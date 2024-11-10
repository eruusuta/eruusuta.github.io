<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = htmlspecialchars(trim($_POST['item_name']));
    $quantity = htmlspecialchars(trim($_POST['quantity']));
    $location = htmlspecialchars(trim($_POST['location']));

    $stmt = $conn->prepare("INSERT INTO inventory (item_name, quantity, location) VALUES (:item_name, :quantity, :location)");
    $stmt->bindParam(':item_name', $item_name);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':location', $location);
    
    if ($stmt->execute()) {
        $_SESSION['item_added'] = "Item added successfully!";
    } else {
        $_SESSION['item_added'] = "Failed to add item.";
    }
    
    header("Location: inventory_manage.php");
    exit();
}
?>