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

if (isset($_GET['sale_id'])) {
    $sale_id = $_GET['sale_id'];
    $stmt = $conn->prepare("SELECT sale_name FROM customer WHERE sale_id = ?");
    $stmt->bind_param("s", $sale_id);
    $stmt->execute();
    $stmt->bind_result($sale_name);
    if ($stmt->fetch()) {
        echo json_encode(["success" => true, "sale_name" => $sale_name]);
    } else {
        echo json_encode(["success" => false]);
    }
    $stmt->close();
}

$conn->close();
?>
