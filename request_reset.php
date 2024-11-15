<?php
require 'db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate a token and expiration time
        $token = bin2hex(random_bytes(50));
        $expires = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // Update the database with the token and expiry time
        $stmt = $conn->prepare("UPDATE users SET reset_token = :token, token_expiry = :expiry WHERE email = :email");
        $stmt->execute(['token' => $token, 'expiry' => $expires, 'email' => $email]);

        // Send the reset email
        $mail = new PHPMailer(true);
        $resetLink = "https://health-card.lovestoblog.com/reset_password.php?token=$token"; // Replace with your actual domain

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'jhonewelster11@gmail.com'; // SMTP User
            $mail->Password = 'jukh hqgk mihf vmwh '; // SMTP Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use TLS if supported
            $mail->Port = 587; // Port for smtpserver.com

            // Email settings
            $mail->setFrom('info@alcasianlearning.com', 'Asian Learning Center'); // Main From email
            $mail->addAddress($email); // User's email
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "Click the link below to reset your password:<br><a href='$resetLink'>$resetLink</a>";

            $mail->send();
            echo "<script>
                        alert('Please check your email.');
                        window.location.href = 'request_reset.php';
                      </script>";
        } catch (Exception $e) {
            echo "<script>
                       alert('reset link not sent. Mailer Error: {$mail->ErrorInfo}');
                       window.location.href = 'request_reset.php';
                       </script>";
        }
    } else {
        echo "<script>
                   alert('Email not found.');
                   window.location.href = 'request_reset.php';
                   </script>";
    }
}
?>
<!--reset password-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>forgot password</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="edit.css"
</head>
<body>
<div class="container">
<form method="POST" action="">
    <input type="email" id="email" name="email" placeholder="Enter Your Email" class="form-control" required>
    <button type="submit" class="button2">Send Reset Link</button>
    <center><p><a href="login_page.php" style="text-decoration: none; color: white; text-size: 15px;">Login</a></p></center>
</form>
</div>
</body>
</html>
