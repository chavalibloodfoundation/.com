<?php
include 'db.php';

header('Content-Type: application/json');

// Adjust the table and column names according to your database structure
$query = "SELECT image, text FROM posts";
$result = $conn->query($query);

$posts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}

echo json_encode($posts);
?>
