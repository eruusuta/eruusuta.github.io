<?php
session_start();
include 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo "<script>alert('Please fill in both fields.'); window.location.href = 'login_page.php';</script>";
        exit();
    }

    // SQL query to fetch user details and status
    $sql = "SELECT id, password, role, status FROM users WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Check if the user exists
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Debugging: Check if the status is fetched correctly
        echo "User status: " . $user['status']; // Debugging line
        if ($user['status'] == 'inactive') {
            echo "<script>alert('Your account has been deactivated. Please contact the administrator.'); window.location.href = 'login_page.php';</script>";
            exit();
        }        

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables if login is successful
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['is_admin'] = ($user['role'] === 'admin') ? 1 : 0;

            // Redirect based on role
            if ($_SESSION['is_admin'] == 1) {
                header("Location: student_list.php");
                exit();
            } else {
                header("Location: main_students.html");
                exit();
            }
        } else {
            echo "<script>alert('Invalid password.'); window.location.href = 'login_page.php';</script>";
        }
    } else {
        echo "<script>alert('No user found with that email.'); window.location.href = 'login_page.php';</script>";
    }
}
?>
