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

if (isset($_GET['supplier_name'])) {
    $supplier_name = $_GET['supplier_name'];
    $stmt = $conn->prepare("SELECT supplier_id FROM supplier WHERE supplier_name = ?");
    $stmt->bind_param("s", $supplier_name);
    $stmt->execute();
    $stmt->bind_result($supplier_id);
    if ($stmt->fetch()) {
        echo json_encode(["success" => true, "supplier_id" => $supplier_id]);
    } else {
        echo json_encode(["success" => false]);
    }
    $stmt->close();
}

$conn->close();
?>
