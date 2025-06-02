<?php
session_start();
include 'db.php';  // Include your database connection

// Redirect if the admin is already logged in
if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit;
}

// Initialize error variable
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username and password from the form
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Query to find the admin user by username
    $query = "SELECT * FROM admin WHERE username='$username'";
    $result = $conn->query($query);
    $admin = $result->fetch_assoc();

    // Check if admin exists and verify password
    if ($admin && password_verify($password, $admin['password'])) {
        // Password matches, create session
        $_SESSION['admin'] = $admin['username'];
        header("Location: dashboard.php");  // Redirect to dashboard after successful login
        exit;
    } else {
        // Invalid login
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        /* Reset some default styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Arial', sans-serif;
}

/* Body background */
body {
    background: linear-gradient(to right, #ff7e5f, #feb47b);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    overflow: hidden;
    color: white;
}

/* Popup */
.popup {
    display: none;  /* Hidden by default */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);  /* Transparent background */
    justify-content: center;
    align-items: center;
    z-index: 9999;  /* Make sure the popup is above everything */
}

/* Popup content */
.popup-content {
    background-color: white;
    padding: 30px;
    border-radius: 10px;
    width: 350px;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Close button */
.popup .close {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 20px;
    cursor: pointer;
}

/* Title */
h2 {
    margin-bottom: 20px;
    font-size: 24px;
    color: #333;
}

/* Error message */
.error-message {
    color: #e74c3c;
    margin-bottom: 15px;
    font-size: 14px;
}

/* Input fields */
.input-group {
    margin-bottom: 15px;
}

input {
    width: 100%;
    padding: 12px;
    margin-top: 10px;
    border-radius: 5px;
    border: 1px solid #ddd;
    font-size: 16px;
    transition: 0.3s;
}

/* Focus effect on input fields */
input:focus {
    border-color: #ff7e5f;
    outline: none;
}

/* Button styles */
button {
    width: 100%;
    padding: 12px;
    background-color: #ff7e5f;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 18px;
    cursor: pointer;
    transition: 0.3s;
}

/* Button hover effect */
button:hover {
    background-color: #feb47b;
}

/* Responsive styling for small screens */
@media (max-width: 480px) {
    .popup-content {
        width: 90%;
    }
}

    </style>
    <script>
        // Function to open the login popup when page loads
        function openPopup() {
            document.getElementById('loginPopup').style.display = 'flex';
        }
        // Function to close the login popup
        function closePopup() {
            document.getElementById('loginPopup').style.display = 'none';
        }
        // Automatically open the popup when page loads
        window.onload = openPopup;
    </script>
</head>
<body>
    <!-- Popup login form -->
    <div id="loginPopup" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closePopup()">&times;</span>
            <h2>Admin Login</h2>
            <?php if ($error): ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="POST">
                <div class="input-group">
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
