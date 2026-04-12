<?php
include("../../includes/configdb.php");
include("../../includes/header.php");

// Check if show_id is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: list.php");
    exit;
}

$show_id = intval($_GET['id']);

//  Correct column name (m.title not m.movie_title)
$sql = "SELECT 
            s.*, 
            m.title AS movie_title, 
            c.name AS cinema_name
        FROM shows s
        JOIN movies m ON s.movie_id = m.movie_id
        JOIN cinemas c ON s.cinema_id = c.cinema_id
        WHERE s.show_id = $show_id";

$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    echo '<div class="alert alert-danger">Show not found. <a href="list.php">Go back</a></div>';
    include("../../includes/footer.php");
    exit;
}

$show = mysqli_fetch_assoc($result);
?>

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h3>Show Details</h3>
        </div>
        <div class="card-body">
            <table class="table table-borderless">
                <tr><th>Movie</th><td><?= htmlspecialchars($show['movie_title']) ?></td></tr>
                <tr><th>Cinema</th><td><?= htmlspecialchars($show['cinema_name']) ?></td></tr>
                <tr><th>Show Date</th><td><?= $show['show_date'] ?></td></tr>
                <tr><th>Show Time</th><td><?= date('h:i A', strtotime($show['show_time'])) ?></td></tr>
                <tr><th>Seat Class</th><td><?= htmlspecialchars($show['seat_class']) ?></td></tr>
                <tr><th>Price</th><td><?= $show['price'] ?> PKR</td></tr>
            </table>
            <a href="list.php" class="btn btn-danger mt-3">Back to List</a>
        </div>
    </div>
</div>

<?php include("../../includes/footer.php"); ?>
