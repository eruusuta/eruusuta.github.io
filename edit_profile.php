<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "Iamwelster64";
$dbname = "health_card_system";

// Make connection
$conn = new mysqli($servername, $username, $password, $dbname);

// This will check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// This will check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user id from session
$user_id = $_SESSION['user_id'];

// Fetch user details from the database
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="edit.css">
    <style>
    body {
        font-family: 'Montserrat', sans-serif;
        background-color: #121212;
        color: #e0e0e0;
    }

    .container {
        background-color: #1e1e1e;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        max-width: 500px;
        margin: auto;
    }

    .profile-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-photo {
            border-radius: 50%;
            border: 4px solid #fff;
            width: 200px; 
            height: 200px; 
            object-fit: cover;
            margin-top: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }


    h1 {
        font-size: 1.75rem;
        color: #f1c40f;
        text-align: center;
    }

    .form-control {
        background-color: #333;
        border: 1px solid #444;
        color: #f1c40f;
        border-radius: 8px;
    }

    .btn-primary {
        background-color: #f1c40f;
        color: #121212;
        border-radius: 8px;
        padding: 10px 20px;
        border: none;
    }
</style>
</head>
<body>
    <a href="main_students.html" class="back-button">Back</a>
    <div class="container">

        <div class="profile-header">
            <!--this will retrieve the image path and displaying it -->
            <img src="uploads/<?php echo htmlspecialchars($user['photo']); ?>" alt="Profile Photo" class="profile-photo">
            <a href="edit_photo.php" class="edit-button">Edit</a>
        </div>

        <form action="update_profile.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($user['name']); ?>" class="form-control" required>

            <label for="lrn" class="form-label">LRN</label>
            <input type="text" name="lrn" id="lrn" value="<?php echo htmlspecialchars($user['lrn']); ?>" class="form-control" required>

            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="form-control" required>

            <label for="age" class="form-label">Age</label>
            <input type="number" name="age" id="age" value="<?php echo htmlspecialchars($user['age']); ?>" class="form-control" required>

            <label for="phone_number" class="form-label">Phone Number</label>
            <input type="text" name="phone_number" id="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" class="form-control" required>

            <label for="address" class="form-label">Address</label>
            <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($user['address']); ?>" class="form-control" required>

            <button type="submit" class="btn btn-primary">Update Information</button>
        </form>
    </div>
</body>
</html>
