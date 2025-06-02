<?php
include 'db.php'; // Include database connection

$success = false; // Variable to track registration success

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $blood_group = mysqli_real_escape_string($conn, $_POST['blood_group']);
    $last_donation = mysqli_real_escape_string($conn, $_POST['last_donation']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $facebook_id = mysqli_real_escape_string($conn, $_POST['facebook_id']);

    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_type = $_FILES['image']['type'];

    // Validate image type
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($image_type, $allowed_types)) {
        die("❌ Error: Only JPG, PNG, and GIF image types are allowed.");
    }

    // Upload image
    $target_dir = "doner img/";
    // ⚠️ Make sure this folder has write permission: chmod 755 or 775
    $target_file = $target_dir . basename($image);
    move_uploaded_file($image_tmp, $target_file);

    // Insert into database
    $query = "INSERT INTO donors (name, blood_group, last_donation, contact, image, facebook_id) 
              VALUES ('$name', '$blood_group', '$last_donation', '$contact', '$image' , '$facebook_id')";

    if ($conn->query($query) === TRUE) {
        session_start();
        $_SESSION['success'] = true;
        header("Location: register.php");
        exit;
    }
}

session_start();
if (isset($_SESSION['success']) && $_SESSION['success'] == true) {
    $success = true;
    unset($_SESSION['success']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - চাঁভালি রক্ত ফাউন্ডেশন</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.maateen.me/kalpurush/font.css" rel="stylesheet">
    <style>
        .popup {
            display: <?php echo $success ? 'block' : 'none'; ?>;
            height: 160px;
            width: 300px;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            z-index: 1000;
        }

        .popup button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
        }

        .popup button:hover {
            background-color: #218838;
        }

        .overlay {
            display: <?php echo $success ? 'block' : 'none'; ?>;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
    </style>
</head>
<body>

<!-- Popup -->
<div class="overlay" id="overlay"></div>
<div class="popup" id="popup">
    <h2>Registration Successful!</h2>
    <p>Thank you for registering as a blood donor.</p>
    <button onclick="closePopup()">OK</button>
</div>

<!-- Header -->
<header>
    <div class="logo-container">
        <img src="media/logo.jpg" alt="Logo" class="logo">
        <h1 class="website-name">চাঁভালি রক্ত ফাউন্ডেশন</h1>
    </div>
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

<!-- Form -->
<div class="register-container">
    <h2>Register as a Blood Donor</h2>
    <form action="register.php" method="POST" enctype="multipart/form-data">
        <label for="name">Full Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="blood_group">Blood Group:</label>
        <select id="blood_group" name="blood_group" required>
            <option value="" disabled selected>Select your Blood Group</option>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
        </select>

        <label for="last_donation">Last Donation Date:</label>
        <input type="date" id="last_donation" name="last_donation" required>

        <label for="contact">Contact Number:</label>
        <input type="text" id="contact" name="contact" required>

        <label for="facebook_id">Facebook ID:</label>
        <input type="text" id="facebook_id" name="facebook_id" placeholder="Your Facebook ID" required>

        <label for="image">Upload Profile Image:</label>
        <input type="file" id="image" name="image" required>


        <button type="submit">Register</button>
    </form>
</div>

<script>
    function closePopup() {
        document.getElementById('popup').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    }

    // Auto close after 5 seconds
    window.onload = function() {
        setTimeout(() => {
            const popup = document.getElementById('popup');
            if (popup && popup.style.display === 'block') {
                closePopup();
            }
        }, 5000);
    };
</script>
<script src="script.js"></script>

</body>
</html>
