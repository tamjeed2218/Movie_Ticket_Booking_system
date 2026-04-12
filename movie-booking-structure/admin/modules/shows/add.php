<?php
// admin/modules/shows/add.php

include '../../includes/configdb.php';
include '../../includes/header.php';

// Fetch movies and cinemas for dropdowns
$movies = $conn->query("SELECT movie_id, title FROM movies ORDER BY title ASC")->fetch_all(MYSQLI_ASSOC);
$cinemas = $conn->query("SELECT cinema_id, name FROM cinemas ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movie_id = $_POST['movie_id'];
    $cinema_id = $_POST['cinema_id'];
    $show_date = $_POST['show_date'];
    $show_time = $_POST['show_time'];
    $seat_class = $_POST['seat_class'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("INSERT INTO shows (movie_id, cinema_id, show_date, show_time, seat_class, price) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssi", $movie_id, $cinema_id, $show_date, $show_time, $seat_class, $price);

    if ($stmt->execute()) {
        echo '<div class="alert alert-success">Show added successfully! <a href="list.php">Go back to list</a></div>';
    } else {
        echo '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
    }

    $stmt->close();
}
?>

<div class="container mt-5">
    <h2 class="text-danger">Add New Show</h2>
    <form method="POST" class="mt-4">
        <div class="mb-3">
            <label for="movie_id" class="form-label">Select Movie</label>
            <select name="movie_id" id="movie_id" class="form-select" required>
                <option value="">-- Select Movie --</option>
                <?php foreach($movies as $movie): ?>
                    <option value="<?= $movie['movie_id']; ?>"><?= htmlspecialchars($movie['title']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="cinema_id" class="form-label">Select Cinema</label>
            <select name="cinema_id" id="cinema_id" class="form-select" required>
                <option value="">-- Select Cinema --</option>
                <?php foreach($cinemas as $cinema): ?>
                    <option value="<?= $cinema['cinema_id']; ?>"><?= htmlspecialchars($cinema['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="show_date" class="form-label">Show Date</label>
            <input type="date" name="show_date" id="show_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="show_time" class="form-label">Show Time</label>
            <input type="time" name="show_time" id="show_time" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="seat_class" class="form-label">Seat Class</label>
            <select name="seat_class" id="seat_class" class="form-select" required>
                <option value="">-- Select Seat Class --</option>
                <option value="Regular">Regular</option>
                <option value="VIP">VIP</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price (PKR)</label>
            <input type="number" name="price" id="price" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Add Show</button>
        <a href="list.php" class="btn btn-secondary">Go Back</a>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>
