<?php 
include '../../includes/configdb.php';

// Handle form submission first
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title    = $_POST['title'] ?? '';
    $genre    = $_POST['genre'] ?? '';
    $duration = intval($_POST['duration'] ?? 0);
    $desc     = $_POST['description'] ?? '';
    $trailer  = $_POST['trailer_url'] ?? '';

    // Handle image
    $imgFile = basename($_FILES["image"]["name"]);
    $imgPath = "img/" . $imgFile;   // DB me sirf relative path save hoga (img/...)
    $uploadDir = "../../admin/img/"; 

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    move_uploaded_file($_FILES["image"]["tmp_name"], $uploadDir . $imgFile);

    // ✅ Insert including duration
    $stmt = $conn->prepare("INSERT INTO movies (title, genre, duration, description, image_path, trailer_link) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("ssisss", $title, $genre, $duration, $desc, $imgPath, $trailer);
    $stmt->execute();

    // Redirect immediately to prevent duplicate insert
    header("Location: list.php?msg=added");
    exit;
}

include '../../includes/header.php';
?>

<div class="container mt-5">
    <h2>Add New Movie</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Genre</label>
            <select name="genre" class="form-control" required>
                <option value="">Select Genre</option>
                <option value="Action">Action</option>
                <option value="Comedy">Comedy</option>
                <option value="Sci-Fi">Sci-Fi</option>
                <option value="Animation">Animation</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Duration (in minutes)</label>
            <input type="number" name="duration" class="form-control" min="30" max="300" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label>Image</label>
            <input type="file" name="image" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Trailer URL</label>
            <input type="url" name="trailer_url" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-danger">Add Movie</button>
        <a href="list.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>
