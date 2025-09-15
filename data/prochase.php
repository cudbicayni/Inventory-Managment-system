<?php
session_start();
$conn = new mysqli("localhost", "root", "", "invent");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['purchase_date'];
    $qty = $_POST['quantity'];
    $cost = $_POST['cost'];
    $discount = $_POST['discount'];
    $total = $_POST['total'];
    $item_id = $_POST['item_id'];

    // Insert into purchases
    $stmt = $conn->prepare("INSERT INTO purchases (purchase_date, quantity, cost, discount, total, item_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siddii", $date, $qty, $cost, $discount, $total, $item_id);
    $stmt->execute();

    // Get the last inserted invoice ID
    $invoice_id = $stmt->insert_id;

    // Store in session for invoice
    $_SESSION['invoice_id'] = $invoice_id;

    echo json_encode(["status" => "success", "invoice_id" => $invoice_id]);
}
?>
