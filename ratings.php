<?php
include "auth.php";
requireLogin();
include "configdb.php"; // DB connection

$user_logged_in = isset($_SESSION['user_id']); // check if user is logged in

// Fetch movies with their average rating
$movies = mysqli_query($conn, "
    SELECT m.movie_id, m.title, m.genre, m.image_path, m.trailer_link,
           AVG(r.rating) AS avg_rating
    FROM movies m
    LEFT JOIN movie_ratings r ON m.movie_id = r.movie_id
    GROUP BY m.movie_id
    ORDER BY m.title ASC
");

// Fetch cinemas with their reviews and average rating
$cinemas = mysqli_query($conn, "
    SELECT c.cinema_id, c.name AS cinema_name, AVG(ur.rating) AS avg_rating, COUNT(ur.review_id) AS total_reviews
    FROM cinemas c
    LEFT JOIN user_reviews ur ON c.cinema_id = ur.cinema_id
    GROUP BY c.cinema_id
    ORDER BY avg_rating DESC
");

function getRatingLabel($rating) {
    if ($rating == 5) return "Excellent";
    elseif ($rating >= 4) return "Very Good";
    elseif ($rating >= 3) return "Good";
    elseif ($rating >= 2) return "Fair";
    elseif ($rating >= 1) return "Poor";
    else return "Not Rated";
}

function displayStars($avg) {
    $fullStars = floor($avg);
    $halfStar = ($avg - $fullStars >= 0.5) ? 1 : 0;
    $emptyStars = 5 - $fullStars - $halfStar;

    $html = "";
    for ($i = 0; $i < $fullStars; $i++) $html .= '<i class="fa fa-star" style="color:#ffc107;"></i>';
    if ($halfStar) $html .= '<i class="fa fa-star-half-alt" style="color:#ffc107;"></i>';
    for ($i = 0; $i < $emptyStars; $i++) $html .= '<i class="far fa-star" style="color:#ffc107;"></i>';

    return $html;
}
?>

<?php include "header.php"; include "navbar.php"; ?>

<section id="cinema-ratings" class="py-5" style="background-color:#1f2124; color:#f8f9fa;">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold display-6" style="color:#e50914;">Cinema Reviews & Ratings</h2>
            <p class="lead text-light">See what other users think about your favorite cinemas!</p>
        </div>
        <div class="row g-4">
            <?php while($cinema = mysqli_fetch_assoc($cinemas)) {
                $avg = round($cinema['avg_rating'], 1);
                $label = ($avg > 0) ? getRatingLabel(round($avg)) : "Not Rated";
            ?>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow movie-card" style="background-color:#2a2c2f;">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold"><?php echo htmlspecialchars($cinema['cinema_name']); ?></h5>
                        <div class="mb-2 rating">
                            <?php echo displayStars($avg); ?>
                            <span class="ms-2 fw-bold" style="color:#e50914;"><?php echo $label; ?></span>
                        </div>
                        <p class="text-muted"><?php echo $cinema['total_reviews'] ?> Reviews</p>

                        <?php if($user_logged_in): ?>
                            <a href="write_review.php?cinema_id=<?php echo $cinema['cinema_id']; ?>" class="btn btn-sm btn-outline-light me-2">Write Review</a>
                        <?php else: ?>
                            <small class="text-muted">Login to write a review or rate</small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>

<section id="movie-ratings" class="py-5" style="background-color:#121212; color:#f8f9fa;">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold display-6" style="color:#e50914;">Movie Ratings</h2>
            <p class="lead text-light">Check the user ratings for your favorite movies!</p>
        </div>
        <div class="row g-4">
            <?php while($movie = mysqli_fetch_assoc($movies)) { 
                $avg = round($movie['avg_rating'], 1);
                $label = ($avg > 0) ? getRatingLabel(round($avg)) : "Not Rated";
            ?>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow movie-card" style="background-color:#1f2124; color:#f8f9fa;">
                    <div class="position-relative overflow-hidden">
                        
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold"><?php echo $movie['title']; ?></h5>
                        <p class="text-muted mb-2"><?php echo $movie['genre']; ?></p>
                        <div class="mb-2 rating">
                            <?php echo displayStars($avg); ?>
                            <span class="ms-2 fw-bold" style="color:#e50914;"><?php echo $label; ?></span>
                        </div>

                        <?php if($user_logged_in): ?>
                            
                            <a href="rate_movie.php?movie_id=<?php echo $movie['movie_id']; ?>" class="btn btn-sm btn-warning">Rate</a>
                        <?php else: ?>
                            <small class="text-muted">Login to write a review or rate</small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>

<style>
.movie-card { transition: transform 0.3s, box-shadow 0.3s; border-radius: 10px; }
.movie-card:hover { transform: translateY(-6px); box-shadow: 0 10px 25px rgba(0,0,0,0.7); }

.rating i { font-size: 1rem; }

.trailer-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #e50914;
    font-size: 1.5rem;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

@media(max-width:768px) {
    .card-body h5 { font-size: 1rem; }
    .trailer-icon { font-size: 1.3rem; }
}/* Make heading color variable for easy reuse */
:root {
    --heading-red: #e50914;
}

/* Rate buttons */
.btn-warning {
    background-color: var(--heading-red) !important; /* same as heading */
    border-color: var(--heading-red) !important;
    color: #fff !important;
    transition: 0.3s;
}

.btn-warning:hover {
    background-color: #b00610 !important; /* slightly darker on hover */
    border-color: #b00610 !important;
}

/* Outline buttons (like Write Review) */
.btn-outline-light {
    color: var(--heading-red) !important; 
    border-color: var(--heading-red) !important;
}

.btn-outline-light:hover {
    background-color: var(--heading-red) !important;
    color: #fff !important;
    border-color: var(--heading-red) !important;
}

</style>

<?php include "footer.php"; ?>
