<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require_once('db.php');

$user_id = $_SESSION['user_id'];

$sql = "SELECT m.message, u.username, m.timestamp
        FROM messages m
        INNER JOIN users u ON m.sender_id = u.id
        WHERE m.receiver_id = $user_id
        ORDER BY m.timestamp DESC";
$result = $conn->query($sql);

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

foreach ($messages as $message) {
    echo '<p>' . $message['username'] . ' (' . $message['timestamp'] . '): ' . $message['message'] . '</p>';
}
?>