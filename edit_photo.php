<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "Iamwelster64";
$dbname = "health_card_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//check if the users is login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

//get the user id from session
$user_id = $_SESSION['user_id'];

//retrieve user details from the database
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

//check if the students exists
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

//handle the image upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['photo'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $uploadOk = 1;

    // check if the file is an image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    //limit file sized 10mb
    if ($_FILES["photo"]["size"] > 10000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    //file format
    if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif" && $imageFileType != "webp") {
        echo "Sorry, only JPG, JPEG, PNG, GIF & WEBP files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            //this will update the photo of database
            $sql = "UPDATE users SET photo = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", basename($_FILES["photo"]["name"]), $user_id);
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Profile photo updated successfully!";
                header("Location: edit_profile.php");
                exit();
            } else {
                echo "Error updating photo in database.";
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile Photo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="edit.css">
    <link rel="stylesheet" href="main.css">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #121212;
            padding-top: 30px;
            color: white;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #1c1c1c;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #fff;
            text-align: center;
        }

        .form-control {
            background-color: #333;
            border: 1px solid #444;
            border-radius: 5px;
            padding: 6px 10px;
            margin-bottom: 12px;
            color: white;
        }

        .form-control:focus {
            border-color: #1877f2;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .btn-primary {
            background-color: #1877f2;
            border-color: #1877f2;
            padding: 6px 10px;
            font-size: 1rem;
            border-radius: 5px;
        }

        .btn-primary:hover {
            background-color: #165e9d;
            border-color: #165e9d;
        }
    </style>
</head>
<body>
    <a href="edit_profile.php" class="back-button">Back</a>
    <div class="container">

        <!--display current prfile-->
        <div class="mb-3 text-center">
            <img src="uploads/<?php echo htmlspecialchars($user['photo']); ?>" alt="Profile Photo" class="profile-photo">
        </div>

        <!--photo upload form-->
        <form action="edit_photo.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="photo">Choose a new profile photo</label>
                <input type="file" name="photo" id="photo" class="form-control" required>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">Upload Photo</button>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>