<?php
$servername = "localhost";
$username = "root";
$password = "Iamwelster64";
$dbname = "health_card_system";
$alertMessage = "";

// Connect to the database using PDO - PHP Data Objects
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $phone_number = $_POST['emergency_number'];
    $address = $_POST['address'];
    $lrn = $_POST['lrn'];  // Ensure this is passed from the form
    $gender = $_POST['gender'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Handle the photo when uploading
    $photo = $_FILES['photo']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($photo);
    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
        $alertMessage = "Sorry, there was an error uploading your file.";
    }

    // Check if email or name already exists
    $checkSql = "SELECT * FROM users WHERE email = :email OR name = :name";
    $stmt = $conn->prepare($checkSql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':name', $name);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $alertMessage = "The email or name is already registered. Please use different details.";
        echo "<script>
                alert('$alertMessage');
                window.history.back();
              </script>";
        exit();
    } else {
        // Insert into users table
        $sql = "INSERT INTO users (name, lrn, age, phone_number, address, email, photo, gender, password)
                VALUES (:name, :lrn, :age, :phone_number, :address, :email, :photo, :gender, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':lrn', $lrn);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':photo', $target_file);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':password', $password);

        if ($stmt->execute()) {
            $user_id = $conn->lastInsertId();

            // Insert into students table
            $sql_student = "INSERT INTO students (user_id, name, email, lrn, age, gender, emergency_phone, address, photo)
                            VALUES (:user_id, :name, :email, :lrn, :age, :gender, :phone_number, :address, :photo)";
            $stmt_student = $conn->prepare($sql_student);
            $stmt_student->bindParam(':user_id', $user_id);
            $stmt_student->bindParam(':name', $name);
            $stmt_student->bindParam(':email', $email);
            $stmt_student->bindParam(':lrn', $lrn);  
            $stmt_student->bindParam(':age', $age);
            $stmt_student->bindParam(':gender', $gender);
            $stmt_student->bindParam(':phone_number', $phone_number);
            $stmt_student->bindParam(':address', $address);
            $stmt_student->bindParam(':photo', $target_file);
            
            if ($stmt_student->execute()) {
                echo "<script>
                        alert('Registration successful!');
                        window.location.href = 'student_list.php';
                      </script>";
                exit();
            } else {
                $alertMessage = "Error inserting into students table: " . implode(' ', $stmt_student->errorInfo());
            }
        } else {
            $alertMessage = "Error inserting into users table: " . implode(' ', $stmt->errorInfo());
        }
    }
}
?>
