<?php
include 'db.php'; // Include database connection

// Get the selected blood group from the filter form
$selected_group = isset($_GET['blood_group']) ? $_GET['blood_group'] : '';

// SQL query to get donors who donated blood 4+ months ago
$query = "SELECT * FROM donors WHERE last_donation <= DATE_SUB(CURDATE(), INTERVAL 4 MONTH)";

// Apply blood group filter if selected
if ($selected_group != '') {
    $query .= " AND blood_group = '$selected_group'";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>চাঁভালি রক্ত  ফাউন্ডেশন</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet">
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

<!-- Filter Form -->
<div class="filter-container">
    <form method="GET" action="donors.php">
        <label for="blood_group">Filter Donor:</label>
        <select name="blood_group" id="blood_group">
            <option value="">All Blood Groups</option>
            <option value="A+" <?= ($selected_group == "A+") ? "selected" : "" ?>>A+</option>
            <option value="A-" <?= ($selected_group == "A-") ? "selected" : "" ?>>A-</option>
            <option value="B+" <?= ($selected_group == "B+") ? "selected" : "" ?>>B+</option>
            <option value="B-" <?= ($selected_group == "B-") ? "selected" : "" ?>>B-</option>
            <option value="O+" <?= ($selected_group == "O+") ? "selected" : "" ?>>O+</option>
            <option value="O-" <?= ($selected_group == "O-") ? "selected" : "" ?>>O-</option>
            <option value="AB+" <?= ($selected_group == "AB+") ? "selected" : "" ?>>AB+</option>
            <option value="AB-" <?= ($selected_group == "AB-") ? "selected" : "" ?>>AB-</option>
        </select>
        <button type="submit">Filter</button>
    </form>
</div>

<!-- Donor List -->
<div class="donor-list">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="donor-card">';
            echo '<img src="doner img/' . $row['image'] . '" alt="Donor Image" class="donor-image">';
            echo '<p class="ready-text">Ready to donate Blood</p>'; // Green text
            echo '<div class="donor-info">';
            echo '<h3><strong>Name: </strong>' . $row['name'] . '</h3>';
            echo '<p><strong>Blood Group:</strong> ' . $row['blood_group'] . '</p>';
            echo '<p><strong>Last Donation:</strong> ' . $row['last_donation'] . '</p>';
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
                                    echo "<p class='contact-icons'><strong>Contact:
                                            <a href='https://m.me/{$messenger_id}' target='_blank' title='Messenger' class='hide-on-small' style='text-decoration: none; padding: 0 5px;'>
                                                <i class='fa-brands fa-facebook-messenger fa-xl' style='color: #0548e6;'></i>
                                            </a>
                                            <a href='https://wa.me/+88{$whatsapp_number}' target='_blank' title='WhatsApp' class='hide-on-small' style='text-decoration: none; padding: 0 5px;'>
                                                <i class='fa-brands fa-whatsapp fa-xl' style='color: #0dce7b;'></i>
                                            </a>
                                            <a href='tel:+88{$row['contact']}' title='Call' class='icon-link' style='text-decoration: none; padding: 0 5px;'>
                                                <i class='fa-solid fa-phone fa-xl' style='color: #ff0000;'></i>
                                            </a>
                                        </strong></p>";
                                    echo '</div>';
                                    echo '</div>';
                                }
    } else {
        echo '<p class="no-donors">No donors found for this blood group.</p>';
    }
    ?>
</div>

     <script src="script.js"></script>

</body>
</html>
