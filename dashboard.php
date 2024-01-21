<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit;
}

require_once('db.php');

$user_id = $_SESSION['user_id'];

// Fetch messages for the logged-in user
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

// Handle sending messages
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receiver_username = $_POST['receiver_username'];
    $message = $_POST['message'];

    // Get receiver's user id
    $sql_receiver = "SELECT id FROM users WHERE username = '$receiver_username'";
    $result_receiver = $conn->query($sql_receiver);

    if ($result_receiver->num_rows > 0) {
        $row_receiver = $result_receiver->fetch_assoc();
        $receiver_id = $row_receiver['id'];

        // Insert message into the database
        $sql_insert = "INSERT INTO messages (sender_id, receiver_id, message)
                       VALUES ($user_id, $receiver_id, '$message')";
        $conn->query($sql_insert);
    } else {
        $error_message = "User not found";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
    <style>
        *{
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body>  
        <div class='flex justify-center'>
            <div class='p-10'>
                <h1 class='text-3xl'>NoMail.com</h1>
                <div class='w-5 h-1 bg-black rounded-full'></div>
            </div>
        </div>

        <div class='flex justify-center pb-10'>
            <div class='w-10/12'>
                <h2>Welcome <?php echo $user_id; ?> !</h2>
                <div class='py-4'>
                <a href="logout.php" class='border border-black rounded-full px-5 shadow-md'>Logout</a>
                </div>

                <div class='flex'>
                    <div class='p-2 border border-black rounded-md shadow-md'>
                        <h3>Send a Message</h3>
                        <form method="post" action="dashboard.php">
                            <div class='flex justify-start gap-2'>
                                <label for="receiver_username">Receiver's @nomail.com :</label>
                                <input type="text" name="receiver_username" required class='border border-black rounded-md pl-2'>
                            </div>
                            <br>
                            <div class='flex justify-start gap-2'>
                            <label for="message">Message :</label>
                            <textarea name="message" required class='resize-none border border-black rounded-md pl-2'></textarea>
                            </div>
                            <br>
                            <div>
                            <button type="submit" class='border border-black rounded-md px-5'>Send</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <div class='flex justify-center'>
            <div class='w-10/12'>
                <div class='pb-5 text-xl'>
                <h3>Received Messages</h3>
                </div>
                <?php
                foreach ($messages as $message) {
                    echo '<div class="flex py-4">';
                    echo '<div class="border border-black rounded-md px-2 shadow-md">';
                    echo '<p class="py-2">' . $message['username'] . ' (' . $message['timestamp'] . '): ' . $message['message'] . '</p>';
                    echo '</div>';
                    echo '</div>';
                }

                if(isset($error_message)) echo $error_message;
                ?>
            </div>
        </div>

    
</body>
</html>