<?php
session_start();
include('includes/configdb.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
$_SESSION['username'] = $user['name'];
            $_SESSION['role_id'] = $user['role_id'];

            if ($user['role_id'] == 2) {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../user/dashboard.php");
            }
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "No account found with that email.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login |TICKETREEL</title>
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
        <p>Login and book your favorite movies hassle-free!</p>
    </div>
    <div class="auth-right">
        <div class="form-container">
            <h2>Login</h2>
            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST">
                <div>
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                </div>
                <div>
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn-theme">Login</button>
            </form>
            <div class="extra-link">
                Don't have an account? <a href="register.php">Register</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
