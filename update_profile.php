<?php
session_start();
require_once 'db.php';  // Make sure this file contains the correct DB connection setup

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the user ID from the session
    $user_id = $_SESSION['user_id'];

    // Get form values
    $name = $_POST['name'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];

    // Check if a new profile photo is uploaded
    if ($_FILES['photo']['name']) {
        $photo = $_FILES['photo'];
        $photo_name = time() . "_" . basename($photo['name']);
        $photo_path = 'uploads/' . $photo_name;

        // Move the uploaded photo to the "uploads" directory
        if (!move_uploaded_file($photo['tmp_name'], $photo_path)) {
            echo "Error uploading photo.";
            exit;
        }
    } else {
        // If no new photo, keep the current photo (optional)
        $photo_name = $_POST['current_photo'];
    }

    // Debugging: Output the received form data
    echo '<pre>';
    print_r($_POST);
    echo '</pre>';

    // Prepare and execute the query to update the users table
    $sql = "UPDATE users SET name = :name, email = :email, age = :age, address = :address, phone_number = :phone_number WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->bindValue(':age', $age, PDO::PARAM_INT);
    $stmt->bindValue(':address', $address, PDO::PARAM_STR);
    $stmt->bindValue(':phone_number', $phone_number, PDO::PARAM_STR);
    $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo 'User data updated successfully.<br>';

        // Update the students table email
        $sql = "UPDATE students SET email = :email WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo 'Student email updated successfully.<br>';

            // Redirect back to the edit profile page with a success message
            header('Location: edit_profile.php?update=success');
            exit;
        } else {
            echo 'Error updating student email: ' . $stmt->errorInfo()[2];
        }
    } else {
        echo 'Error updating user data: ' . $stmt->errorInfo()[2];
    }
}
?>
