<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$allergies = isset($_POST['allergies']) ? implode(", ", $_POST['allergies']) : '';
$other_allergy = isset($_POST['other_allergy']) ? $_POST['other_allergy'] : '';

if (!empty($other_allergy)) {
    $allergies .= ", " . $other_allergy;
}

$query = "UPDATE students SET allergies = :allergies WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':allergies', $allergies);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

if ($stmt->execute()) {
    header("Location: dashboard.php?status=success");
    exit();
} else {
    echo "Error updating allergies.";
}
?>
