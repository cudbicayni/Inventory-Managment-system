<?php
include 'db1.php';

$sql = "SELECT i.item_no, c.cat_name as category_name, i.item_name as item_name, i.price, i.image, c.cat_no as category_id
        FROM items i
        JOIN categories c ON i.cat_no = c.cat_no";

$result = $conn->query($sql);

$items = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
} else {
    // Debug SQL error
    echo "SQL Error: " . $conn->error;
    exit;
}

header('Content-Type: application/json');
echo json_encode($items);
?>
