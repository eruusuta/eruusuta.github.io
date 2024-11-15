<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['is_admin'] !== 1) {
    header("Location: login_page.php");
    exit();
}

include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    //confirmation message if the admin want to delete students
    echo '<script>
        var confirmDelete = confirm("Are you sure you want to delete this student?");
        if (confirmDelete) {
            // If confirmed, proceed with the deletion
            window.location.href = "delete_student_action.php?id=' . $id . '";
        } else {
            // If canceled, redirect back to the student list page
            window.location.href = "student_list.php";
        }
    </script>';
    exit();
} else {
    echo "Invalid request.";
}
?>