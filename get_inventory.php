<?php
session_start();
include 'db.php';

//check if we are fetching a single item by ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    //fetch the item details by id
    $stmt = $conn->prepare("SELECT * FROM inventory WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    //return item as json
    echo json_encode($item);
} else {
    // if theres no id is provided, retrueve all inventory items
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $sql = "SELECT * FROM inventory WHERE item_name LIKE :search";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['search' => '%' . $search . '%']);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //return all item as json- javascript object notation
    echo json_encode($items);
}
?>
