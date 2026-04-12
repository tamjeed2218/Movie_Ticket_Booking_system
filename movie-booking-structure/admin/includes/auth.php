<?php
session_start();

// If not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// If we require admin only
if (isset($require_admin) && $require_admin && $_SESSION['role_id'] != 2) {
    header("Location: login.php");
    exit();
}
