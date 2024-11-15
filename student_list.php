<?php
session_start();
require_once 'db.php';

// Check if user is admin, otherwise redirect to login
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== 1) {
    header("Location: login_page.php");
    exit();
}

// Toggle student status (active/inactive)
if (isset($_GET['toggle_status']) && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch current status from the users table
    $userStmt = $conn->prepare("SELECT status FROM users WHERE id = :id");
    $userStmt->bindParam(':id', $id);
    $userStmt->execute();
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Toggle status: active (text) <-> inactive (text)
        $newStatus = ($user['status'] == 'active') ? 'inactive' : 'active';

        // Update status in users table
        $updateUserStmt = $conn->prepare("UPDATE users SET status = :status WHERE id = :id");
        $updateUserStmt->bindParam(':status', $newStatus, PDO::PARAM_STR);
        $updateUserStmt->bindParam(':id', $id, PDO::PARAM_INT);
        $updateUserStmt->execute();

        // Optionally update students' status (if needed)
        $updateStudentStmt = $conn->prepare("UPDATE students SET status = :status WHERE id = :id");
        $updateStudentStmt->bindParam(':status', $newStatus, PDO::PARAM_STR);
        $updateStudentStmt->bindParam(':id', $id, PDO::PARAM_INT);
        $updateStudentStmt->execute();

        // Redirect back to student list page to prevent resubmission
        header("Location: student_list.php");
        exit();
    }
}

// Fetch the list of students
$stmt = $conn->prepare("SELECT id, name, email, lrn, emergency_phone, address, gender, age, allergies, conditions, status FROM students");
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If no students are found, handle gracefully without debugging output
if (empty($students)) {
    echo "No students found!";
} else {
    foreach ($students as $student) {
        // Determine the button text based on student status
        $buttonText = ($student['status'] == 'active') ? 'Deactivate' : 'Activate';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List</title>
    <link rel="stylesheet" href="main.css">
    <style>
    body {
        background-image: url('https://img.freepik.com/premium-photo/stethoscope-medicine-accessories-black-background-with-copy-space_362520-268.jpg?ga=GA1.1.1160075450.1730121164&semt=ais_siglip');
    }
    .container {
        margin-top: 100px;
        width: 90%;
        margin: auto;
        overflow-x: auto;
        overflow-y: scroll;
        background-color: rgba(0, 0, 0, 0.6);
        border-radius: 10px;
        padding: 20px;
    }

    h1 {
        text-align: center;
        color: #ffffff;
        padding: 20px 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 100px;
        table-layout: auto;
    }

    table, th, td {
        border: 1px solid #444;
    }

    th, td {
        padding: 12px;
        text-align: center;
        color: #ffffff;
        white-space: nowrap; 
    }

    th {
        background-color: #333;
    }

    tr:nth-child(even) {
        background-color: rgba(255, 255, 255, 0.1);
    }

    tr:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }

    .delete {
        padding: 5px 10px;
        background-color: darkred;
        color: #ffffff;
        text-decoration: none;
        border-radius: 5px;
        margin: 5px;
        display: inline-block;
    }

    .delete:hover {
        background-color: red;
    }

    .edit-button {
        background-color: goldenrod;
        color: #ffffff;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
        border-radius: 5px;
        text-decoration: none;
    }

    .edit-button:hover {
        background-color: gold;
    }

    .no-students {
        color: #d9534f;
        font-size: 20px;
        text-align: center;
        margin-top: 20px;
    }

    .back-buttons {
    background-color: grey;
    color: #ffffff; 
    padding: 10px 20px; 
    margin-top: 15px;
    margin-left: 20px;
    border-radius: 5px; 
    text-decoration: none; 
    display: inline-block; 
    transition: background-color 0.3s ease;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
}

.back-buttons:hover {
    background-color: darkred;
}
</style>
</head>
<body>
    <a href="inventory_management.html" class="back-buttons">Back</a>
    <div class="container">
        <h1>Student List</h1>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>LRN</th>
                    <th>Emergency Phone</th>
                    <th>Address</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Allergies</th>
                    <th>Conditions</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($students)): ?>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['name']); ?></td>
                            <td><?php echo htmlspecialchars($student['email']); ?></td>
                            <td><?php echo htmlspecialchars($student['lrn']); ?></td>
                            <td><?php echo htmlspecialchars($student['emergency_phone']); ?></td>
                            <td><?php echo htmlspecialchars($student['address']); ?></td>
                            <td><?php echo htmlspecialchars($student['gender']); ?></td>
                            <td><?php echo htmlspecialchars($student['age']); ?></td>
                            <td><?php echo htmlspecialchars($student['allergies']); ?></td>
                            <td><?php echo htmlspecialchars($student['conditions']); ?></td>
                            <td><?php echo htmlspecialchars($student['status']); ?></td>
                            <td class="actions">
                                <?php if ($student['status'] == 'active'): ?>
                                    <a href="?toggle_status=true&id=<?php echo $student['id']; ?>" class="delete">Deactivate</a>
                                <?php else: ?>
                                    <a href="?toggle_status=true&id=<?php echo $student['id']; ?>" class="edit-button">Activate</a>
                                <?php endif; ?>
                                <a href="edit_student.php?id=<?php echo $student['id']; ?>" class="edit-button">Edit</a>
                                <a href="delete_student.php?id=<?php echo $student['id']; ?>" class="delete" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11" class="no-students">No students found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
