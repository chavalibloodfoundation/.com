<?php
session_start();
include 'db.php';

// Get visitor's IP address
$ip_address = $_SERVER['REMOTE_ADDR'];

// Check if this IP has already visited today
$check_query = "SELECT * FROM visitors WHERE ip_address = '$ip_address' AND DATE(visit_date) = CURDATE()";
$check_result = $conn->query($check_query);

if ($check_result->num_rows == 0) {
    // Insert new visitor record
    $insert_query = "INSERT INTO visitors (ip_address) VALUES ('$ip_address')";
    $conn->query($insert_query);
}

// Get total unique visitors count
$count_query = "SELECT COUNT(DISTINCT ip_address) AS total_visitors FROM visitors";
$count_result = $conn->query($count_query);
$row = $count_result->fetch_assoc();
$total_visitors = $row['total_visitors'];

// Fetch 6 unique blood group donors with the earliest last donation date
$query = "SELECT * FROM donors GROUP BY blood_group ORDER BY last_donation ASC LIMIT 8";
$result = $conn->query($query);

$post_query = "SELECT * FROM posts ORDER BY created_at DESC LIMIT 4";
$post_result = $conn->query($post_query);

// SQL query to get donors who donated blood 4+ months ago
$query = "
    SELECT d.*
    FROM donors d
    INNER JOIN (
        SELECT blood_group, MIN(last_donation) AS earliest_donation
        FROM donors
        WHERE last_donation >= DATE_SUB(CURDATE(), INTERVAL 4 MONTH)
        GROUP BY blood_group
    ) AS grouped
    ON d.blood_group = grouped.blood_group AND d.last_donation = grouped.earliest_donation
    ORDER BY d.blood_group
";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>চাঁভালি রক্ত ফাউন্ডেশন</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet">
    <!-- Font Awesome 6 CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

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

    <!-- Slideshow -->
    <div class="slideshow-container">
        <div class="slide fade">
            <img src="images/slide.png" class="slide-img">
            <img src="images/slide.png" class="slide-img">
            <img src="images/slide.png" class="slide-img">
        </div>
        <div class="slide fade">
            <img src="images/slide.png" class="slide-img">
            <img src="images/slide.png" class="slide-img">
            <img src="images/slide.png" class="slide-img">
        </div>
        <div class="slide fade">
            <img src="images/slide.png" class="slide-img">
            <img src="images/slide.png" class="slide-img">
            <img src="images/slide.png" class="slide-img">
        </div>
    </div>

    <!-- Available Donors Section -->
    <div class="donors-container">
        <h2>Available Donors</h2>
        <div class="donor-list">
            <table class="donor-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Blood Group</th>
                        <th>Contacts</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td><img src='doner img/" . $row['image'] . "' width='70px' height='70px' alt='Donor' class='donor-table-image'></td>";
                                    echo "<td>" . $row['name'] . "</td>";
                                    echo "<td>" . $row['blood_group'] . "</td>";

                                    // --- Messenger ID extraction ---
                                    $facebook_link = $row['facebook_id'];
                                    $messenger_id = '';

                                    if (!empty($facebook_link)) {
                                        $url_parts = parse_url($facebook_link);

                                        if (isset($url_parts['query'])) {
                                            parse_str($url_parts['query'], $query_params);
                                            if (isset($query_params['id'])) {
                                                $messenger_id = $query_params['id'];
                                            }
                                        }

                                        if (!$messenger_id && isset($url_parts['path'])) {
                                            $messenger_id = ltrim($url_parts['path'], '/');
                                        }
                                    }

                                    // --- WhatsApp number cleanup ---
                                    $whatsapp_number = preg_replace('/\D/', '', $row['contact']);

                                    // --- Contact icons ---
                                    echo "<td class='contact-icons'>
                                            <a href='https://m.me/{$messenger_id}' target='_blank' title='Messenger' class='hide-on-small' style='text-decoration: none; padding: 0 5px;'>
                                                <i class='fa-brands fa-facebook-messenger fa-xl' style='color: #0548e6;'></i>
                                            </a>
                                            <a href='https://wa.me/+88{$whatsapp_number}' target='_blank' title='WhatsApp' class='hide-on-small' style='text-decoration: none; padding: 0 5px;'>
                                                <i class='fa-brands fa-whatsapp fa-xl' style='color: #0dce7b;'></i>
                                            </a>
                                            <a href='tel:+88{$row['contact']}' title='Call' class='icon-link' style='text-decoration: none; padding: 0 5px;'>
                                                <i class='fa-solid fa-phone fa-xl' style='color: #ff0000;'></i>
                                            </a>
                                        </td>";

                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' class='no-donors'>No donors available.</td></tr>";
                            }
                            ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Latest Posts Section -->
    <h2 style="text-align: center;">Latest Posts</h2>
    <div class="latest-posts">
        <?php
        if ($post_result->num_rows > 0) {
            while ($post = $post_result->fetch_assoc()) {
                echo "<div class='latest-post'>";
                echo "<img src='uploads/" . $post['image'] . "' alt='Post Image'>";
                echo "<h3>Donate No: " . $post['post_id'] . "</h3>";
                echo "<p>" . substr($post['text'], 0, 50) . "...</p>";
                echo "<a href='view_post.php?id=" . $post['post_id'] . "'>See Full Post</a>";
                echo "</div>";
            }
        } else {
            echo "<p style='text-align:center;'>No posts available.</p>";
        }
        ?>
    </div>

    <!-- Visitor Counter -->
    <footer>
        <p>Total Visitors: <strong><?php echo $total_visitors; ?></strong></p>
    </footer>


    <script src="script.js"></script>
</body>
</html>
