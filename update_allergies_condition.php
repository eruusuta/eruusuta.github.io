<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$allergies = isset($_POST['allergies']) ? implode(", ", $_POST['allergies']) : '';
$conditions = isset($_POST['conditions']) ? implode(", ", $_POST['conditions']) : '';
$other_allergy = isset($_POST['other_allergy']) ? $_POST['other_allergy'] : '';
$other_condition = isset($_POST['other_condition']) ? $_POST['other_condition'] : '';

// Append "Other" fields if they are provided
if (!empty($other_allergy)) {
    $allergies .= ", " . $other_allergy;
}
if (!empty($other_condition)) {
    $conditions .= ", " . $other_condition;
}

// Update allergies and conditions in the database
$query = "UPDATE students SET allergies = :allergies, conditions = :conditions WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':allergies', $allergies);
$stmt->bindParam(':conditions', $conditions);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

if ($stmt->execute()) {
    header("Location: dashboard.php?status=success");
    exit();
} else {
    echo "Error updating allergies and conditions.";
}
?>
