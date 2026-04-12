<?php
include "auth.php";
requireLogin();
include "configdb.php"; // DB connection

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$review_text = '';
$rating = 0;
$cinema_id = 0;
$message = '';

// Handle POST submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review_text = isset($_POST['review_text']) ? trim($_POST['review_text']) : '';
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $cinema_id = isset($_POST['cinema_id']) ? intval($_POST['cinema_id']) : 0;

    if ($review_text && $rating && $cinema_id) {
        $stmt = mysqli_prepare($conn, "INSERT INTO user_reviews (user_id, cinema_id, review_text, rating) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "iisi", $user_id, $cinema_id, $review_text, $rating);
        if (mysqli_stmt_execute($stmt)) {
            // Redirect to ratings page after successful submission
            header("Location: ratings.php?cinema_id=$cinema_id&success=1");
            exit;
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } else {
        $message = "Please fill in all required fields.";
    }
}

// Optional: get cinema_id from GET
if (isset($_GET['cinema_id'])) {
    $cinema_id = intval($_GET['cinema_id']);
}
?>
<?php include "header.php"; ?>

<div class="review-page">
    <div class="review-container">
        <h2>Write a Review</h2>

        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form action="write_review.php" method="post">
            <input type="hidden" name="cinema_id" value="<?php echo htmlspecialchars($cinema_id); ?>">

            <label for="review_text">Your Review:</label>
            <textarea id="review_text" name="review_text" rows="5" required><?php echo htmlspecialchars($review_text); ?></textarea>

            <label for="rating">Rating:</label>
            <select name="rating" id="rating" required>
                <option value="">Select Rating</option>
                <option value="5" <?php if ($rating == 5) echo 'selected'; ?>>5 - Excellent</option>
                <option value="4" <?php if ($rating == 4) echo 'selected'; ?>>4 - Very Good</option>
                <option value="3" <?php if ($rating == 3) echo 'selected'; ?>>3 - Good</option>
                <option value="2" <?php if ($rating == 2) echo 'selected'; ?>>2 - Fair</option>
                <option value="1" <?php if ($rating == 1) echo 'selected'; ?>>1 - Poor</option>
            </select>

            <button type="submit" class="btn-submit">Submit Review</button>
        </form>

        <!-- Go Back Button -->
        <a href="ratings.php?cinema_id=<?php echo htmlspecialchars($cinema_id); ?>" class="btn-back">Go Back</a>
    </div>
</div>

<style>
    .review-page {
        min-height: 80vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #141414;
        padding: 50px 15px;
    }
    .review-container {
        width: 100%;
        max-width: 600px;
        background-color: #1e1e1e;
        padding: 30px 25px;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0,0,0,0.7);
        text-align: center;
    }
    .review-container h2 {
        color: #e50914;
        margin-bottom: 25px;
        font-size: 28px;
    }
    .review-container label {
        display: block;
        margin-top: 15px;
        font-weight: bold;
        color: #fff;
    }
    .review-container textarea,
    .review-container select {
        width: 100%;
        padding: 12px;
        border-radius: 5px;
        border: none;
        margin-top: 5px;
        margin-bottom: 15px;
        background-color: #333;
        color: #fff;
        font-size: 15px;
        resize: vertical;
    }
    .btn-submit {
        width: 100%;
        padding: 14px;
        background-color: #e50914;
        color: #fff;
        font-size: 16px;
        font-weight: bold;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s ease;
        margin-bottom: 10px;
    }
    .btn-submit:hover {
        background-color: #b0060f;
    }
    .btn-back {
                width: 100%;
display: inline-block;
        padding: 12px 25px;
        background-color: #444;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        transition: 0.3s ease;
    }
    .btn-back:hover {
        background-color: #666; color: white;
    }
    .message {
        text-align: center;
        margin-bottom: 15px;
        color: #ffdd57;
        font-weight: bold;
    }
</style>

<?php include "footer.php"; ?>
