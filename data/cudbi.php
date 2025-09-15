<?php
ob_start();

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

/* ---------------- FETCH INVOICE ---------------- */
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
                        if ($discountPct < 0) $discountPct = 0;
                        if ($discountPct > 100) $discountPct = 100;

                        $lineTotal = $costPerUnit * $qty;
                        $discountAmount = $lineTotal * ($discountPct / 100);
                        $lineTotalAfter = $lineTotal - $discountAmount;

                        $items[] = [
                            'name' => $row['item_name'] ?? '',
                            'qty' => $qty,
                            'cost' => $costPerUnit,
                            'discountPct' => $discountPct,
                            'discountAmount' => $discountAmount,
                            'lineTotal' => $lineTotal,
                            'lineTotalAfter' => $lineTotalAfter
                        ];

                        $totalBeforeDiscount += $lineTotal;
                        $totalDiscountAmount += $discountAmount;
                    }
                }
                $stmtInvoice->close();

                $totalAfterDiscount = $totalBeforeDiscount - $totalDiscountAmount;

                // Optional tax configuration
                $taxRate = 0.00; // e.g., 0.05 for 5% VAT
                $taxAmount = $totalAfterDiscount * $taxRate;
                $grandTotal = $totalAfterDiscount + $taxAmount;

                $invoiceData = [
                    'po_no' => $po_no,
                    'supplierName' => $supplierName,
                    'supplierTell' => $supplierTell,
                    'items' => $items,
                    'totalBeforeDiscount' => $totalBeforeDiscount,
                    'totalDiscount' => $totalDiscountAmount,
                    'totalAfterDiscount' => $totalAfterDiscount,
                    'invoiceTaxRate' => $taxRate,
                    'invoiceTaxAmount' => $taxAmount,
                    'grandTotal' => $grandTotal,
                    'order_date' => $order_date
                ];
            }
        }
    }
}

/* ---------------- INSERT ORDER ---------------- */
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['multi_order'])) {
    $postedItemNos = (isset($_POST['item_no']) && is_array($_POST['item_no'])) ? $_POST['item_no'] : [];
    if (count($postedItemNos) === 0) {
        $msg = "<div class='alert alert-danger mb-2'>Please add at least one item row.</div>";
    } else {
        $mysqli->begin_transaction();
        try {
            $stmtOrder = $mysqli->prepare("INSERT INTO purchase_order (supp_no, br_no, order_date) VALUES (?, ?, ?)");
            if (!$stmtOrder) throw new Exception("Prepare purchase_order failed: " . $mysqli->error);

            $supp_no = intval($_POST['supp_no'] ?? 0);
            $br_no = intval($_POST['branch_num'] ?? 0);
            $order_date = $_POST['order_date'] ?? date('Y-m-d');

            // Validate date format Y-m-d
            $d = DateTime::createFromFormat('Y-m-d', $order_date);
            if (!$d || $d->format('Y-m-d') !== $order_date) {
                throw new Exception("Invalid date format.");
            }

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

            // Optional: verify items exist to avoid FK issues
            $validItems = [];
            if ($countRows > 0) {
                $itemIds = array_filter(array_map('intval', $_POST['item_no']));
                if ($itemIds) {
                    $in = implode(',', array_fill(0, count($itemIds), '?'));
                    $types = str_repeat('i', count($itemIds));
                    $stmtChk = $mysqli->prepare("SELECT item_no FROM items WHERE item_no IN ($in)");
                    if ($stmtChk) {
                        $stmtChk->bind_param($types, ...$itemIds);
                        $stmtChk->execute();
                        $rs = $stmtChk->get_result();
                        while ($r = $rs->fetch_assoc()) { $validItems[(int)$r['item_no']] = true; }
                        $stmtChk->close();
                    }
                }
            }

            for ($i = 0; $i < $countRows; $i++) {
                $item_no = intval($_POST['item_no'][$i] ?? 0);
                $qty = intval($_POST['qty'][$i] ?? 0);
                $cost = (float)($_POST['cost'][$i] ?? 0);
                $discountPct = (float)($_POST['discount'][$i] ?? 0);

                if ($item_no <= 0 || $qty <= 0) continue;
                if (!isset($validItems[$item_no])) continue;

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
<title>Purchase Order / Invoice</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- CSS -->

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
/* General tweaks */
.select2-container { width: 100% !important; }

/* Invoice styles */
.invoice-box {
  max-width: 900px;
  margin: 0 auto;
  padding: 16px;
  background: #fff;
  color: #000;
  border: 1px solid #e5e5e5;
  border-radius: 6px;
  font-size: 14px;
}
.invoice-box .divider { border-top: 1px dashed #bbb; margin: 10px 0; }
.invoice-box .row-line {
  display: grid;
  grid-template-columns: 1fr 90px 60px 70px 110px 120px;
  gap: 8px;
  align-items: center;
  margin: 6px 0;
}
.invoice-box .row-line.bold { font-weight: 600; }
.invoice-box .row-line.total { grid-template-columns: 1fr 1fr; }
.invoice-box .row-line.total .right { justify-self: end; }
.invoice-box .row-line .right { justify-self: end; }
.invoice-box .total.grand { font-size: 16px; font-weight: 700; }
#printBtn { margin-top: 10px; }

@media (max-width: 576px) {
  .invoice-box .row-line {
    grid-template-columns: 1fr 70px 50px 60px 90px 100px;
    font-size: 13px;
  }
}

/* Print-only:
   We clone the invoice into #__print_area__ and only show that during printing */
@media print {
  body * { visibility: hidden !important; }

  #__print_area__, #__print_area__ * {
    visibility: visible !important;
  }

  #__print_area__ {
    position: static !important;
    width: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
    background: #b50affff;
  }

  #__print_area__ .invoice-box {
    position: static !important;
    width: 100% !important;
    max-width: 100% !important;
    border: none !important;
    box-shadow: none !important;
  }

  .modal, .modal-backdrop, .btn, .close {
    display: none !important;
  }
}

/* Form tweaks */
.item-row .remove-item { align-self: flex-end; }
</style>
</head>
<body>

<div class="container py-3">
  <?php if (!empty($msg)) echo $msg; ?>

  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
    Open Modal
  </button>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Purchase Order</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
      </div>

      <div class="modal-body">

        <!-- FORM WRAPPER -->
        <div id="po-form" <?php echo $invoiceData ? 'style="display:none;"' : ''; ?>>
          <form id="frm_purchase_order_multi" method="post" action="" class="needs-validation" novalidate>
            <input type="hidden" name="multi_order" value="1">

            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="supp_no">Supplier</label>
                <select id="supp_no" name="supp_no" class="form-control" required>
                  <option value="">Select supplier</option>
                  <?php foreach ($suppliers as $s): ?>
                  <option value="<?php echo intval($s['supp_no']); ?>"><?php echo htmlspecialchars($s['supp_name']); ?></option>
                  <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">Please select a supplier.</div>
              </div>

              <div class="form-group col-md-6">
                <label for="branch_num">Branch</label>
                <select id="branch_num" name="branch_num" class="form-control" required>
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
                <input type="date" id="order_date" name="order_date" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
                <div class="invalid-feedback">Please provide an order date.</div>
              </div>
            </div>

            <h5>Items</h5>
            <div id="itemsContainer">
              <div class="item-row form-row align-items-end">
                <div class="form-group col-md-5">
                  <label>Item</label>
                  <select name="item_no[]" class="form-control" required>
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
          </form>
        </div>

        <!-- INVOICE WRAPPER -->
        <div id="po-invoice" <?php echo $invoiceData ? '' : 'style="display:none;"'; ?>>
          <div class="invoice-box">
            <div style="text-align:center;">
              <h5>Aslan Style</h5>
              <p>Phone Number: +252 61xxxxx</p>
              <p>Email: aslanb@gmail.com</p>
              <h4>Invoice</h4>
            </div>

            <div class="divider"></div>

            <div class="row-line" style="grid-template-columns:1fr 1fr;">
              <span><strong>Name:</strong> <?php echo htmlspecialchars($invoiceData['supplierName'] ?? ''); ?></span>
              <span class="right"><strong>Tell:</strong> <?php echo htmlspecialchars($invoiceData['supplierTell'] ?? ''); ?></span>
            </div>
            <div class="row-line" style="grid-template-columns:1fr 1fr;">
              <span><strong>Invoice No:</strong> <?php echo htmlspecialchars((string)($invoiceData['po_no'] ?? '')); ?></span>
              <span class="right"><strong>Date:</strong> <?php echo htmlspecialchars($invoiceData['order_date'] ?? ''); ?></span>
            </div>

            <div class="divider"></div>

            <div class="row-line bold">
              <span># Item</span>
              <span>Cost</span>
              <span>Qty</span>
              <span>Disc %</span>
              <span class="right">Line Total</span>
              <span class="right">After Disc</span>
            </div>

            <?php foreach (($invoiceData['items'] ?? []) as $index => $item): ?>
            <div class="row-line">
              <span><?php echo ($index+1) . '. ' . htmlspecialchars($item['name'] ?? ''); ?></span>
              <span>$<?php echo number_format((float)($item['cost'] ?? 0), 2); ?></span>
              <span><?php echo intval($item['qty'] ?? 0); ?></span>
              <span><?php echo number_format((float)($item['discountPct'] ?? 0), 2); ?>%</span>
              <span class="right">$<?php echo number_format((float)($item['lineTotal'] ?? 0), 2); ?></span>
              <span class="right">$<?php echo number_format((float)($item['lineTotalAfter'] ?? 0), 2); ?></span>
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
            <div class="row-line total">
              <span>Total After Discount :</span>
              <span class="right">$<?php echo number_format((float)($invoiceData['totalAfterDiscount'] ?? 0), 2); ?></span>
            </div>
            <?php if (!empty($invoiceData) && isset($invoiceData['invoiceTaxRate']) && $invoiceData['invoiceTaxRate'] > 0): ?>
            <div class="row-line total">
              <span>Tax (<?php echo number_format($invoiceData['invoiceTaxRate']*100, 2); ?>%) :</span>
              <span class="right">$<?php echo number_format((float)$invoiceData['invoiceTaxAmount'], 2); ?></span>
            </div>
            <?php endif; ?>
            <div class="row-line total grand">
              <span>Grand Total :</span>
              <span class="right">$<?php echo number_format((float)($invoiceData['grandTotal'] ?? ($invoiceData['totalAfterDiscount'] ?? 0)), 2); ?></span>
            </div>

            <div style="text-align:center; margin-top:15px;">
              <p>Thank You For Shopping With Us. Please Come Again</p>
            </div>

            <button id="printBtn" class="btn btn-outline-secondary" type="button">Print Receipt</button>
          </div>
        </div>

      </div><!-- modal-body -->

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="saveFormBtn">Save Order</button>
      </div>
    </div>
  </div>
</div>

<!-- JS -->
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
// Robust print: clone invoice into a top-layer container and print only that
function printInvoice() {
  var $invoice = $('.invoice-box');
  if ($invoice.length === 0) {
    window.print();
    return;
  }

  // Create dedicated print area
  var $printArea = $('<div id="__print_area__"></div>').css({
    position: 'fixed',
    left: 0, top: 0, right: 0, bottom: 0,
    background: '#fff',
    padding: 0,
    margin: 0,
    zIndex: 2147483647
  });

  // Clone to avoid altering UI
  var $clone = $invoice.clone(true);
  // Remove interactive controls in clone
  $clone.find('#printBtn, .modal-footer, .modal-header, .btn, .close').remove();

  $printArea.append($clone);
  $('body').append($printArea);

  setTimeout(function() {
    window.print();
    $printArea.remove();
  }, 50);
}

document.addEventListener('DOMContentLoaded', function() {
    var hasMsg = <?php echo json_encode((bool)$msg); ?>;
    var hasInvoice = <?php echo json_encode((bool)$invoiceData); ?>;

    var $modal = $('#myModal');
    var $formWrap = $('#po-form');
    var $invoiceWrap = $('#po-invoice');
    var $saveBtn = $('#saveFormBtn');

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

    if (hasInvoice || hasMsg) {
        showView(hasInvoice ? 'invoice' : 'form');
    }

    // Open the modal if there's an invoice, a message, or a POST attempt
    var shouldOpen = <?php echo json_encode((bool)$invoiceData || (bool)$msg || ($_SERVER['REQUEST_METHOD']==='POST')); ?>;
    if (shouldOpen) {
        $modal.modal('show');
    }

    // Clean URL after showing invoice (remove po_no from query)
    try {
        if (hasInvoice && 'URL' in window && 'URLSearchParams' in window && history && history.replaceState) {
            var url = new URL(location.href);
            if (url.searchParams.has('po_no')) {
                url.searchParams.delete('po_no');
                var clean = url.origin + url.pathname + (url.searchParams.toString() ? '?' + url.searchParams.toString() : '') + (url.hash || '');
                history.replaceState({}, document.title, clean);
                defaultView = 'form';
            }
        }
    } catch (e) {}

    $(document).on('click', '[data-target="#myModal"]', function() {
        showView(defaultView);
    });

    $modal.on('hidden.bs.modal', function() {
        defaultView = 'form';
        showView(defaultView);
    });

    // Add item row
    $('#addItem').on('click', function() {
        var row = $('<div class="item-row form-row align-items-end">\
            <div class="form-group col-md-5">\
                <label>Item</label>\
                <select name="item_no[]" class="form-control" required><?php echo addslashes($itemOptionsHtml); ?></select>\
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

        // Initialize select2 for the new item select
        var $last = $('#itemsContainer .item-row:last select[name="item_no[]"]');
        if ($.fn.select2) {
            $last.select2({ width: '100%', dropdownParent: $('#myModal') });
        }
    });

    $(document).on('click', '.remove-item', function() {
        $(this).closest('.item-row').remove();
    });

    // Form validation and submission
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

    // Initialize Select2 on initial selects
    if ($.fn.select2) {
        $('#supp_no, #branch_num, select[name="item_no[]"]').select2({
            width: '100%',
            dropdownParent: $('#myModal')
        });
    }

    // Print button handler
    $(document).on('click', '#printBtn', function() {
        var $modal = $('#myModal');
        if ($modal.is(':visible')) {
            printInvoice();
        } else {
            $modal.one('shown.bs.modal', function() { printInvoice(); });
            $modal.modal('show');
        }
    });
});
</script>
</body>
</html>