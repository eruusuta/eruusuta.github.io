<?php
session_start();
include 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo "<script>alert('Please fill in both fields.'); window.location.href = 'login_page.html';</script>";
        exit();
    }

    $sql = "SELECT id, password, role FROM users WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    //this will check if the user or email exist
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $user['password'])) {
            //set session variabless
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['is_admin'] = ($user['role'] === 'admin') ? 1 : 0;

            //redirect based on user role
            if ($_SESSION['is_admin'] == 1) {
                //redirect to admin page
                header("Location: student_list.php");
                exit();
            } else {
                //redirect the students
                header("Location: main_students.html");
                exit();
            }
        } else {
            echo "<script>alert('Invalid password.'); window.location.href = 'login_page.html';</script>";
        }
    } else {
        echo "<script>alert('No user found with that email.'); window.location.href = 'login_page.html';</script>";
    }
}    
?>
