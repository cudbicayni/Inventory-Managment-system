<?php
require 'db.php';
header('Content-Type: application/json');

ini_set('display_errors', 1);
error_reporting(E_ALL);

$transactionStarted = false; // ✅ Flag to track transaction state

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['multi_order'])) {
    try {
        // ✅ Validate required fields early
        if (
            !isset($_POST['per_no'], $_POST['branch_num'], $_POST['order_date'],
                    $_POST['item_no'], $_POST['cost'], $_POST['qty'], $_POST['discount'])
        ) {
            throw new Exception('Missing required fields.');
        }

        $per_no    = intval($_POST['per_no']);
        $branch_num = intval($_POST['branch_num']);
        $order_date = $_POST['order_date'];

        $item_nos  = $_POST['item_no'];
        $costs     = $_POST['cost'];
        $qtys      = $_POST['qty'];
        $discounts = $_POST['discount'];

        // ✅ Start transaction
        $pdo->beginTransaction();
        $transactionStarted = true;

        // ✅ Insert into main order table
        $stmt = $pdo->prepare("INSERT INTO purchase_order (per_no, br_no, order_date) VALUES (?, ?, ?)");
        $stmt->execute([$per_no, $branch_num, $order_date]);
        $po_no = $pdo->lastInsertId();

        // ✅ Insert line items
        $stmtItem = $pdo->prepare("INSERT INTO purchase (po_no, qty, cost, discount, item_no) VALUES (?, ?, ?, ?, ?)");

        for ($i = 0; $i < count($item_nos); $i++) {
            if (!isset($item_nos[$i], $costs[$i], $qtys[$i], $discounts[$i])) continue;

            $stmtItem->execute([
                $po_no,
                intval($qtys[$i]),
                floatval($costs[$i]),
                floatval($discounts[$i]),
                intval($item_nos[$i])
            ]);
        }

        $pdo->commit();

        echo json_encode([
            'status' => 'success',
            'po_no' => $po_no
        ]);

    } catch (Exception $e) {
        if ($transactionStarted) {
            $pdo->rollBack(); // ✅ Only rollback if started
        }

        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
}
