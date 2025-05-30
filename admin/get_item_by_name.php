<?php
header("Content-Type: application/json");
$servername = "localhost";
$username = "root";
$password = "";
$database = "shooping";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed"]));
}

if (isset($_GET['item_name'])) {
    $item_name = $_GET['item_name'];
    $stmt = $conn->prepare("SELECT item_id FROM item WHERE item_name = ?");
    $stmt->bind_param("s", $item_name);
    $stmt->execute();
    $stmt->bind_result($item_id);
    if ($stmt->fetch()) {
        echo json_encode(["success" => true, "item_id" => $item_id]);
    } else {
        echo json_encode(["success" => false]);
    }
    $stmt->close();
}

$conn->close();
?>
