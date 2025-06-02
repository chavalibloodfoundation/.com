<?php
session_start();
include 'db.php';

// Get the post ID from the URL parameter
$post_id = isset($_GET['id']) ? $_GET['id'] : null;

// Check if post ID is valid
if ($post_id) {
    // Query to get the post details from the database
    $query = "SELECT * FROM posts WHERE post_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the post exists
    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();
    } else {
        echo "Post not found.";
        exit;
    }
} else {
    echo "Invalid post ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor No: <?php echo $post['post_id']; ?></title> <!-- Show post ID in the page title -->
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet">
    <style>
        /* Container to align image and text side by side */
        .post-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 20px;
        }

        /* Image styling */
        .post-image {
            width: 40%; /* Adjust the image size */
            max-height: 300px;
            object-fit: cover;
            border-radius: 8px;
        }
        .post-image img{
            object-fit: scale-down;
            height: 400px;
            width: 400px;
        }

        /* Text styling */
        .post-text {
            width: 55%; /* Adjust the text block width */
            padding-left: 20px;
            text-align: justify;
        }

        /* Post content */
        .post-text p {
            font-size: 1.2em;
        }
        @media (max-width: 768px) {
            .post-image img{
                object-fit: scale-down;
                width: 250px;
            }
        }
        @media (max-width: 576px) {
    .post-container {
        flex-direction: column; /* Stack items vertically */
        align-items: center; /* Center align the content */
    }

    .post-image {
        width: 100%;
        text-align: center;
        margin-bottom: 15px;
    }

    .post-image img {
        width: 100%;
        height: auto;
        max-width: 100%;
        object-fit: contain;
    }

    .post-text {
        width: 100%;
        top: 170px;
        position: relative;
        padding-left: 0;
        text-align: justify;
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

        <!-- Responsive Nav -->
        <nav>
            <button id="menu-toggle" class="menu-toggle">☰</button>
            <ul class="menu" id="nav-menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="donors.php">Donors</a></li>
                <li><a href="register.php">Register</a></li>
                <li><a href="posts.php">Posts</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>

<!-- Display the full post -->
<div class="post-container">
    <!-- Post image -->
    <div class="post-image">
        <img src="uploads/<?php echo urlencode($post['image']); ?>" alt="Post Image">
    </div>

    <!-- Post text -->
    <div class="post-text">
            <h3>Donate No: <?php echo nl2br(htmlspecialchars($post['post_id'])); ?></h3>
        <p><?php echo nl2br(htmlspecialchars($post['text'])); ?></p>
    </div>
</div>

 <script src="script.js"></script>

</body>
</html>
