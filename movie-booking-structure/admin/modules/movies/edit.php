<?php
include '../../includes/configdb.php';

// 1️⃣ GET ID check
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: list.php");
    exit;
}
$id = intval($_GET['id']);

// 2️⃣ Handle POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title    = $_POST['title'] ?? '';
    $desc     = $_POST['description'] ?? '';
    $trailer  = $_POST['trailer_link'] ?? '';
    $genre    = $_POST['genre'] ?? '';
    $duration = $_POST['duration'] ?? '';

    // Fetch current image path
    $movie = $conn->query("SELECT * FROM movies WHERE movie_id=$id")->fetch_assoc();
    $imgPath = $movie['image_path'];

    // Handle new image upload
    if (!empty($_FILES["image"]["name"])) {
        $imgFile   = basename($_FILES["image"]["name"]);
        $imgPath   = "img/" . $imgFile;
        $uploadDir = "../../admin/img/";

        if (!file_exists($uploadDir)) mkdir($uploadDir, 0755, true);

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $uploadDir . $imgFile)) {
            $imgPath = $movie['image_path']; // fallback
        }
    }

    // Update movie
    $stmt = $conn->prepare("UPDATE movies 
                            SET title=?, description=?, genre=?, duration=?, image_path=?, trailer_link=? 
                            WHERE movie_id=?");
    $stmt->bind_param("ssssssi", $title, $desc, $genre, $duration, $imgPath, $trailer, $id);
    $stmt->execute();

    header("Location: list.php");
    exit;
}

// 3️⃣ Include header
include '../../includes/header.php';

// 4️⃣ Fetch movie for display
$movie = $conn->query("SELECT * FROM movies WHERE movie_id=$id")->fetch_assoc();
?>

<div class="container mt-5">
    <h2>Edit Movie</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($movie['title']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Genre</label>
            <input type="text" name="genre" class="form-control" value="<?= htmlspecialchars($movie['genre']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Duration (e.g., 120 min or 2h 10m)</label>
            <input type="text" name="duration" class="form-control" value="<?= htmlspecialchars($movie['duration']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" required><?= htmlspecialchars($movie['description']) ?></textarea>
        </div>
        <div class="mb-3">
            <label>Current Image</label><br>
            <?php if (!empty($movie['image_path']) && file_exists("../../" . $movie['image_path'])): ?>
                <img src="../../<?= $movie['image_path'] ?>" width="100">
            <?php else: ?>
                <span class="text-danger">Image not available</span>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label>Change Image</label>
            <input type="file" name="image" class="form-control">
        </div>
        <div class="mb-3">
            <label>Trailer URL</label>
            <input type="url" name="trailer_link" class="form-control" value="<?= htmlspecialchars($movie['trailer_link'] ?? '') ?>" required>
        </div>
        <button type="submit" class="btn btn-danger">Update Movie</button>
        <a href="list.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>
