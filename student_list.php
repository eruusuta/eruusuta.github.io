<?php
session_start();

// Ensure the user is logged in as an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['is_admin'] !== 1) {
    header("Location: login_page.html");
    exit();
}

include 'db.php';

// Fetch student data including allergies and conditions
$results = [];

try {
    $sql = "SELECT id, name, email, lrn, emergency_phone, address, gender, age, allergies, conditions FROM students";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching student data: " . $e->getMessage();
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($results)): ?>
                    <?php foreach ($results as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['lrn']); ?></td>
                            <td><?php echo htmlspecialchars($row['emergency_phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                            <td><?php echo htmlspecialchars($row['gender']); ?></td>
                            <td><?php echo htmlspecialchars($row['age']); ?></td>
                            <td><?php echo htmlspecialchars($row['allergies']); ?></td>
                            <td><?php echo htmlspecialchars($row['conditions']); ?></td>
                            <td class="actions">
                                <a href="edit_student.php?id=<?php echo $row['id']; ?>" class="edit-button">Edit</a>
                                <a href="delete_student.php?id=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Are you sure?')">Delete</a>
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
