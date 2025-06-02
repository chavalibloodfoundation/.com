<?php
session_start();
include 'db.php'; // Make sure your db.php contains $conn = new mysqli(...)

// Show errors (only in development)
error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $user['username'];
                $message = "✅ Login successful! Welcome, " . htmlspecialchars($user['username']);
                // header("Location: dashboard.php"); exit; // Redirect if needed
            } else {
                $message = "❌ Incorrect password.";
            }
        } else {
            $message = "❌ User not found.";
        }

        $stmt->close();
    } else {
        $message = "❌ Database error: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Password Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 40px;
            text-align: center;
        }
        .login-box {
            background: white;
            padding: 20px;
            display: inline-block;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input[type=text], input[type=password] {
            padding: 10px;
            width: 90%;
            margin: 10px 0;
        }
        button {
            padding: 10px 20px;
            background: #28a745;
            border: none;
            color: white;
            cursor: pointer;
        }
        .message {
            margin-top: 20px;
            color: #d9534f;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Login</h2>
    <form method="POST" action="password.php">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>

    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
</div>

</body>
</html>
