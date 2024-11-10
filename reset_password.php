<?php
include('db_connect.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    //check if the token exists in the database and is not expired
    $query = "SELECT * FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        //token is valid allow students to reset pass
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $newPassword = $_POST['password'];
            $confirmPassword = $_POST['confirm-password'];

            if ($newPassword == $confirmPassword) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateQuery = "UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param('ss', $hashedPassword, $token);
                $updateStmt->execute();
                
                echo "Password has been reset successfully!";
            } else {
                echo "Passwords do not match.";
            }
        }
    } else {
        echo "Invalid or expired reset link.";
    }
}
?>
<form action="reset_password.php?token=<?php echo $token; ?>" method="POST">
    <input type="password" name="password" placeholder="New Password" required class="form-control2">
    <input type="password" name="confirm-password" placeholder="Confirm New Password" required class="form-control2">
    <button type="submit" class="button-login1">Reset Password</button>
</form>
