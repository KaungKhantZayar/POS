<?php
header("Content-Type: application/json");
require '../Config/config.php';

if (isset($_GET['item_name'])) {
    $item_name = $_GET['item_name'];

    $stmt = $pdo->prepare("SELECT item_id,original_price,selling_price FROM item WHERE item_name = :name");
    $stmt->execute([':name' => $item_name]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $item_id = $row ? $row['item_id'] : null;

    $stockstmt = $pdo->prepare("SELECT balance FROM stock WHERE item_id = :id ORDER BY id DESC");
    $stockstmt->execute([':id' => $item_id]);
    $stockrow = $stockstmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        echo json_encode(["success" => true, "item_id" => $row['item_id'], "original_price" => $row['original_price'], "selling_price" => $row['selling_price'], "stock_balance" => $stockrow ? $stockrow['balance'] : 0]);
    } else {
        echo json_encode(["success" => false]);
    }
}
