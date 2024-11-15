<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$query = "SELECT name, age, allergies, conditions FROM students WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $name = $user['name'];
    $age = $user['age'];
    $allergies = $user['allergies'];
    $conditions = $user['conditions'];
} else {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Health Card System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="main.css"> 
    <link rel="stylesheet" href="edit.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fb;
            font-family: 'Montserrat', sans-serif;
        }
        .container {
            max-width: 900px;
            margin-top: 50px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .card-body {
            padding: 30px;
            background-color: #ffffff;
        }
        .card h4 {
            font-size: 1.8rem;
            color: #333;
        }
        .card p {
            font-size: 1.1rem;
            color: #666;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            font-size: 1.1rem;
            padding: 12px 25px;
            border-radius: 50px;
            margin-top: 20px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>
    <a href="main_students.html" class="back-button">Back</a>
    <div class="container">
        <h1 class="text-center text-primary">STATUS</h1>

        <div class="card mt-4">
            <div class="card-body">
                <h4>Hello, <?php echo htmlspecialchars($name); ?>!</h4>
                <p><strong>Age:</strong> <?php echo htmlspecialchars($age); ?></p>
                <p><strong>Allergies:</strong> <?php echo htmlspecialchars($allergies); ?></p>
                <p><strong>Conditions:</strong> <?php echo htmlspecialchars($conditions); ?></p>
            </div>
        </div>

        <!-- Add Allergy Survey Section -->
        <div class="card mt-4">
            <div class="card-body">
                <h4>Allergy Survey</h4>
                <p>Do you have any known allergies? If yes, please specify.</p>
                <form action="update_allergies.php" method="POST">
                    <div class="form-group">
                        <label for="allergies">Please check any allergies that apply to you:</label><br>
                        <input type="checkbox" name="allergies[]" value="Peanuts"> Peanuts<br>
                        <input type="checkbox" name="allergies[]" value="Shellfish"> Shellfish<br>
                        <input type="checkbox" name="allergies[]" value="Penicillin"> Penicillin<br>
                        <input type="checkbox" name="allergies[]" value="Dust"> Dust<br>
                        <input type="checkbox" name="allergies[]" value="NO Allergies"> None<br>
                        <input type="checkbox" name="allergies[]" value="Other"> Other (please specify below)<br>
                        <input type="text" class="form-control" name="other_allergy" placeholder="If other, please specify">
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
                </form>
            </div>
        </div>

        <!-- Add Conditions Survey Section -->
        <div class="card mt-4">
            <div class="card-body">
                <h4>Conditions Survey</h4>
                <p>Do you have any medical conditions? Please select the conditions you have.</p>
                <form action="update_conditions.php" method="POST">
                    <div class="form-group">
                        <label for="conditions">Please check any conditions that apply to you:</label><br>
                        <input type="checkbox" name="conditions[]" value="Asthma"> Asthma<br>
                        <input type="checkbox" name="conditions[]" value="Diabetes"> Diabetes<br>
                        <input type="checkbox" name="conditions[]" value="Hypertension"> Hypertension<br>
                        <input type="checkbox" name="conditions[]" value="Heart Disease"> Heart Disease<br>
                        <input type="checkbox" name="conditions[]" value="NO Allergies"> None<br>
                        <input type="checkbox" name="conditions[]" value="Other"> Other (please specify below)<br>
                        <input type="text" class="form-control" name="other_condition" placeholder="If other, please specify">
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>