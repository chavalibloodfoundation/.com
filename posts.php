<?php
session_start();
include 'db.php';

// Fetch all posts
$query = "SELECT * FROM posts ORDER BY post_id DESC";
$result = $conn->query($query);

// Check for query failure
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet">
    <style>
        /* Container for the post grid */
        .post-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            padding: 20px;
        }

        /* Post card */
        .post {
            width: 23%; /* Each post takes 23% width to fit 4 in a row */
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            background: white;
        }

        /* Post image */
        .post img {
            width: 100%;
            max-height: 200px;
            object-fit: scale-down;
            border-radius: 5px;
        }

        /* See More button */
        .see-more {
            color: blue;
            text-decoration: none;
            font-weight: bold;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .post {
                width: 48%; /* Show 2 posts per row on tablets */
            }
        }

        @media (max-width: 768px) {
            .post {
                width: 100%; /* Show 1 post per row on small screens */
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

<!-- Post container -->
<div class="post-container">
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="post">
            <img src="uploads/<?php echo urlencode($row['image']); ?>" alt="Post Image">
            <h3 class="postid">Donate No: <?php echo $row['post_id']; ?></h3>
            <p>
                <?php 
                    $text = $row['text'];
                    echo (strlen($text) > 100) ? substr($text, 0, 100) . '...' : $text; 
                ?>
            </p>
            <a class="see-more" href="view_post.php?id=<?php echo $row['post_id']; ?>">See Full Post</a>
        </div>
    <?php endwhile; ?>
</div>

 <script src="script.js"></script>

</body>
</html>