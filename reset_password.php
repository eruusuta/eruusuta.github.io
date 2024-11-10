<?php
include('db.php');

// Check if token is passed via URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Check if the token exists in the database and is not expired
    $query = "SELECT * FROM users WHERE reset_token = :token AND reset_token_expiry > NOW()";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Token is valid, allow password reset
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $newPassword = $_POST['password'];
            $confirmPassword = $_POST['confirm-password'];

            // Check if passwords match
            if ($newPassword == $confirmPassword) {
                // Hash the new password
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                
                // Update the user's password and clear the reset token
                $updateQuery = "UPDATE users SET password = :password, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = :token";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
                $updateStmt->bindParam(':token', $token, PDO::PARAM_STR);
                $updateStmt->execute();

                // Send confirmation email via EmailJS
                $user = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch user data
                $userEmail = $user['email']; // User's email
                echo "Password has been reset successfully!";

                // Send email notification using EmailJS
                echo "
                <script type='text/javascript'>
                    emailjs.send('service_6m3e5yp', 'template_0vic8sk', {
                        to_email: '$userEmail',
                        subject: 'Password Reset Confirmation',
                        message: 'Your password has been successfully reset.'
                    })
                    .then(function(response) {
                        console.log('Email sent successfully:', response);
                    }, function(error) {
                        console.log('Email send failed:', error);
                    });
                </script>";
            } else {
                echo "Passwords do not match.";
            }
        }
    } else {
        echo "Invalid or expired reset link.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- EmailJS Script -->
    <script type="text/javascript" src="https://cdn.emailjs.com/dist/email.min.js"></script>
    <script type="text/javascript">
        emailjs.init("AKjVozw_GUcVzpOcI");  
    </script>
</head>
<body>

<!-- Password Reset Form -->
<form action="reset_password.php?token=<?php echo $token; ?>" method="POST">
    <input type="password" name="password" placeholder="New Password" required class="form-control2">
    <input type="password" name="confirm-password" placeholder="Confirm New Password" required class="form-control2">
    <button type="submit" class="button-login1">Reset Password</button>
</form>

</body>
</html>
