<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    $admin_username = "eruusuta"; //username for admin
    $admin_password_hash = password_hash("Iamwelster64", PASSWORD_BCRYPT); //password for admin

    if ($username === $admin_username && password_verify($password, $admin_password_hash)) {
        $_SESSION['loggedin'] = true;  //set the login session
        $_SESSION['is_admin'] = 1;    
        header("Location: inventory_management.html");  //after login it redirect to inventory for admin
        exit();
    } else {
        echo "<script>alert('Invalid username or password.'); window.history.back();</script>";
    }
}
?>
