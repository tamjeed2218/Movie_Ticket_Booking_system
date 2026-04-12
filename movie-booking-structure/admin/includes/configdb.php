<?php
// configdb.php

$host = "localhost";      // usually localhost
$dbname = "movie-ticket-db"; // your database name
$username = "root";       // database username
$password = "";           // database password (empty for XAMPP)

$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
