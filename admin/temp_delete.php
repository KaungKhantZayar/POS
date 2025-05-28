<?php
include 'Config/config.php';
$stmt = $pdo->prepare("DELETE FROM temp WHERE id=".$_GET['id']);
$stmt->execute();
header('Location: temp.php');
 ?>
