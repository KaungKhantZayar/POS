<?php
include 'Config/config.php';
$stmt = $pdo->prepare("DELETE FROM customer WHERE id=".$_GET['id']);
$stmt->execute();
header('Location: customer.php');
 ?>
