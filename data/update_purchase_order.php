<?php
require 'db.php'; // adjust path to your PDO connection

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

$update_id  = $_POST['update_id'] ?? null;
$supp_no    = $_POST['supp_no'] ?? null;

// 🔹 FIX: match your form field name (branch_num not br_no)
$br_no      = $_POST['br_no'] ?? null;

$order_date = $_POST['order_date'] ?? null;

// 🔹 Convert incoming date (MM/DD/YYYY → YYYY-MM-DD for MySQL)


$item_nos   = $_POST['item_no'] ?? [];
$costs      = $_POST['cost'] ?? [];
$qtys       = $_POST['qty'] ?? [];
$discounts  = $_POST['discount'] ?? [];

if (!$update_id) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Missing update_id']);
    exit;
}

try {
    $pdo->beginTransaction();

    // 1) Update purchase_order (header)
    $stmt = $pdo->prepare(
        "UPDATE purchase_order
         SET supp_no = ?, br_no = ?, order_date = ?
         WHERE po_no = ?"
    );
    $stmt->execute([$supp_no, $br_no, $order_date, $update_id]);

    // 2) Delete existing purchase rows for this order
    $stmt = $pdo->prepare("DELETE FROM purchase WHERE pur_no = ?");
    $stmt->execute([$update_id]);

    // 3) Re-insert items
    $ins = $pdo->prepare(
        "INSERT INTO purchase (po_no, qty, cost, discount, item_no)
         VALUES (?, ?, ?, ?, ?)"
    );

    foreach ($item_nos as $i => $item_no) {
        if ($item_no === '' || $item_no === null) {
            continue; // skip empty rows
        }

        $qty  = isset($qtys[$i]) ? (int)$qtys[$i] : null;
        $cost = isset($costs[$i]) ? $costs[$i] : null;
        $disc = isset($discounts[$i]) && $discounts[$i] !== '' ? $discounts[$i] : 0;

        $ins->execute([
            $update_id,
            $qty,
            $cost,
            $disc,
            $item_no
        ]);
    }

    $pdo->commit();
    echo json_encode(['status' => 'success', 'po_no' => $update_id]);

} catch (Throwable $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
