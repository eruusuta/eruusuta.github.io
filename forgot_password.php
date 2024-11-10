<?php
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(16)); 
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour')); 
    
        $updateQuery = "UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param('sss', $token, $expiry, $email);
        $updateStmt->execute();

        $resetLink = "http://yourdomain.com/reset_password.php?token=$token";
        emailjs.send("service_hr3leqd", "template_ietxcck", {
            to_email: $email,
            reset_link: $resetLink
        }).then(response => {
            alert('Password reset link sent!');
        }).catch(error => {
            alert('Error sending email.');
        });

        echo "Password reset link has been sent to your email.";
    } else {
        echo "Email not found.";
    }
}
?>
