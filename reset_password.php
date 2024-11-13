<?php
require 'db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Find the user by token
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = :token AND token_expiry > NOW()");
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch();

    if ($user) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

            // Update the password and clear the token
            $stmt = $conn->prepare("UPDATE users SET password = :password, reset_token = NULL, token_expiry = NULL WHERE id = :id");
            $stmt->execute(['password' => $new_password, 'id' => $user['id']]);

            echo "<script> alert('Please check your email');
                     </script>";
            exit;
        }
    } else {
        echo "<script>
                        alert('Invalid or expire token!');
                      </script>";
        exit;
    }
} else {
    echo "<script>
                        alert('No token provided!');
                      </script>";
    exit;
}
?>

<!--resetting password-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reset password</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="edit.css">
</head>
<body>
 <div class="container">
<form method="POST" action="">
    <input type="password" id="new_password" name="new_password" placeholder="Enter new password" class="form-control" required>
    <button type="submit" class="button2">Reset Password</button>
</form>
</container>
</body>
</html>