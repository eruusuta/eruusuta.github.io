<?php
include('db.php'); // Ensure this is using PDO to connect to the database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Use PDO to prepare and execute the query
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $token = bin2hex(random_bytes(16)); 
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour')); 
    
        // Update the database with the reset token and expiry time
        $updateQuery = "UPDATE users SET reset_token = :token, reset_token_expiry = :expiry WHERE email = :email";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bindParam(':token', $token, PDO::PARAM_STR);
        $updateStmt->bindParam(':expiry', $expiry, PDO::PARAM_STR);
        $updateStmt->bindParam(':email', $email, PDO::PARAM_STR);
        $updateStmt->execute();

        // Construct the reset link
        $resetLink = "https://eruusuta.github.io/reset_password.php?token=$token";

        // Now echo a script to trigger EmailJS email sending.
        echo "<script type='text/javascript'>
                emailjs.send('service_6m3e5yp', 'template_0vic8sk', {
                    to_email: '$email',
                    reset_link: '$resetLink'
                }).then(response => {
                    alert('Password reset link sent!');
                }).catch(error => {
                    alert('Error sending email: ' + error);
                });
              </script>";
        
        echo "Password reset link has been sent to your email.";
    } else {
        echo "Email not found.";
    }
}
?>

<form action="forgot_password.php" method="POST">
    <input type="email" name="email" placeholder="Enter your email" required class="form-control2">
    <button type="submit" class="button-login1">Reset Password</button>
</form>
