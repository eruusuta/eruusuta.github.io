<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['is_admin'] !== 1) {
    header("Location: login_page.php");
    exit();
}

include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $sql = "UPDATE students SET status = 'deactivated' WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        header("Location: student_list.php");
        exit();
    } catch (PDOException $e) {
        echo "Error deactivating student: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
