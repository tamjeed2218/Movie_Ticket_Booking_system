<?php
include "auth.php";
requireLogin();
include "configdb.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$movie_id = intval($_GET['movie_id']);

if(isset($_POST['rating'])){
    $rating = intval($_POST['rating']);
    if($rating >= 1 && $rating <= 5){
        // Check if user already rated
        $check = mysqli_query($conn, "SELECT * FROM movie_ratings WHERE user_id=$user_id AND movie_id=$movie_id");
        if(mysqli_num_rows($check) > 0){
            mysqli_query($conn, "UPDATE movie_ratings SET rating=$rating WHERE user_id=$user_id AND movie_id=$movie_id");
        } else {
            mysqli_query($conn, "INSERT INTO movie_ratings (user_id, movie_id, rating) VALUES ($user_id, $movie_id, $rating)");
        }
        header("Location: ratings.php");
        exit;
    }
}
?>

<?php include "header.php"; ?>

<div class="container py-5" style="max-width: 500px;">
    <h3 style="color:#e50914; text-align:center; margin-bottom: 25px;">Rate Movie</h3>
    <form method="post">
        <div class="mb-3">
            <label for="rating" class="form-label" style="font-weight:bold; color:#fff;">Your Rating (1-5)</label>
            <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" required
                   style="background-color:#333; color:#fff; border:none; border-radius:5px; padding:10px;">
        </div>
        <button type="submit" class="btn-redd">Submit Rating</button>
        <a href="ratings.php" class="btn-red">Back</a>
    </form>
</div>

<style>
        .btn-redd {
        display: inline-block;
        width: 100%;
        padding: 12px;
        background-color: #e50914;
        color: #fff;
        text-align: center;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        border: none;
        cursor: pointer;
        transition: 0.3s ease;
        margin-bottom: 10px;
    }
    .btn-redd:hover {
        background-color: #b0060f;
    }.btn-red {
        display: inline-block;
        width: 100%;
        padding: 12px;
        background-color: #444;
        color: #fff;
        text-align: center;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        border: none;
        cursor: pointer;
        transition: 0.3s ease;
        margin-bottom: 10px;
    }
    .btn-red:hover {
        background-color: #666; color: white;
    }
    body {
        background-color: #141414;
        color: #fff;
        font-family: Arial, sans-serif;
    }
</style>

<?php include "footer.php"; ?>
