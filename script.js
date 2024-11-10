(function() {
    emailjs.init("AKjVozw_GUcVzpOcI"); 
})();

document.getElementById("addStudentForm").addEventListener("submit", function(event) {
    event.preventDefault(); 

    const formData = new FormData(this);
    const name = formData.get("name");
    const lrn = formData.get("lrn");
    const email = formData.get("email");
    const emergencyNumber = formData.get("emergency_number");
    const address = formData.get("address");
    const age = formData.get("age");
    const gender = formData.get("gender");
    const submitButton = this.querySelector('button[type="submit"]');

    submitButton.disabled = true;
    submitButton.textContent = "Registering..."; 

    emailjs.send("service_hr3leqd", "template_ietxcck", {
        name: name,
        lrn: lrn,
        email: email,
        emergency_number: emergencyNumber,
        address: address,
        age: age,
        gender: gender
    }).then(function(response) {
        console.log("SUCCESS!", response.status, response.text);
        alert('Registration successful! Check your email for confirmation.');
        window.location.href = "student_list.php"; 
    }, function(error) {
        console.error("FAILED...", error);
        alert('An error occurred while sending the email. Please try again later.');
    }).finally(() => {
        submitButton.disabled = false;
        submitButton.textContent = "Register"; 
    });
});

// Animation for login and signup
document.getElementById('show-signup').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('login-form').style.display = 'none';
    const signupForm = document.getElementById('signup-form');
    signupForm.style.display = 'block';
    signupForm.classList.add('fade-in');
});

document.getElementById('show-login').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('signup-form').style.display = 'none';
    const loginForm = document.getElementById('login-form');
    loginForm.style.display = 'block';
    loginForm.classList.add('fade-in');
});

// Ensure DOM elements are loaded before accessing them
document.addEventListener("DOMContentLoaded", function() {
    const showSignupLink = document.getElementById("show-signup");
    const showLoginLink = document.getElementById("show-login");
    const loginForm = document.getElementById("login-form");
    const signupForm = document.getElementById("signup-form");

    // Function to show the signup form and hide the login form
    showSignupLink.addEventListener("click", function(event) {
        event.preventDefault(); 
        loginForm.style.display = "none";
        signupForm.style.display = "block"; 
    });

    showLoginLink.addEventListener("click", function(event) {
        event.preventDefault(); 
        signupForm.style.display = "none"; 
        loginForm.style.display = "block"; 
    });
});
