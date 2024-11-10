<?php
session_start();

//this will check if the user loggedin as admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['is_admin'] !== 1) {
    header("Location: login_page.html"); //it will automatically back if the user is not admin
    exit();
}

include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Begin a transaction to ensure both deletions are handled together
        $conn->beginTransaction();

        // Prepare and execute the delete query for the student
        $sql = "DELETE FROM students WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $sql_user = "DELETE FROM users WHERE id = :id"; //use id to delete user
        $stmt_user = $conn->prepare($sql_user);
        $stmt_user->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_user->execute();

        // Commit the transaction
        $conn->commit();

        //after delete the students it will redirect to student list
        header("Location: student_list.php");
        exit();
    } catch (PDOException $e) {
        //if error it will automatic back
        $conn->rollBack();
        echo "Error deleting student and user: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
