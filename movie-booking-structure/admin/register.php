<?php
session_start();
include('includes/configdb.php');

$error = ''; $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role_id = 1; // normal user

    $checkEmail = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($checkEmail) > 0) {
        $error = "Email already registered.";
    } else {
        $query = "INSERT INTO users (username, email, password, role_id) VALUES ('$username','$email','$password','$role_id')";
        if (mysqli_query($conn, $query)) {
            $_SESSION['user_id'] = mysqli_insert_id($conn);
            $_SESSION['username'] = $username;
            $_SESSION['role_id'] = $role_id;
            header("Location: ../user/dashboard.php");
            exit;
        } else { $error = "Error: " . mysqli_error($conn); }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | TICKETREEL</title>
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">  
    <style>
        body { margin:0; font-family:'Segoe UI',sans-serif; display:flex; height:100vh; background:#121212; }
        .auth-wrapper { display:flex; width:100%; }
        .auth-left {
            flex:1; background:linear-gradient(135deg,#e63946,#000); color:#fff;
            display:flex; flex-direction:column; align-items:center; justify-content:center;
            padding:40px; text-align:center;
        }
        .auth-left h1 { font-size:42px; font-weight:700; margin-bottom:20px; }
        .auth-left p { font-size:18px; opacity:0.9; }
        .auth-right { flex:1; display:flex; align-items:center; justify-content:center; background:#f9f9f9; padding:40px; }
        .form-container {
            width:100%; max-width:400px; background:#fff; padding:35px;
            border-radius:12px; box-shadow:0px 8px 25px rgba(0,0,0,0.2);
        }
        .form-container h2 { color:#e63946; font-weight:700; margin-bottom:25px; text-align:center; }
        label { font-weight:500; color:#000; margin-bottom:6px; display:block; }
        .form-control {
            width:100%; padding:12px; border-radius:6px; border:1px solid #ddd;
            background:#fff; color:#000; margin-bottom:18px;
        }
        .form-control:focus { border-color:#e63946; box-shadow:0 0 0 0.2rem rgba(230,57,70,0.25); }
        .btn-theme { background:#e63946; color:#fff; font-weight:600; border:none; border-radius:6px; padding:12px; width:100%; }
        .btn-theme:hover { background:#c72c38; }
        .alert { font-size:14px; border-radius:6px; }
        .extra-link { text-align:center; margin-top:15px; font-size:14px; color:#555; }
        .extra-link a { color:#e63946; font-weight:600; text-decoration:none; }
        .extra-link a:hover { text-decoration:underline; }
        @media(max-width:768px){ .auth-left{display:none;} .auth-right{flex:1;background:#fff;} }
    </style>
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-left">
        <h1>🎬 TICKETREEL</h1>
        <p>Create your account and start booking today!</p>
    </div>
    <div class="auth-right">
        <div class="form-container">
            <h2>Register</h2>
            <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <?php if ($success) echo "<div class='alert alert-success'>$success</div>"; ?>
            <form method="POST">
                <div>
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Enter username" required>
                </div>
                <div>
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter email" required>
                </div>
                <div>
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                </div>
                <button type="submit" class="btn-theme">Register</button>
            </form>
            <div class="extra-link">
                Already have an account? <a href="login.php">Login</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
