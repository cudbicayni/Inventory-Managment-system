<?php
require 'db.php';
header('Content-Type: application/json');

try {
  $po_no = intval($_POST['po_no']);
  $supp_no = intval($_POST['supp_no']);
  $br_no = intval($_POST['branch_num']);
  $order_date = $_POST['order_date'];

  $item_nos = $_POST['item_no'];
  $costs = $_POST['cost'];
  $qtys = $_POST['qty'];
  $discounts = $_POST['discount'];

  // Start transaction
  $pdo->beginTransaction();

  // Update header
  $pdo->prepare("UPDATE purchase_order SET supp_no=?, br_no=?, order_date=? WHERE po_no=?")
      ->execute([$supp_no, $br_no, $order_date, $po_no]);

  // Delete old items
  $pdo->prepare("DELETE FROM purchase WHERE po_no=?")->execute([$po_no]);

  // Insert updated items
  $stmt = $pdo->prepare("INSERT INTO purchase (po_no, qty, cost, discount, item_no) VALUES (?, ?, ?, ?, ?)");
  for ($i = 0; $i < count($item_nos); $i++) {
    $stmt->execute([$po_no, intval($qtys[$i]), floatval($costs[$i]), floatval($discounts[$i]), intval($item_nos[$i])]);
  }

  $pdo->commit();

  echo json_encode(['status' => 'success']);

} catch (Exception $e) {
  $pdo->rollBack();
  echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
