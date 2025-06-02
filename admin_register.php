<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$error = '';
$success = false;

// Check if form was submitted and handle the POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form input values
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Hash the password using bcrypt
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert the new admin into the database
    $query = "INSERT INTO admin (username, password) VALUES ('$username', '$hashed_password')";
    
    if ($conn->query($query)) {
        // Set a session variable to trigger the success popup
        $_SESSION['success'] = true;
        // Redirect after successful registration to prevent form re-submission
        header("Location: admin_register.php");
        exit;
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Register</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet">
    <style>
        /* Same styles as before */
        
        .register-form { 
            background-color: white; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); 
            text-align: center; 
        }

        h2 { 
            margin-bottom: 20px; 
            font-size: 24px; 
            color: #333; 
        }

        .error-message { 
            color: #e74c3c; 
            margin-bottom: 15px; 
            font-size: 14px; 
        }

        .input-group { 
            margin-bottom: 15px; 
        }

        input { 
            width: 30%; 
            padding: 12px; 
            border-radius: 5px; 
            border: 1px solid #ddd; 
            font-size: 16px; 
            margin-top: 10px; 
            transition: 0.3s; 
        }

        input:focus { 
            border-color: #ff7e5f; 
            outline: none; 
        }

        button { 
            width: 30%; 
            padding: 12px; 
            background-color: #ff7e5f; 
            color: white; border: none; 
            border-radius: 5px; 
            font-size: 18px; 
            cursor: pointer; 
            transition: 0.3s; 
        }

        button:hover { 
            background-color: #feb47b; 
        }
        .dd{
            width: 100%;
        }

        /* Success Popup Styling */
        .popup { 
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%; 
            background-color: rgba(0, 0, 0, 0.5); 
            display: none; 
            justify-content: center; 
            align-items: center; 
            z-index: 9999; 
            opacity: 0; 
            animation: fadeIn 0.5s forwards; 
        }

        .popup-content { 
            background-color: white; 
            padding: 30px; 
            border-radius: 10px; 
            text-align: center; 
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3); 
            transform: scale(0); 
            animation: popupAnimation 0.5s forwards; 
        }

        .popup-content h2 { 
            color: #2ecc71; 
        }

        .popup .close { 
            position: absolute; 
            top: 10px; 
            right: 10px; 
            font-size: 30px; 
            cursor: pointer; 
        }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        @keyframes popupAnimation { from { transform: scale(0); } to { transform: scale(1); } }

        @media (max-width: 480px) { .register-form { width: 90%; padding: 20px; } }

        /* Header Styling */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #d32f2f;
            padding: 15px 20px;
            color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            flex-wrap: nowrap;
            flex-direction: column;
        }
        /* Logo and Website Name */
        .logo-container {
            display: flex;
            align-items: center;
        }
        .logo {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .logo-container h1{
            margin: 0px;
        }
        .website-name {
            font-family: 'Kalpurush', sans-serif;
            font-size: 40px;
            font-weight: bold;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            display: flex;
            height: 100vh;
            background: #f4f4f4;
            flex-direction: column;
        }
        .sidebar {
            margin-top: 100px;
            width: 250px;
            background: #2c3e50;
            padding: 20px;
            color: white;
            position: fixed;
            height: 100%;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            color: white;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            padding: 10px;
            margin: 10px 0;
            background: #34495e;
            text-align: center;
            border-radius: 5px;
        }
        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
        }
        .sidebar ul li:hover {
            background: #1abc9c;
        }
        .main-content {
            margin-left: 270px;
            padding: 20px;
            flex: 1;
        }
    </style>
</head>
<body>
    <!-- Header -->
<header>
    <div class="logo-container">
        <img src="media/logo.jpg" alt="Logo" class="logo">
        <h1 class="website-name">চাঁভালি রক্ত  ফাউন্ডেশন</h1>
    </div>
</header><!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="make_post.php">Make Post</a></li>
            <li><a href="admin_register.php">Admin Register</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="register-form">
            <h2>Create New Admin</h2>
            <?php if ($error): ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php endif; ?>

            <form method="POST" action="admin_register.php">
                <div class="input-group">
                    <input type="text" name="username" placeholder="Username" required value="">
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required value="">
                </div>
                <button type="submit">Register Admin</button>
            </form><br>
        </div>
    </div>

    <!-- Success Popup -->
    <?php if (isset($_SESSION['success']) && $_SESSION['success'] == true): ?>
        <div id="successPopup" class="popup">
            <div class="popup-content">
                <span class="close" onclick="closePopup()">&times;</span>
                <h2>New Admin Registered Successfully!</h2>
            </div>
        </div>
        <script>
            // Show the popup after successful registration
            document.getElementById('successPopup').style.display = 'flex';

            // Close popup function
            function closePopup() {
                document.getElementById('successPopup').style.display = 'none';
            }

            // Close the popup after 3 seconds automatically
            setTimeout(closePopup, 3000);
        </script>
        <?php 
            // Unset the success session variable after showing the popup
            unset($_SESSION['success']);
        ?>
    <?php endif; ?>
</body>
</html>
