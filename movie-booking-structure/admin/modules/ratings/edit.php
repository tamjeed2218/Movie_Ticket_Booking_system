<?php
include '../../includes/configdb.php';
include '../../includes/header.php';

if (!isset($_GET['id'])) { header("Location:list.php"); exit; }

$review_id = intval($_GET['id']);
$review = $conn->query("SELECT * FROM user_reviews WHERE review_id = $review_id")->fetch_assoc();
if (!$review) { header("Location:list.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id']);
    $cinema_id = intval($_POST['cinema_id']);
    $review_text = $conn->real_escape_string($_POST['review_text']);
    $rating = intval($_POST['rating']);

    $conn->query("
        UPDATE user_reviews
        SET user_id=$user_id, cinema_id=$cinema_id, review_text='$review_text', rating=$rating
        WHERE review_id=$review_id
    ");
    header("Location:list.php");
    exit;
}

$users = $conn->query("SELECT user_id, name FROM users");
$cinemas = $conn->query("SELECT cinema_id, name FROM cinemas");
?>

<div class="container mt-5">
    <h2>Edit Review</h2>
    <form method="post">
        <div class="form-group mb-3">
            <label>User</label>
            <select name="user_id" class="form-control" required>
                <?php while ($row = $users->fetch_assoc()) { ?>
                    <option value="<?= $row['user_id'] ?>" <?= $row['user_id']==$review['user_id']?'selected':'' ?>>
                        <?= htmlspecialchars($row['name']) ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group mb-3">
            <label>Cinema</label>
            <select name="cinema_id" class="form-control" required>
                <?php while ($row = $cinemas->fetch_assoc()) { ?>
                    <option value="<?= $row['cinema_id'] ?>" <?= $row['cinema_id']==$review['cinema_id']?'selected':'' ?>>
                        <?= htmlspecialchars($row['name']) ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group mb-3">
            <label>Review Text</label>
            <textarea name="review_text" class="form-control" required><?= htmlspecialchars($review['review_text']) ?></textarea>
        </div>
        <div class="form-group mb-3">
            <label>Rating (1–5)</label>
            <input type="number" name="rating" class="form-control" min="1" max="5" value="<?= $review['rating'] ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Review</button>
        <a href="list.php" class="btn btn-secondary">Go Back</a>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>
