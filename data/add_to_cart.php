<?php
session_start();

// Check if item_id was sent via POST
if (!isset($_POST['item_id'])) {
    echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
    exit;
}

$itemId = (int) $_POST['item_id'];

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add the item to the cart or increase its quantity
if (isset($_SESSION['cart'][$itemId])) {
    $_SESSION['cart'][$itemId]++;
} else {
    $_SESSION['cart'][$itemId] = 1;
}

// Return the updated cart count
echo count($_SESSION['cart']);
