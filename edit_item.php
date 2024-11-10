<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $item_name = htmlspecialchars(trim($_POST['item_name']));
    $quantity = htmlspecialchars(trim($_POST['quantity']));
    $location = htmlspecialchars(trim($_POST['location']));

    $stmt = $conn->prepare("UPDATE inventory SET item_name = :item_name, quantity = :quantity, location = :location WHERE id = :id");
    $stmt->bindParam(':item_name', $item_name);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':location', $location);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        $_SESSION['item_updated'] = "Item updated successfully!";
    } else {
        $_SESSION['item_updated'] = "Failed to update item.";
    }
    
    header("Location: inventory_manage.php");
    exit();
}
?>
