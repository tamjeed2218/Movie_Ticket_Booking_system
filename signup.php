<?php
session_start();
include "configdb.php"; // DB connection

$error = '';
$success = '';

// If user is already logged in, redirect to book_ticket.php
if (isset($_SESSION['user_id'])) {
    header("Location: book_ticket.php");
    exit;
}

// Determine redirect target
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'book_ticket.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $age = intval($_POST['age']);

    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $check_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
        $check_result = mysqli_query($conn, $check_query);

        if ($check_result && mysqli_num_rows($check_result) > 0) {
            $error = "Email already registered!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_query = "INSERT INTO users (name, email, password, age, role_id) 
                             VALUES ('$name', '$email', '$hashed_password', $age, 1)";

            if (mysqli_query($conn, $insert_query)) {
                $user_id = mysqli_insert_id($conn);

                // Set session
                $_SESSION['user_id'] = $user_id;
                $_SESSION['role_id'] = 1; // registered user
                $_SESSION['name'] = $name;

                // Redirect to intended page
                header("Location: $redirect");
                exit;
            } else {
                $error = "Error creating account: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #141414;
            color: #fff;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .signup-container {
            max-width: 400px;
            width: 100%;
            padding: 30px;
            background-color: #1a1a1a;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.7);
        }
        .signup-container h2 {
            text-align: center;
            color: #e50914;
            margin-bottom: 25px;
        }
        .signup-container input[type="text"],
        .signup-container input[type="email"],
        .signup-container input[type="password"],
        .signup-container input[type="number"] {
            width: 93%;
            padding: 12px;
            margin: 10px 0 20px 0;
            border: none;
            border-radius: 6px;
            background-color: #222;
            color: #fff;
        }
        .signup-container input::placeholder {
            color: #ccc;
        }
        .signup-container button {
            width: 100%;
            padding: 12px;
            background-color: #e50914;
            border: none;
            border-radius: 6px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        .signup-container button:hover {
            background-color: #b00710;
        }
        .signup-container p {
            text-align: center;
            margin-top: 15px;
        }
        .signup-container a {
            color: #e50914;
            text-decoration: none;
        }
        .signup-container a:hover {
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
        @media (max-width: 500px) {
            .signup-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Sign Up</h2>

        <?php if($error) echo "<div class='error-message'>$error</div>"; ?>
        <?php if($success) echo "<div class='error-message' style='background-color: #006600;'>$success</div>"; ?>

        <form method="POST" action="signup.php?redirect=<?= urlencode($redirect) ?>">
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <input type="number" name="age" placeholder="Age" min="1">

            <button type="submit">Sign Up</button>
        </form>

        <p>Already have an account? 
            <a href="login.php?redirect=<?= urlencode($redirect) ?>">Login</a>
        </p>
    </div>
</body>
</html>
