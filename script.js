document.getElementById('show-signup').addEventListener('click', function(e) {
    e.preventDefault(); // Prevent the link from refreshing the page

    // Fade out the login form
    const loginForm = document.getElementById('login-form');
    loginForm.classList.add('fade-out');
    
    // Wait for the fade-out to finish before hiding and showing forms
    setTimeout(function() {
        loginForm.style.display = 'none'; // Hide login form
        const signupForm = document.getElementById('signup-form');
        signupForm.style.display = 'block'; // Show signup form
        signupForm.classList.add('fade-in'); // Fade in the signup form
    }, 500); // Match fade-out duration (500ms)
});

document.getElementById('show-login').addEventListener('click', function(e) {
    e.preventDefault(); // Prevent the link from refreshing the page

    // Fade out the signup form
    const signupForm = document.getElementById('signup-form');
    signupForm.classList.add('fade-out');
    
    // Wait for the fade-out to finish before hiding and showing forms
    setTimeout(function() {
        signupForm.style.display = 'none'; // Hide signup form
        const loginForm = document.getElementById('login-form');
        loginForm.style.display = 'block'; // Show login form
        loginForm.classList.add('fade-in'); // Fade in the login form
    }, 500); // Match fade-out duration (500ms)
});
