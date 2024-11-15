<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch the user from the database
    $sql = "SELECT id, password, status, role FROM users WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists and password is correct
    if ($user && password_verify($password, $user['password'])) {
        if ($user['status'] == 'deactivated') {
            echo "<script>alert('Your account has been deactivated. Please contact the admin.'); window.location.href = 'login_page.php';</script>";
            exit();
        }

        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        header("Location: dashboard.php"); // Redirect to the dashboard or appropriate page
        exit();
    } else {
        echo "<script>alert('Invalid email or password'); window.location.href = 'login_page.php';</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .back-button {
    background-color: grey;
    color: #ffffff; 
    padding: 10px 20px; 
    margin-top: 20px;
    margin-left: 20px;
    border-radius: 5px; 
    text-decoration: none; 
    display: inline-block; 
    transition: background-color 0.3s ease;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
}

.back-button:hover {
    background-color: darkred;
}
    </style>
</head>
<body>
    <li><a class="back-button" href="home.html">Home</a></li>
    <div class="container">
        <h1 class="healthcard">Health Card</h1>

        <!--Login Form-->
        <div id="login-form" class="form-wrapper">
            <img src="462116111_3061095900698632_3691388022954901051_n.jpg" alt="Image" class="form-image">
            <div class="form-content">
                <form action="login.php" method="POST">
                    <input type="email" name="email" placeholder="Email" required class="form-control2">
                    <input type="password" name="password" placeholder="Password" required class="form-control2">
                    <button type="submit" class="button-login1">Log In</button>
                    <p><a href="#" id="show-signup" style="text-decoration: none;">Sign Up</a></p>
                    <p><a href="request_reset.php" style="text-decoration: none; margin-left: 90px;">Forgot Password?</a></p>
                </form>
            </div>
        </div>

        <!--Register Form-->
        <div id="register-form" class="form-wrapper" style="display: none;">
            <img src="462116111_3061095900698632_3691388022954901051_n.jpg" alt="Image" class="form-image2">
            <div class="form-content">
                <form id="addStudentForm" action="register.php" method="POST" enctype="multipart/form-data">
                    <input type="text" name="name" placeholder="Name" required class="form-control">
                    <input type="text" name="lrn" placeholder="LRN" required class="form-control" maxlength="12" pattern="\d{12}" title="LRN must be exactly 12 digits">
                    <input type="email" name="email" placeholder="Email" required class="form-control">
                    <input type="password" name="password" placeholder="Password" required class="form-control">
                    <input type="password" name="confirm-password" placeholder="Confirm Password" required class="form-control">
                    <input type="text" name="emergency_number" placeholder="Emergency Number" required class="form-control" maxlength="11" pattern="\d{11}" title="Emergency number must be exactly 11 digits">
                    <input type="text" name="address" placeholder="Address" required class="form-control">
                    <input type="number" name="age" placeholder="Age" required class="form-control" min="1" max="120">
                    <select name="gender" required class="form-control">
                        <option value="" disabled selected>Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                    <button type="submit" class="button1">Register</button>
                    <p><a href="#" id="show-login" style="text-decoration: none; color: skyblue;">Already registered?</a></p>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@latest/dist/email.min.js"></script>
    <script src="js/email.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    emailjs.init("AKjVozw_GUcVzpOcI");

    //this will show the signup form
    document.getElementById('show-signup').addEventListener('click', function(e) {
        e.preventDefault();
        const loginForm = document.getElementById('login-form');
        const signupForm = document.getElementById('register-form');

        loginForm.classList.add('fade-out');
        setTimeout(() => {
            loginForm.style.display = 'none'; 
            signupForm.style.display = 'block'; 
            signupForm.classList.add('fade-in'); 
        }, 1000); 
    });

    //this will show login
    document.getElementById('show-login').addEventListener('click', function(e) {
        e.preventDefault();
        const signupForm = document.getElementById('register-form');
        const loginForm = document.getElementById('login-form');

        signupForm.classList.add('fade-out');
        setTimeout(() => {
            signupForm.style.display = 'none'; 
            loginForm.style.display = 'block'; 
            loginForm.classList.add('fade-in'); 
        }, 1000); 
    });

    //for notification email
    document.getElementById("addStudentForm").addEventListener("submit", function(event) {
        const formData = new FormData(this);
        const name = formData.get("name");
        const lrn = formData.get("lrn");
        const email = formData.get("email");
        const emergencyNumber = formData.get("emergency_number");
        const age = formData.get("age");
        const gender = formData.get("gender");
        const submitButton = this.querySelector('button[type="submit"]');

        submitButton.disabled = true;
        submitButton.textContent = "Registering..."; 

        emailjs.send("service_hr3leqd", "template_ietxcck", {
            student_name: name,
            student_lrn: lrn,
            student_email: email,
            student_contact: emergencyNumber,
            student_age: age,
            student_gender: gender
        }).then((response) => {
            console.log("SUCCESS!", response.status, response.text);
            alert('Registration successful! Check your email for confirmation.');

            event.target.submit();
        }).catch((error) => {
            console.error("FAILED...", error);
            alert('An error occurred while sending the email. Please try again later.');
        }).finally(() => {
            submitButton.disabled = false;
            submitButton.textContent = "Register"; 
        });

        event.preventDefault();
    });
});

    </script>
    <script src="script.js"></script>
</body>
</html>