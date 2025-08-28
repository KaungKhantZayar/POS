<?php
header("Content-Type: application/json");
require '../Config/config.php';

if (isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];

    $stmt = $pdo->prepare("SELECT item_name,original_price,selling_price FROM item WHERE item_id = :id");
    $stmt->execute([':id' => $item_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $stockstmt = $pdo->prepare("SELECT balance FROM stock WHERE item_id = :id ORDER BY id DESC");
    $stockstmt->execute([':id' => $item_id]);
    $stockrow = $stockstmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        echo json_encode(["success" => true, "item_name" => $row['item_name'], "original_price" => $row['original_price'], "selling_price" => $row['selling_price'], "stock_balance" => $stockrow ? $stockrow['balance'] : 0]);
    } else {
        echo json_encode(["success" => false]);
    }
}
