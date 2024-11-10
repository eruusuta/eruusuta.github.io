<?php
//database connection
$servername = "localhost";
$username = "root";
$password = "Iamwelster64";  
$dbname = "health_card_system";  

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
