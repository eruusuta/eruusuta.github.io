<?php
$servername = "localhost";
$username = "root";
$password = "Iamwelster64"; 
$dbname = "health_card_system";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $phone = htmlspecialchars(trim($_POST["phone"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    if (empty($name) || empty($email) || empty($message)) {
        echo "Name, Email, and Message are required fields.";
        exit;
    }
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, phone, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $phone, $message);

    if ($stmt->execute()) {
        echo "<script>
                        alert('Message Sent.');
                        window.location.href = 'contact.html';
                      </script>";
        
        $to = "eruusutatsuda@gmail.com";
        $subject = "New Contact Message from Health Card System";
        $emailContent = "Name: $name\nEmail: $email\nPhone: $phone\nMessage: $message";
        mail($to, $subject, $emailContent);

    } else {
        echo "Error sending message: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
