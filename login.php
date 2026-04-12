<?php 
session_start();
include "configdb.php"; // DB connection

$error = '';

// If user is already logged in, redirect directly to book_ticket.php
if (isset($_SESSION['user_id'])) {
    header("Location: book_ticket.php");
    exit;
}

// Determine redirect target
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'book_ticket.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['name'] = $user['name'];

            // Redirect to intended page
            header("Location: $redirect");
            exit;
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #141414;
            color: #fff;
            font-family: Arial, sans-serif;
        }
        .login-container {
            max-width: 400px;
            margin: 80px auto;
            padding: 30px;
            background-color: #1a1a1a;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.7);
        }
        .login-container h2 {
            text-align: center;
            color: #e50914;
            margin-bottom: 25px;
        }
        .login-container input[type="email"],
        .login-container input[type="password"] {
            width: 93%;
            padding: 12px;
            margin: 10px 0 20px 0;
            border: none;
            border-radius: 6px;
            background-color: #222;
            color: #fff;
        }
        .login-container input::placeholder {
            color: #ccc;
        }
        .login-container button {
            width: 100%;
            padding: 12px;
            background-color: #e50914;
            border: none;
            border-radius: 6px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        .login-container button:hover {
            background-color: #b00710;
        }
        .login-container p {
            text-align: center;
            margin-top: 15px;
        }
        .login-container a {
            color: #e50914;
            text-decoration: none;
        }
        .login-container a:hover {
            text-decoration: underline;
        }
        .error-message {
            background-color: #660000;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
            margin-bottom: 15px;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if($error) echo "<div class='error-message'>$error</div>"; ?>
        <form method="POST" action="login.php?redirect=<?= urlencode($redirect) ?>">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? 
            <a href="signup.php?redirect=<?= urlencode($redirect) ?>">Sign Up</a>
        </p>
    </div>
</body>
</html>
