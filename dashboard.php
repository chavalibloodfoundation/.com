<?php
session_start();
include 'db.php'; // Include database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}

//Get the total number of donors
$query = "SELECT COUNT(*) AS total_donors FROM donors";
$result = $conn->query($query);
$total_donors = ($result->num_rows > 0) ? $result->fetch_assoc()['total_donors'] : 0;

//Get the total number of admins
$query = "SELECT COUNT(*) AS total_admins FROM admin";
$result = $conn->query($query);
$total_admins = ($result->num_rows > 0) ? $result->fetch_assoc()['total_admins'] : 0;

//Get the total number of posts
$query = "SELECT COUNT(*) AS total_posts FROM posts";
$result = $conn->query($query);
$total_posts = ($result->num_rows > 0) ? $result->fetch_assoc()['total_posts'] : 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet">
    <style>
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
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .card h3 {
            margin-bottom: 10px;
            font-size: 27px;
            color: red;
        }
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
    font-size: 30px;
    font-weight: bold;
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
</header>
    <!-- Sidebar -->
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
        
        <!-- Dashboard Cards -->
        <div class="cards">
            <div class="card">
                <h3><b><?php echo $total_donors; ?></b></h3>
                <p>Total Donors</p>
            </div>
            <div class="card">
                <h3><b><?php echo $total_admins; ?></b></h3>
                <p>Total Admins</p>
            </div>
            <div class="card">
                <h3><b><?php echo $total_posts; ?></b></h3>
                <p>Total Posts</p>
            </div>
            <div class="card">
                <h3>45.8k</h3>
                <p>Total Views</p>
            </div>
        </div>
    </div>
</body>
</html>
