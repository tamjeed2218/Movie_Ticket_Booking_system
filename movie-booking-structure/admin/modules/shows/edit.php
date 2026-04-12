<?php
include '../../includes/configdb.php';

// 1️⃣ GET ID check
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: list.php");
    exit;
}
$id = intval($_GET['id']);

// 2️⃣ Handle POST (Update Show)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $movie_id   = $_POST['movie_id'] ?? '';
    $cinema_id  = $_POST['cinema_id'] ?? '';
    $show_date  = $_POST['show_date'] ?? '';
    $show_time  = $_POST['show_time'] ?? '';
    $seat_class = $_POST['seat_class'] ?? '';
    $price      = $_POST['price'] ?? '';

    $stmt = $conn->prepare("UPDATE shows 
                            SET movie_id=?, cinema_id=?, show_date=?, show_time=?, seat_class=?, price=? 
                            WHERE show_id=?");
    $stmt->bind_param("iisssii", $movie_id, $cinema_id, $show_date, $show_time, $seat_class, $price, $id);
    $stmt->execute();

    header("Location: list.php");
    exit;
}

// 3️⃣ Fetch movies and cinemas for dropdowns
$movies  = $conn->query("SELECT movie_id, title FROM movies ORDER BY title ASC")->fetch_all(MYSQLI_ASSOC);
$cinemas = $conn->query("SELECT cinema_id, name FROM cinemas ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);

// 4️⃣ Fetch show for display
$show = $conn->query("SELECT * FROM shows WHERE show_id=$id")->fetch_assoc();
if (!$show) {
    echo '<div class="alert alert-danger">Show not found! <a href="list.php">Go back</a></div>';
    exit;
}

// 5️⃣ Include header
include '../../includes/header.php';
?>

<div class="container mt-5">
    <h2>Edit Show</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Select Movie</label>
            <select name="movie_id" class="form-control" required>
                <option value="">-- Select Movie --</option>
                <?php foreach($movies as $movie): ?>
                    <option value="<?= $movie['movie_id']; ?>" <?= $show['movie_id'] == $movie['movie_id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($movie['title']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Select Cinema</label>
            <select name="cinema_id" class="form-control" required>
                <option value="">-- Select Cinema --</option>
                <?php foreach($cinemas as $cinema): ?>
                    <option value="<?= $cinema['cinema_id']; ?>" <?= $show['cinema_id'] == $cinema['cinema_id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($cinema['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Show Date</label>
            <input type="date" name="show_date" class="form-control" value="<?= htmlspecialchars($show['show_date']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Show Time</label>
            <input type="time" name="show_time" class="form-control" value="<?= htmlspecialchars($show['show_time']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Seat Class</label>
            <select name="seat_class" class="form-control" required>
                <option value="">-- Select Seat Class --</option>
                <option value="Gold" <?= $show['seat_class'] == 'Gold' ? 'selected' : ''; ?>>Gold</option>
                <option value="Platinum" <?= $show['seat_class'] == 'Platinum' ? 'selected' : ''; ?>>Platinum</option>
                <option value="Box" <?= $show['seat_class'] == 'Box' ? 'selected' : ''; ?>>Box</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Price (PKR)</label>
            <input type="number" name="price" class="form-control" value="<?= htmlspecialchars($show['price']) ?>" required>
        </div>

        <button type="submit" class="btn btn-danger">Update Show</button>
        <a href="list.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>
