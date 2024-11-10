<?php
session_start();
include 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM inventory WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}
?>