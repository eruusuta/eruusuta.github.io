<?php
session_start();

//this will ensure if user login as admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['is_admin'] !== 1) {
    header("Location: login_page.html"); 
    exit();
}

include 'db.php';

if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    //retrieve the student data to edit
    $sql = "SELECT * FROM students WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $student_id);
    $stmt->execute();

    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        echo "Student not found!";
        exit();
    }
} else {
    echo "Invalid request!";
    exit();
}

//handle form submission for updating the student details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $lrn = htmlspecialchars(trim($_POST['lrn']));
    $emergency_phone = htmlspecialchars(trim($_POST['emergency_phone']));
    $address = htmlspecialchars(trim($_POST['address']));
    $gender = htmlspecialchars(trim($_POST['gender']));
    $age = htmlspecialchars(trim($_POST['age']));

    $sql = "UPDATE students SET name = :name, email = :email, lrn = :lrn, emergency_phone = :emergency_phone, 
            address = :address, gender = :gender, age = :age WHERE id = :id";
    $stmt = $conn->prepare($sql);

    //bind values to the placeholder
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':lrn', $lrn);
    $stmt->bindParam(':emergency_phone', $emergency_phone);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':age', $age);
    $stmt->bindParam(':id', $student_id);

    if ($stmt->execute()) {
        header("Location: student_list.php");
        exit();
    } else {
        echo "Error updating student.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="edit.css">
    <style>
         .update {
            height: 30px;
            width: auto;
            border-radius: 5px;
            background-color: skyblue;
         }

         .update:hover {
            background-color: grey;
         }
        </style>
</head>
<body>
<a href="student_list.php" class="back-button">Back</a>
    <h1>Edit Student</h1>
    <div class="container">
        <form action="edit_student.php?id=<?php echo $student['id']; ?>" method="POST">
            <input type="text" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" class="form-control" required><br>
            <input type="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" class="form-control" required><br>
            <input type="text" name="lrn" value="<?php echo htmlspecialchars($student['lrn']); ?>" class="form-control" required><br>
            <input type="text" name="emergency_phone" value="<?php echo htmlspecialchars($student['emergency_phone']); ?>" class="form-control" required><br>
            <input type="text" name="address" value="<?php echo htmlspecialchars($student['address']); ?>" class="form-control" required><br>
            <input type="text" name="gender" value="<?php echo htmlspecialchars($student['gender']); ?>" class="form-control" required><br>
            <input type="number" name="age" value="<?php echo htmlspecialchars($student['age']); ?>" class="form-control" required><br>
            <strong><button type="submit" class="update">Update Student</button></strong>
        </form>
    </div>
</body>
</html>
