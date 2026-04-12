<?php
$host = "localhost";
$dbname = "movie-ticket-db";
$user = "root"; // adjust your DB username
$pass = "";     // adjust your DB password

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
