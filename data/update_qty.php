<?php
session_start();
header("Content-Type: application/json");

// DB connection
$conn = new mysqli("localhost", "root", "", "invent");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB failed"]);
    exit;
}

// Validate input
$itemId = isset($_POST['item_id']) ? (int)$_POST['item_id'] : 0;
$qty    = isset($_POST['qty']) ? (int)$_POST['qty'] : 0;

if ($itemId <= 0 || $qty <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
    exit;
}

// Update session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$_SESSION['cart'][$itemId] = $qty;

// Fetch item price from DB
$sql = "SELECT Price FROM items WHERE item_no = $itemId";
$res = $conn->query($sql);
if (!$res || $res->num_rows == 0) {
    echo json_encode(["success" => false, "message" => "Item not found"]);
    exit;
}
$row = $res->fetch_assoc();
$price = (float)$row['Price'];

// Calculate subtotal
$subtotal = $price * $qty;

// Recalculate total
$total = 0;
if (!empty($_SESSION['cart'])) {
    $ids = implode(",", array_keys($_SESSION['cart']));
    $sql = "SELECT item_no, Price FROM items WHERE item_no IN ($ids)";
    $res = $conn->query($sql);
    while ($r = $res->fetch_assoc()) {
        $id = $r['item_no'];
        $q = $_SESSION['cart'][$id] ?? 1;
        $total += $r['Price'] * $q;
    }
}

echo json_encode([
    "success"  => true,
    "subtotal" => $subtotal,
    "total"    => $total
]);
