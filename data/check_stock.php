<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "invent");
if ($conn->connect_error) {
    echo json_encode(["error" => "DB connection failed"]);
    exit;
}

$result = $conn->query("SELECT item_name FROM items WHERE balance < 2");
$lowStock = [];
while ($row = $result->fetch_assoc()) {
    $lowStock[] = $row['item_name'];
}
$conn->close();

echo json_encode($lowStock);