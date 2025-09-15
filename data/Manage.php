<?php
ob_start();
session_start();

$msg = '';
$invoiceData = null;

$mysqli = @new mysqli("localhost", "root", "", "invent");
if ($mysqli->connect_errno) {
    http_response_code(500);
    die("DB connect failed: " . htmlspecialchars($mysqli->connect_error));
}
$mysqli->set_charset("utf8mb4");

function fetchAllAssoc(mysqli $db, string $sql): array {
    $out = [];
    if ($res = $db->query($sql)) {
        while ($row = $res->fetch_assoc()) { $out[] = $row; }
        $res->free();
    }
    return $out;
}

$suppliers = fetchAllAssoc($mysqli, "SELECT supp_no, supp_name FROM suppliers ORDER BY supp_name ASC");
$branches  = fetchAllAssoc($mysqli, "SELECT br_no, branches_name FROM branches ORDER BY branches_name ASC");
$itemsList = fetchAllAssoc($mysqli, "SELECT item_no, item_name FROM items ORDER BY item_name ASC");

$itemOptionsHtml = '<option value="">Select item</option>';
foreach ($itemsList as $it) {
    $itemOptionsHtml .= '<option value="' . intval($it['item_no']) . '">' . htmlspecialchars($it['item_name']) . '</option>';
}

if (isset($_GET['po_no']) && intval($_GET['po_no']) > 0) {
    $po_no = intval($_GET['po_no']);
    $stmtOrder = $mysqli->prepare("SELECT supp_no, br_no, order_date FROM purchase_order WHERE po_no = ?");
    if ($stmtOrder) {
        $stmtOrder->bind_param("i", $po_no);
        $stmtOrder->execute();
        $resOrder = $stmtOrder->get_result();
        $rowOrder = $resOrder ? $resOrder->fetch_assoc() : null;
        $stmtOrder->close();

        if ($rowOrder) {
            $order_date = $rowOrder['order_date'] ?? '';
            $supp_no = intval($rowOrder['supp_no'] ?? 0);

            $supplierName = '';
            $supplierTell = '';
            $stmtSupp = $mysqli->prepare("SELECT supp_name, supp_tell FROM suppliers WHERE supp_no = ?");
            if ($stmtSupp) {
                $stmtSupp->bind_param("i", $supp_no);
                $stmtSupp->execute();
                $rs = $stmtSupp->get_result();
                if ($rs) {
                    $rowSupp = $rs->fetch_assoc();
                    $supplierName = $rowSupp['supp_name'] ?? '';
                    $supplierTell = $rowSupp['supp_tell'] ?? '';
                }
                $stmtSupp->close();
            }

            $stmtInvoice = $mysqli->prepare("
                SELECT i.item_name, p.qty, p.cost, p.discount
                FROM purchase p
                JOIN items i ON p.item_no = i.item_no
                WHERE p.po_no = ?
            ");
            if ($stmtInvoice) {
                $stmtInvoice->bind_param("i", $po_no);
                $stmtInvoice->execute();
                $resInv = $stmtInvoice->get_result();

                $items = [];
                $totalBeforeDiscount = 0.0;
                $totalDiscountAmount = 0.0;

                if ($resInv) {
                    while ($row = $resInv->fetch_assoc()) {
                        $qty = max(0, intval($row['qty'] ?? 0));
                        $costPerUnit = (float)($row['cost'] ?? 0);
                        $discountPct = (float)($row['discount'] ?? 0);
                        $lineTotal = $costPerUnit * $qty;
                        $discountAmount = $lineTotal * ($discountPct / 100);

                        $items[] = [
                            'name' => $row['item_name'] ?? '',
                            'qty' => $qty,
                            'cost' => $costPerUnit,
                            'discountPct' => $discountPct,
                            'discountAmount' => $discountAmount,
                            'lineTotal' => $lineTotal
                        ];

                        $totalBeforeDiscount += $lineTotal;
                        $totalDiscountAmount += $discountAmount;
                    }
                }
                $stmtInvoice->close();

                $totalAfterDiscount = $totalBeforeDiscount - $totalDiscountAmount;

                $invoiceData = [
                    'po_no' => $po_no,
                    'supplierName' => $supplierName,
                    'supplierTell' => $supplierTell,
                    'items' => $items,
                    'totalBeforeDiscount' => $totalBeforeDiscount,
                    'totalDiscount' => $totalDiscountAmount,
                    'totalAfterDiscount' => $totalAfterDiscount,
                    'order_date' => $order_date
                ];
            }
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['multi_order'])) {
    $postedItemNos = (isset($_POST['item_no']) && is_array($_POST['item_no'])) ? $_POST['item_no'] : [];
    if (count($postedItemNos) === 0) {
        $msg = "<div class='alert alert-danger mb-2'>Please add at least one item row.</div>";
    } else {
        $mysqli->begin_transaction();
        try {
            $stmtOrder = $mysqli->prepare("INSERT INTO purchase_order (supp_no, br_no, order_date) VALUES (?, ?, ?)");
            if (!$stmtOrder) throw new Exception("Prepare purchase_order failed: " . $mysqli->error);

            $supp_no = intval($_POST['supp_num'] ?? 0);
            $br_no = intval($_POST['branch_num'] ?? 0);
            $order_date = $_POST['order_date'] ?? date('Y-m-d');

            if ($supp_no <= 0) throw new Exception("Invalid supplier selected.");
            if ($br_no <= 0) throw new Exception("Invalid branch selected.");

            $stmtOrder->bind_param("iis", $supp_no, $br_no, $order_date);
            if (!$stmtOrder->execute()) throw new Exception("Insert purchase_order failed: " . $stmtOrder->error);
            $po_no = $mysqli->insert_id;
            $stmtOrder->close();

            $stmtPurchase = $mysqli->prepare("INSERT INTO purchase (po_no, qty, cost, discount, item_no) VALUES (?, ?, ?, ?, ?)");
            if (!$stmtPurchase) throw new Exception("Prepare purchase failed: " . $mysqli->error);

            $countRows = max(
                count($_POST['item_no'] ?? []),
                count($_POST['qty'] ?? []),
                count($_POST['cost'] ?? []),
                count($_POST['discount'] ?? [])
            );

            $insertedRows = 0;
            for ($i = 0; $i < $countRows; $i++) {
                $item_no = intval($_POST['item_no'][$i] ?? 0);
                $qty = intval($_POST['qty'][$i] ?? 0);
                $cost = (float)($_POST['cost'][$i] ?? 0);
                $discountPct = (float)($_POST['discount'][$i] ?? 0);

                if ($item_no <= 0 || $qty <= 0) continue;
                if ($cost < 0) $cost = 0;
                if ($discountPct < 0) $discountPct = 0;
                if ($discountPct > 100) $discountPct = 100;

                $stmtPurchase->bind_param("iiddi", $po_no, $qty, $cost, $discountPct, $item_no);
                if (!$stmtPurchase->execute()) {
                    throw new Exception("Insert purchase failed on row " . ($i + 1) . ": " . $stmtPurchase->error);
                }
                $insertedRows++;
            }
            $stmtPurchase->close();

            if ($insertedRows === 0) {
                throw new Exception("No valid purchase rows to insert.");
            }

            $mysqli->commit();

            header("Location: " . strtok($_SERVER['REQUEST_URI'], '?') . "?po_no=" . $po_no);
            exit;
        } catch (Exception $e) {
            $mysqli->rollback();
            $msg = "<div class='alert alert-danger mb-2'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
}


?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Manage Purchase Order</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    body { background:#f6f8fa; }
    .invoice-box {
        max-width: 720px;
        margin: auto;
        background: #fff;
        padding: 15px;
        border-radius: 8px;
        font-family: Arial, sans-serif;
        box-shadow: 0 0 10px rgba(0,0,0,0.15);
    }
    .divider { border-top: 1px dashed #999; margin: 10px 0; }
    .row-line { display: flex; justify-content: space-between; font-size: 13px; }
    .row-line.bold { font-weight: bold; border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 5px; }
    .row-line.total { font-weight: bold; }
    .row-line.total.grand { font-size: 15px; border-top: 2px solid #000; margin-top: 5px; padding-top: 5px; }
    .right { text-align: right; }
    #printBtn {
        display: block; width: 100%; background-color: #9115deff; color: white;
        padding: 10px; font-size: 14px; border: none; border-radius: 4px; margin-top: 15px; cursor: pointer;
    }
    #printBtn:hover { background-color: #9115deff; }
    @media print {
        #printBtn { display: none; }
        .invoice-box { box-shadow: none; border: none; max-width: 100%; }
        .modal, .modal-backdrop, .btn, .container, .divider { box-shadow: none !important; }
    }
</style>
</head>
<body>


<div class="container py-4">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
        Open Modal
    </button>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document"><!-- large, non-scrollable -->
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTitle">Manage Purchase Order</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <div class="invoice-box">
                <h3 class="mb-3">Purchase Order</h3>

                <?php if ($msg): ?>
                    <?php echo $msg; ?>
                <?php endif; ?>

                <!-- FORM WRAPPER -->
                <div id="po-form" style="display:none;">
                    <form id="frm_purchase_order_multi" method="post" action="" class="needs-validation" novalidate>
                        <input type="hidden" name="multi_order" value="1">

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="supp_num">Supplier</label>
                                <select id="supp_num" name="supp_num" class="form-control select-search" required>
                                    <option value="">Select supplier</option>
                                    <?php foreach ($suppliers as $s): ?>
                                        <option value="<?php echo intval($s['supp_no']); ?>"><?php echo htmlspecialchars($s['supp_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Please select a supplier.</div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="branch_num">Branch</label>
                                <select id="branch_num" name="branch_num" class="form-control select-search" required>
                                    <option value="">Select branch</option>
                                    <?php foreach ($branches as $b): ?>
                                        <option value="<?php echo intval($b['br_no']); ?>"><?php echo htmlspecialchars($b['branches_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Please select a branch.</div>
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="form-group col-md-4">
                                <label for="order_date">Order Date</label>
                                <input type="date" id="order_date" name="order_date" class="form-control" required value="<?php echo htmlspecialchars(date('Y-m-d')); ?>">
                                <div class="invalid-feedback">Please provide an order date.</div>
                            </div>
                        </div>

                        <hr class="divider">
                        <h5>Purchase</h5>

                        <div id="itemsContainer">
                            <div class="item-row form-row align-items-end">
                                <div class="form-group col-md-5">
                                    <label>Item</label>
                                    <select name="item_no[]" class="form-control select-search" required>
                                        <?php echo $itemOptionsHtml; ?>
                                    </select>
                                    <div class="invalid-feedback">Select an item.</div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Cost</label>
                                    <input type="number" step="0.01" name="cost[]" class="form-control" min="0" required>
                                    <div class="invalid-feedback">Enter cost.</div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Qty</label>
                                    <input type="number" name="qty[]" class="form-control" min="1" required>
                                    <div class="invalid-feedback">Enter qty.</div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Discount %</label>
                                    <input type="number" step="0.01" name="discount[]" class="form-control" min="0" max="100" value="0">
                                </div>
                                <div class="form-group col-md-1 d-flex">
                                    <button type="button" class="btn btn-danger remove-item" aria-label="Remove item">X</button>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-success mb-3" id="addItem">Add Item</button>
                        <!-- <button type="submit" class="btn btn-primary mb-3">Save Order</button> -->
                    </form>
                </div>

                <!-- INVOICE WRAPPER -->
                <div id="po-invoice" style="display:none;">
                    <div class="invoice-box">
                        <div style="text-align:center;">
                            <img src="assets/img/users/Aslan b.jpg" alt="Company Logo" style="max-height:50px; width:auto;">
                            <h5>Aslan style</h5>
                            <p>Phone Number: +252 61xxxxx</p>
                            <p>Email: aslanb@gmail.com</p>
                            <h4>Invoice</h4>
                        </div>

                        <div class="divider"></div>

                        <div class="row-line">
                            <span><strong>Name:</strong> <?php echo htmlspecialchars($invoiceData['supplierName'] ?? ''); ?></span>
                            <span><strong>Tell:</strong> <?php echo htmlspecialchars($invoiceData['supplierTell'] ?? ''); ?></span>
                        </div>
                        <div class="row-line">
                            <span><strong>Invoice No:</strong> <?php echo htmlspecialchars((string)($invoiceData['po_no'] ?? '')); ?></span>
                            <span><strong>Date:</strong> <?php echo htmlspecialchars($invoiceData['order_date'] ?? ''); ?></span>
                        </div>

                        <div class="divider"></div>

                        <div class="row-line bold">
                            <span># Item</span>
                            <span>Cost</span>
                            <span>Qty</span>
                            <span class="right">Total</span>
                        </div>

                        <?php foreach (($invoiceData['items'] ?? []) as $index => $item): ?>
                        <div class="row-line">
                            <span><?php echo ($index+1) . '. ' . htmlspecialchars($item['name'] ?? ''); ?></span>
                            <span>$<?php echo number_format((float)($item['cost'] ?? 0), 2); ?></span>
                            <span><?php echo intval($item['qty'] ?? 0); ?></span>
                            <span class="right">$<?php echo number_format((float)(($item['qty'] ?? 0) * ($item['cost'] ?? 0)), 2); ?></span>
                        </div>
                        <?php endforeach; ?>

                        <div class="divider"></div>

                        <div class="row-line total">
                            <span>Sub Total :</span>
                            <span class="right">$<?php echo number_format((float)($invoiceData['totalBeforeDiscount'] ?? 0), 2); ?></span>
                        </div>
                        <div class="row-line total">
                            <span>Discount :</span>
                            <span class="right">-$<?php echo number_format((float)($invoiceData['totalDiscount'] ?? 0), 2); ?></span>
                        </div>
                        <div class="row-line total grand">
                            <span>Total :</span>
                            <span class="right">$<?php echo number_format((float)($invoiceData['totalAfterDiscount'] ?? 0), 2); ?></span>
                        </div>

                        <div style="text-align:center; margin-top:15px;">
                            <p>Thank You For Shopping With Us. Please Come Again</p>
                        </div>

                        <button id="printBtn" onclick="window.print()">Print Receipt</button>
                    </div>
                </div>

            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <?php if (!$invoiceData): ?>
            <button type="button" class="btn btn-primary" id="saveFormBtn">Save Order</button>
            <?php else: ?>
            <button type="button" class="btn btn-primary" id="saveFormBtn" style="display:none;">Save Changes</button>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var hasMsg = <?php echo json_encode((bool)$msg); ?>;
    var hasInvoice = <?php echo json_encode((bool)$invoiceData); ?>;

    var $modal = $('#myModal');
    var $formWrap = $('#po-form');
    var $invoiceWrap = $('#po-invoice');
    var $saveBtn = $('#saveFormBtn');

    // Default view: if invoice initially present, show invoice once, then default to form
    var defaultView = hasInvoice ? 'invoice' : 'form';

    function showView(view) {
        if (view === 'invoice') {
            $invoiceWrap.show();
            $formWrap.hide();
            $saveBtn.hide();
        } else {
            $formWrap.show();
            $invoiceWrap.hide();
            $saveBtn.show();
        }
    }

    // Initial open when invoice or message exists
    if (hasInvoice || hasMsg) {
        showView(hasInvoice ? 'invoice' : 'form');
        $modal.modal('show');
    }

    // Clean the URL if invoice shown, and set future default to form
    try {
        if (hasInvoice && 'URL' in window && 'URLSearchParams' in window && history && history.replaceState) {
            var url = new URL(location.href);
            if (url.searchParams.has('po_no')) {
                url.searchParams.delete('po_no');
                var clean = url.origin + url.pathname + (url.searchParams.toString() ? '?' + url.searchParams.toString() : '') + (url.hash || '');
                history.replaceState({}, document.title, clean);
                defaultView = 'form'; // future opens show form
            }
        }
    } catch (e) {}

    // When user clicks the Open Modal button, show the current default view
    $(document).on('click', '[data-target="#myModal"]', function() {
        showView(defaultView);
    });

    // When modal is closed, reset to form for next open
    $modal.on('hidden.bs.modal', function() {
        defaultView = 'form';
        showView(defaultView);
    });

    // Initialize Select2
    function initSelect2(scope) {
        (scope || $(document)).find('.select-search').select2({
            width: '100%',
            placeholder: 'Select...',
            allowClear: true
        });
    }
    initSelect2();

    // Add item row
    $('#addItem').on('click', function() {
        var row = $('<div class="item-row form-row align-items-end">\
            <div class="form-group col-md-5">\
                <label>Item</label>\
                <select name="item_no[]" class="form-control select-search" required><?php echo addslashes($itemOptionsHtml); ?></select>\
                <div class="invalid-feedback">Select an item.</div>\
            </div>\
            <div class="form-group col-md-2">\
                <label>Cost</label>\
                <input type="number" step="0.01" name="cost[]" class="form-control" min="0" required>\
                <div class="invalid-feedback">Enter cost.</div>\
            </div>\
            <div class="form-group col-md-2">\
                <label>Qty</label>\
                <input type="number" name="qty[]" class="form-control" min="1" required>\
                <div class="invalid-feedback">Enter qty.</div>\
            </div>\
            <div class="form-group col-md-2">\
                <label>Discount %</label>\
                <input type="number" step="0.01" name="discount[]" class="form-control" min="0" max="100" value="0">\
            </div>\
            <div class="form-group col-md-1 d-flex">\
                <button type="button" class="btn btn-danger remove-item" aria-label="Remove item">X</button>\
            </div>\
        </div>');
        $('#itemsContainer').append(row);
        initSelect2(row);
    });

    // Remove item row
    $(document).on('click', '.remove-item', function() {
        $(this).closest('.item-row').remove();
    });

    // Bootstrap validation and footer save
    var form = document.getElementById('frm_purchase_order_multi');
    if (form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                $('#myModal').modal('show');
            }
            form.classList.add('was-validated');
        });
        $('#saveFormBtn').on('click', function() {
            form.requestSubmit();
        });
    }
});
</script>
</body>
</html>
