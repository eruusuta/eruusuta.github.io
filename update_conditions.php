<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$conditions = isset($_POST['conditions']) ? implode(", ", $_POST['conditions']) : '';
$other_condition = isset($_POST['other_condition']) ? $_POST['other_condition'] : '';

if (!empty($other_condition)) {
    $conditions .= ", " . $other_condition;
}

$query = "UPDATE students SET conditions = :conditions WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':conditions', $conditions);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

if ($stmt->execute()) {
    header("Location: dashboard.php?status=success");
    exit();
} else {
    echo "Error updating conditions.";
}
?>
