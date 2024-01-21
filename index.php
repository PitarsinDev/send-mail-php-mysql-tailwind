<?php
session_start();

if(isset($_SESSION['user_id'])){
    header("Location: dashboard.php");
    exit;
}

require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['id'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

        <div class='flex justify-center p-10'>
            <div>
                <div class='p-5'>
                <h2 class='text-center text-xl'>Login</h2>
                </div>

                    <form method="post" action="index.php">
                        <div>
                            <div class='flex gap-2'>
                                <label for="username">Username :</label>
                                <input type="text" name="username" required class='border border-black rounded-md pl-2'>
                            </div>
                            <br>
                            <div class='flex gap-2'>
                                <label for="password">Password :</label>
                                <input type="password" name="password" required class='border border-black rounded-md pl-2'>
                            </div>
                        </div>

                        <div class='py-5'>
                            <button type="submit" class='border border-black rounded-md px-10 hover:text-white hover:bg-black transition'>Login</button>
                        </div>
                    </form>
            </div>
        </div>

        <?php if(isset($error)) echo $error; ?>
    
</body>
</html>