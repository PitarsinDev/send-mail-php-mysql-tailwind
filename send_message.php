<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require_once('db.php');

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receiver_username = $_POST['receiver_username'];
    $message = $_POST['message'];

    $sql_receiver = "SELECT id FROM users WHERE username = '$receiver_username'";
    $result_receiver = $conn->query($sql_receiver);

    if ($result_receiver->num_rows > 0) {
        $row_receiver = $result_receiver->fetch_assoc();
        $receiver_id = $row_receiver['id'];

        $sql_insert = "INSERT INTO messages (sender_id, receiver_id, message)
                       VALUES ($user_id, $receiver_id, '$message')";
        $conn->query($sql_insert);

        header("Location: dashboard.php");
        exit;
    } else {
        $error_message = "User not found";
    }
}
?>