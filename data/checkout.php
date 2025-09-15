<?php
session_start();
$conn = new mysqli("localhost", "root", "", "invent");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) die("Your cart is empty.");

$cart          = $_SESSION['cart'];
$vat_rate      = isset($_POST['vat_rate']) ? floatval($_POST['vat_rate']) : 0;
$discount_rate = isset($_POST['discount_rate']) ? floatval($_POST['discount_rate']) : 0;
$per_no        = isset($_POST['per_no']) ? (int)$_POST['per_no'] : 14;
$paid_amount   = isset($_POST['paid_amount']) ? floatval($_POST['paid_amount']) : 0;

$subtotal = 0;
$items = [];

// Fetch items & subtotal
foreach ($cart as $item_id => $qty) {
    $stmt = $conn->prepare("SELECT item_no, item_name, Price FROM items WHERE item_no = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $price = (float)$row['Price'];
        $row['qty']   = (int)$qty;
        $row['Price'] = $price;
        $row['total'] = $price * $qty;
        $items[] = $row;
        $subtotal += $row['total'];
    }
    $stmt->close();
}

// VAT, Discount, Total
$vat      = ($subtotal * $vat_rate) / 100;
$discount = ($subtotal * $discount_rate) / 100;
$total    = $subtotal + $vat - $discount;

// Determine payment details
if ($per_no == 14) {
    // Walk-in must pay fully in cash
    $paid_amount = $total;
    $balance = 0.00;
    $payment_type = 'cash';
} else {
    // Registered customer can have credit
    if ($paid_amount >= $total) {
        $paid_amount = $total;
        $balance = 0.00;
        $payment_type = 'cash';
    } else {
        $balance = $total - $paid_amount;
        $payment_type = 'credit';
    }
}

// ✅ Insert into sale_invoice
$stmt = $conn->prepare("INSERT INTO sales_invoice 
    (per_no, sale_date, payment_type, total_amount, vat, discount, paid_amount, balance) 
    VALUES (?, NOW(), ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isddddd", $per_no, $payment_type, $total, $vat, $discount, $paid_amount, $balance);
$stmt->execute();
$invo_no = $stmt->insert_id;
$stmt->close();

// ✅ Insert into sales (line items)
foreach ($items as $item) {
    $stmt = $conn->prepare("INSERT INTO sales (item_no, quantity, price, invo_no) 
                            VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $item['item_no'], $item['qty'], $item['Price'], $invo_no);
    $stmt->execute();
}
$stmt->close();

// ✅ Insert into receipt (only if some cash was paid)
// ✅ Insert into receipts (only if some cash was paid)
if ($paid_amount > 0) {
    // Since your column is DECIMAL(10,0), store rounded integer
    $amount = round($paid_amount, 0);

    $stmt = $conn->prepare("INSERT INTO receipts (per_no, amount, acc_no, rec_date) 
                            VALUES (?, ?, ?, CURDATE())");
    $acc_no = 1; // you can replace this with actual account id
    $stmt->bind_param("iii", $per_no, $amount, $acc_no);
    $stmt->execute();

    if ($stmt->error) {
        die("MySQL Insert Error (receipts): " . $stmt->error);
    }

    $stmt->close();
}


// Clear cart
unset($_SESSION['cart']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?= $invo_no ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .invoice-container {
            width: 500px;
            margin: 40px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .invoice-header { text-align: center; }
        .invoice-header h3 { margin: 10px 0; }
        .invoice-items, .invoice-totals { width: 100%; margin: 20px 0; }
        .invoice-items th, .invoice-items td {
            border-bottom: 1px dashed #ccc;
            padding: 8px;
            text-align: left;
        }
        .print-btn {
            display: block;
            margin: 20px auto 0;
            padding: 10px 20px;
            background: orange;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="invoice-container">
    <div class="invoice-header">
        <h3>Invoice #<?= $invo_no ?></h3>
        <p>Date: <?= date("d-m-Y H:i") ?></p>
        <p>Customer ID: <?= $per_no ?></p>
        <p>Payment Type: <?= ucfirst($payment_type) ?></p>
    </div>

    <table class="invoice-items">
        <tr>
            <th># Item</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Total</th>
        </tr>
        <?php $i=1; foreach ($items as $item): ?>
        <tr>
            <td><?= $i++ ?>. <?= htmlspecialchars($item['item_name']) ?></td>
            <td>$<?= number_format($item['Price'], 2) ?></td>
            <td><?= $item['qty'] ?></td>
            <td>$<?= number_format($item['total'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <table class="invoice-totals">
        <tr><td>Sub Total :</td><td>$<?= number_format($subtotal, 2) ?></td></tr>
        <tr><td>Discount :</td><td>-$<?= number_format($discount, 2) ?></td></tr>
        <tr><td>VAT (<?= $vat_rate ?>%) :</td><td>$<?= number_format($vat, 2) ?></td></tr>
        <tr><td><b>Total Bill :</b></td><td><b>$<?= number_format($total, 2) ?></b></td></tr>
        <tr><td>Paid :</td><td>$<?= number_format($paid_amount, 2) ?></td></tr>
        <tr><td><b>Balance Due :</b></td><td><b>$<?= number_format($balance, 2) ?></b></td></tr>
    </table>

    <p style="text-align:center;font-size:12px;">Thank you for your business!</p>
    <button class="print-btn" onclick="window.print()">Print Receipt</button>
</div>

</body>
</html>
