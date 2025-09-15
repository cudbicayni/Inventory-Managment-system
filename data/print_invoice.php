<?php
// DB connection
$conn = new mysqli("localhost", "root", "", "invent");

// Get invoice ID
$invoice_id = isset($_GET['invoice']) ? intval($_GET['invoice']) : 0;

// Fetch totals
$totalRes = $conn->query("SELECT * FROM total WHERE total_no = $invoice_id");
$totals = $totalRes->fetch_assoc();

// Fetch sales (line items)
$salesRes = $conn->query("SELECT s.item_no, s.quantity, s.price, (s.quantity*s.price) as line_total 
                          FROM sales s WHERE s.total_no = $invoice_id");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Invoice #<?= $invoice_id ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <div class="card">
        <div class="card-header text-center">
            <h4>Dreamguys Technologies Pvt Ltd</h4>
            <small>Phone: +1 5656665656 | Email: example@gmail.com</small>
            <hr>
            <h5>Tax Invoice</h5>
        </div>
        <div class="card-body">
            <p><strong>Invoice No:</strong> <?= $invoice_id ?><br>
               <strong>Date:</strong> <?= date("d.m.Y", strtotime($totals['Sal_date'])) ?></p>
            
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th><th>Item</th><th>Price</th><th>Qty</th><th>Total</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $i=1;
                while($row = $salesRes->fetch_assoc()): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td>Item <?= $row['item_no'] ?></td>
                        <td>$<?= number_format($row['price'],2) ?></td>
                        <td><?= $row['quantity'] ?></td>
                        <td>$<?= number_format($row['line_total'],2) ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>

            <p class="text-right">
                Subtotal: $<?= number_format($totals['total'],2) ?><br>
                Discount: -$<?= number_format($totals['discount'],2) ?><br>
                VAT: $<?= number_format($totals['vat'],2) ?><br>
                <strong>Total Payable: $<?= number_format($totals['balance'],2) ?></strong>
            </p>
        </div>
    </div>
    <div class="text-center mt-3">
        <button onclick="window.print()" class="btn btn-success">🖨 Print</button>
    </div>
</div>
</body>
</html>
