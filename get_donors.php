<?php
include 'db.php';

header('Content-Type: application/json');

$query = "SELECT name, blood_group, contact FROM donors";
$result = $conn->query($query);

$donors = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $donors[] = $row;
    }
}

echo json_encode($donors);
?>
