<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT name, age, allergies, conditions FROM users WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Generate the report (for simplicity, let's just show the data in the report)
    $report = "
        Health Report for " . $user['name'] . "\n
        Age: " . $user['age'] . "\n
        Allergies: " . $user['allergies'] . "\n
        Medical Conditions: " . $user['conditions'] . "\n
    ";
    
    echo "<pre>" . htmlspecialchars($report) . "</pre>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Health Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Generate Your Health Report</h1>
        <form method="POST" action="generate_report.php">
            <button type="submit" class="btn btn-success">Generate Report</button>
        </form>
    </div>
</body>
</html>
