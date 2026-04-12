<?php
include '../../includes/configdb.php';
include '../../includes/header.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: list.php");
    exit;
}

$id = intval($_GET['id']);
$movie = $conn->query("SELECT * FROM movies WHERE movie_id=$id")->fetch_assoc();
?>

<div class="container mt-5">
    <h2>Movie Details</h2>
    <table class="table table-bordered">
        <tr><th>ID</th><td><?= $movie['movie_id'] ?></td></tr>
        <tr><th>Title</th><td><?= htmlspecialchars($movie['title']) ?></td></tr>
        <tr><th>Description</th><td><?= htmlspecialchars($movie['description']) ?></td></tr>
        <tr><th>Duration</th><td><?= htmlspecialchars($movie['duration']) ?> mins</td></tr>
        <tr><th>Genre</th><td><?= htmlspecialchars($movie['genre']) ?></td></tr>
        <tr><th>Image</th><td><img src="../../<?= $movie['image_path'] ?>" width="150"></td></tr>
        <tr><th>Trailer</th>
            <td>
                <a class="text-decoration-none text-danger fw-bold" href="<?= $movie['trailer_link'] ?>" target="_blank">Watch Trailer</a>
            </td>
        </tr>
    </table>
    <a href="list.php" class="btn btn-secondary">Back to List</a>
</div>

<?php include '../../includes/footer.php'; ?>
