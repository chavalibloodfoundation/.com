<?php
session_start();
include 'db.php'; // Include database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}

$message = ''; // Variable to store success/error messages

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_text = mysqli_real_escape_string($conn, $_POST['post_text']);

    // Image upload handling
    $image_name = $_FILES['post_image']['name']; // Get only the image name
    $image_tmp = $_FILES['post_image']['tmp_name'];
    $upload_dir = "uploads/";
    $image_path = $upload_dir . $image_name; // Full path for moving the file

    if (move_uploaded_file($image_tmp, $image_path)) {
        // Store only the image name in the database, let MySQL handle the post_id
        $query = "INSERT INTO posts(text, image) VALUES ('$post_text', '$image_name')";
        if ($conn->query($query)) {
            $_SESSION['message'] = "<div class='success'>Post added successfully!</div>"; // Store message in session
            header("Location: make_post.php"); // Redirect to clear POST data
            exit;
        } else {
            $_SESSION['message'] = "<div class='error'>Error: " . $conn->error . "</div>";
            header("Location: make_post.php");
            exit;
        }
    } else {
        $_SESSION['message'] = "<div class='error'>Failed to upload image.</div>";
        header("Location: make_post.php");
        exit;
    }
}

// Display the message if available
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Clear the message after displaying it
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
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
            background: #f4f4f4;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background: #d32f2f;
            padding: 15px 20px;
            color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .sidebar {
            width: 250px;
            margin-top: 100px;
            background: #2c3e50;
            color: white;
            padding: 20px;
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

        .main-content h2 {
            margin-bottom: 20px;
            color: #333;
        }

        form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        textarea, input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background: #d32f2f;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #b71c1c;
        }

        .success {
            background: #4CAF50;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .error {
            background: #f44336;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="logo-container">
            <img src="media/logo.jpg" alt="Logo" class="logo">
            <h1 class="website-name">চাঁভালি রক্ত ফাউন্ডেশন</h1>
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
        <h2>Make a Post</h2>
        <?php echo $message; ?>
        <form action="make_post.php" method="POST" enctype="multipart/form-data">
            <label for="post_text">Post Text:</label>
            <textarea name="post_text" rows="5" required></textarea>
            <label for="post_image">Upload Image:</label>
            <input type="file" name="post_image" required>
            <button type="submit">Post</button>
        </form>
    </div>
</body>
</html>
