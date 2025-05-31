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

if (isset($_GET['sale_name'])) {
    $sale_name = $_GET['sale_name'];
    $stmt = $conn->prepare("SELECT sale_id FROM customer WHERE sale_name = ?");
    $stmt->bind_param("s", $sale_name);
    $stmt->execute();
    $stmt->bind_result($sale_id);
    if ($stmt->fetch()) {
        echo json_encode(["success" => true, "sale_id" => $sale_id]);
    } else {
        echo json_encode(["success" => false]);
    }
    $stmt->close();
}

$conn->close();
?>
