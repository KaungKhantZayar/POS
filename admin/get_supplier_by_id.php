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

if (isset($_GET['supplier_id'])) {
    $supplier_id = $_GET['supplier_id'];
    $stmt = $conn->prepare("SELECT supplier_name FROM supplier WHERE supplier_id = ?");
    $stmt->bind_param("s", $supplier_id);
    $stmt->execute();
    $stmt->bind_result($supplier_name);
    if ($stmt->fetch()) {
        echo json_encode(["success" => true, "supplier_name" => $supplier_name]);
    } else {
        echo json_encode(["success" => false]);
    }
    $stmt->close();
}

$conn->close();
?>
