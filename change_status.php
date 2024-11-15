<?php
session_start();
include 'db.php';

// Ensure user is logged in and is admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['is_admin'] !== 1) {
    header("Location: login_page.php");
    exit();
}

if (isset($_GET['id'], $_GET['current_status'])) {
    $student_id = $_GET['id'];
    $current_status = $_GET['current_status'];

    // Toggle status
    $new_status = ($current_status == 'active') ? 'deactivated' : 'active';

    try {
        $sql = "UPDATE students SET status = :status WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':status' => $new_status, ':id' => $student_id]);
        header("Location: student_list.php"); // Redirect back to student list after status update
    } catch (PDOException $e) {
        echo "Error changing status: " . $e->getMessage();
    }
} else {
    header("Location: student_list.php"); // Redirect if no ID or status found
    exit();
}
?>