<?php
require 'db.php';

if (!isset($_GET['po_no'])) {
    echo "<p>Invalid purchase order number.</p>";
    exit;
}

$po_no = intval($_GET['po_no']);

// Get PO header info
$stmt = $pdo->prepare("
    SELECT po.po_no, po.order_date, s.name, s.tell, b.branches_name
    FROM purchase_order po
    JOIN people s ON po.per_no = s.per_no
    JOIN branches b ON po.br_no = b.br_no
    WHERE po.po_no = ?
");
$stmt->execute([$po_no]);
$order = $stmt->fetch();

if (!$order) {
    echo "<p>Order not found.</p>";
    exit;
}

// ✅ Get all items for this PO using po_no (not pur_no)
$stmt = $pdo->prepare("
    SELECT i.item_name AS item_name, p.qty, p.cost, p.discount,
           (p.qty * p.cost * (1 - p.discount / 100)) AS Total
    FROM purchase p
    JOIN items i ON p.item_no = i.item_no
    WHERE p.po_no = ?
");
$stmt->execute([$po_no]);
$items = $stmt->fetchAll();

// Calculate totals
$totalBeforeDiscount = 0;
$totalDiscount = 0;
$totalAfterDiscount = 0;

foreach ($items as $item) {
    $lineTotal = $item['qty'] * $item['cost'];
    $discountAmount = $lineTotal * $item['discount'] / 100;
    $totalBeforeDiscount += $lineTotal;
    $totalDiscount += $discountAmount;
    $totalAfterDiscount += $lineTotal - $discountAmount;
}
?>

<div class="invoice-box p-3 border rounded bg-light">
    <h4 class="text-center">Purchase Order Invoice</h4>

    <p><strong>Supplier:</strong> <?= htmlspecialchars($order['name']) ?> (<?= htmlspecialchars($order['tell']) ?>)</p>
    <p><strong>Branch:</strong> <?= htmlspecialchars($order['branches_name']) ?></p>
    <p><strong>Invoice #:</strong> <?= $order['po_no'] ?> | <strong>Date:</strong> <?= $order['order_date'] ?></p>

    <table class="table table-bordered table-sm mt-3">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Item</th>
                <th>Qty</th>
                <th>Cost</th>
                <th>Discount %</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $i => $row): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($row['item_name']) ?></td>
                <td><?= $row['qty'] ?></td>
                <td><?= number_format($row['cost'], 2) ?></td>
                <td><?= number_format($row['discount'], 2) ?></td>
                <td><?= number_format($row['Total'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p><strong>Sub Total:</strong> <?= number_format($totalBeforeDiscount, 2) ?></p>
    <p><strong>Total Discount:</strong> -<?= number_format($totalDiscount, 2) ?></p>
    <p><strong>Final Total:</strong> <?= number_format($totalAfterDiscount, 2) ?></p>

    <button class="btn btn-secondary mt-3" onclick="window.print()">🖨️ Print Invoice</button>
</div>
