<?php
session_start();
include "configdb.php";

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to check if user is Admin
function isAdmin() {
    return (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 2);
}

// Redirect visitors who need to be logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

// Redirect non-admins from admin pages
function requireAdmin() {
    if (!isLoggedIn() || !isAdmin()) {
        header("Location: login.php");
        exit;
    }
}
?>
