<?php
$invoice_id = intval($_GET['total_no'] ?? 0);  // or from POST

if ($invoice_id === 0) {
    die("❌ Invalid or missing invoice ID.");
}

$conn = new mysqli("localhost", "root", "", "invent");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Call the stored procedure
$stmt = $conn->prepare("CALL Invoices(?)");
$stmt->bind_param("i", $invoice_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
    die("❌ No invoice found for ID #$invoice_id.");
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Invoice #<?= $invoice_id ?></title>
</head>
<body>
    <h2>🧾 Invoice #<?= $invoice_id ?></h2>
    <p><strong>Date:</strong> <?= date('Y-m-d') ?></p>
    <button onclick="window.print()">🖨️ Print Invoice</button>

    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>#</th>
            <th>Item</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Subtotal</th>
        </tr>

        <?php
        $i = 1;
        $grand = 0;
        $row = null;
        while ($item = $result->fetch_assoc()):
            $row = $item;  // Save last row for totals
            $subtotal = $item['Qty'] * $item['Prices'];
            $grand += $subtotal;
        ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($item['items']) ?></td>
            <td><?= $item['Qty'] ?></td>
            <td>$<?= number_format($item['Prices'], 2) ?></td>
            <td>$<?= number_format($subtotal, 2) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <?php if ($row): ?>
        <p><strong>Total:</strong> $<?= number_format($row['Total'], 2) ?></p>
        <p><strong>VAT (5%):</strong> $<?= number_format($row['VAT'], 2) ?></p>
        <p><strong>Balance:</strong> $<?= number_format($row['Balance'], 2) ?></p>
    <?php endif; ?>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
