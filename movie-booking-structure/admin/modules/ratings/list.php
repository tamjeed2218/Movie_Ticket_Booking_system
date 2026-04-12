<?php
include '../../includes/configdb.php';
include '../../includes/header.php';
?>

<div class="container mt-5">
    <h2>Cinemas Reviews & Ratings</h2>
    <a href="../../dashboard.php" class="btn btn-dark mb-3">Go Back</a>

    <?php
    // Fetch all user reviews with user and cinema names
    $reviews = $conn->query("
        SELECT ur.review_id, u.name AS user_name, c.name AS cinema_name, 
               ur.review_text, ur.rating
        FROM user_reviews ur
        JOIN users u ON ur.user_id = u.user_id
        JOIN cinemas c ON ur.cinema_id = c.cinema_id
        ORDER BY ur.review_id DESC
    ");
    ?>

    <table class="table table-striped table-bordered text-center align-middle">
        <thead class="bg-dark text-white">
            <tr>
                <th>Review ID</th>
                <th>User</th>
                <th>Cinema</th>
                <th>Review Text</th>
                <th>Rating</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $reviews->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['review_id'] ?></td>
                    <td><?= htmlspecialchars($row['user_name']) ?></td>
                    <td><?= htmlspecialchars($row['cinema_name']) ?></td>
                    <td><?= htmlspecialchars($row['review_text']) ?></td>
                    <td>
                        <?php
                        $rating = (int)$row['rating'];
                        for ($i = 1; $i <= 5; $i++) {
                            echo '<span style="font-size:1.2rem; color:' . ($i <= $rating ? 'gold' : '#ccc') . '">&#9733;</span>';
                        }
                        ?>
                    </td>
                    <td>
                        <a href="delete.php?id=<?= $row['review_id'] ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Are you sure you want to delete this review?');"
                           title="Delete Review">
                           <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <hr class="my-5">

    <h2>Movie Ratings</h2>

    <?php
    // Fetch average ratings per movie
    $movies = $conn->query("
        SELECT m.movie_id, m.title, AVG(r.rating) AS avg_rating, COUNT(r.rating_id) AS total_ratings
        FROM movies m
        LEFT JOIN movie_ratings r ON m.movie_id = r.movie_id
        GROUP BY m.movie_id
        ORDER BY avg_rating DESC
    ");
    ?>

    <table class="table table-striped table-bordered text-center align-middle">
        <thead class="bg-dark text-white">
            <tr>
                <th>Movie ID</th>
                <th>Title</th>
                <th>Rating</th>
                <th>Total Ratings</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $movies->fetch_assoc()) { 
                $avg = round($row['avg_rating']);
            ?>
                <tr>
                    <td><?= $row['movie_id'] ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td>
                        <?php
                        for ($i = 1; $i <= 5; $i++) {
                            echo '<span style="font-size:1.2rem; color:' . ($i <= $avg ? 'gold' : '#ccc') . '">&#9733;</span>';
                        }
                        ?>
                    </td>
                    <td><?= $row['total_ratings'] ?></td>
                    <td>
                        <a href="delete.php?id=<?= $row['movie_id'] ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Are you sure you want to delete all ratings for this movie?');"
                           title="Delete Ratings">
                           <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <a href="../../dashboard.php" class="btn btn-dark mt-3">Go Back</a>
</div>

<?php include '../../includes/footer.php'; ?>
