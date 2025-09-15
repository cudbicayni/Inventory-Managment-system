<?php
// get_purchase_order.php
// Returns JSON: { header: {po_no, supp_no, br_no, order_date}, items: [{item_no, qty, cost, discount}, ...] }

require 'db.php'; // make sure this points to your PDO connection

// Optional: show errors during development
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

$po_no = isset($_GET['id']) ? trim($_GET['id']) : null;
if ($po_no === null || $po_no === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Missing id']);
    exit;
}

try {
    // Fetch header
    $sqlH = "SELECT po_no, per_no, br_no, order_date
             FROM purchase_order
             WHERE po_no = ?";
    $stH = $pdo->prepare($sqlH);
    $stH->execute([$po_no]);
    $header = $stH->fetch(PDO::FETCH_ASSOC);

    if (!$header) {
        http_response_code(404);
        echo json_encode(['error' => 'Purchase order not found']);
        exit;
    }

    // Fetch items (detail lines)
    $sqlI = "SELECT item_no, qty, cost, discount
             FROM purchase
             WHERE po_no = ?
             ORDER BY pur_no";
    $stI = $pdo->prepare($sqlI);
    $stI->execute([$po_no]);
    $items = $stI->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'header' => $header,
        'items'  => $items
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?>