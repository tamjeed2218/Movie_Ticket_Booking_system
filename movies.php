<?php
session_start();
include "configdb.php";

$genres = ["Action", "Sci-Fi", "Animation", "Comedy"];
?>
<?php 
include "header.php";
include "navbar.php"; 
?>

<section id="trend" class="pt-4 pb-5">
  <div class="container">

    <?php foreach ($genres as $genre): 

      $sql = "SELECT m.movie_id, m.title, m.description, m.image_path, m.trailer_link,
                 IFNULL(ROUND(AVG(r.rating),1), 0) AS avg_rating,
                 COUNT(r.rating_id) AS total_reviews
              FROM movies m
              LEFT JOIN movie_ratings r ON m.movie_id = r.movie_id
              WHERE m.image_path != '' AND m.genre = '$genre'
              GROUP BY m.movie_id
              ORDER BY m.movie_id DESC";
      $res = mysqli_query($conn, $sql);

      $movies = [];
      while($row = mysqli_fetch_assoc($res)){
          $movies[] = $row;
      }

      if (count($movies) == 0) continue;

      $chunks = array_chunk($movies, 3);
      $carouselId = "carousel_" . strtolower(str_replace(' ', '_', $genre));
    ?>

    <div class="row mt-5">
      <div class="col-md-12">
        <h3 class="fw-bold mb-3"><i class="fa fa-film col_red me-2"></i> <?= htmlspecialchars($genre) ?> <span class="col_red">Movies</span></h3>
        <hr class="mb-4">
      </div>
    </div>

    <div class="row">
      <div id="<?= $carouselId ?>" class="carousel slide mb-5" data-bs-ride="carousel">

        <div class="carousel-indicators">
          <?php for($i = 0; $i < count($chunks); $i++): ?>
            <button type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide-to="<?= $i ?>" class="<?= $i==0 ? 'active' : ''; ?>"></button>
          <?php endfor; ?>
        </div>

        <div class="carousel-inner">
          <?php
          $active = "active";
          foreach ($chunks as $chunk) {
              echo '<div class="carousel-item '.$active.'"><div class="row g-4">';
              foreach ($chunk as $movie) {
                  // Fetch first show for this movie
                  $show_res = mysqli_query($conn, "SELECT show_id FROM shows WHERE movie_id={$movie['movie_id']} ORDER BY show_date, show_time LIMIT 1");
                  $show = mysqli_fetch_assoc($show_res);
                  $first_show_id = $show['show_id'] ?? 0;

                  // Decide the booking link
                  if (isset($_SESSION['user_id'])) {
                      $link = "book_ticket.php?show_id=" . $first_show_id;
                  } else {
                      $link = "login.php?redirect=" . urlencode("book_ticket.php?show_id=" . $first_show_id);
                  }
          ?>
            <div class="col-md-4 col-12">
              <div class="card shadow-sm border-0 h-100 custom-card-body">
                <div class="position-relative">
                  <a href="movie_details.php?id=<?= $movie['movie_id']; ?>">
                    <img src="<?= $movie['image_path'] ?>" class="card-img-top img-fluid" style="height:350px;object-fit:contain;background:#000;" alt="<?= htmlspecialchars($movie['title']); ?>">
                    <div class="overlay d-flex justify-content-center align-items-center">
                      <i class="fa fa-youtube-play text-white display-4"></i>
                    </div>
                  </a>
                </div>
                <div class="card-body text-center text-white">
                  <h5 class="card-title"><?= htmlspecialchars($movie['title']); ?></h5>
                  <p class="text-muted small"><?= substr($movie['description'], 0, 60) ?>...</p>
                  <div class="mb-2">
                    <?php 
                      $stars = round($movie['avg_rating']);
                      for ($i = 1; $i <= 5; $i++) {
                          echo $i <= $stars ? '<i class="fa fa-star text-warning"></i>' : '<i class="fa fa-star-o text-muted"></i>';
                      }
                    ?>
                    <small class="text-muted">(<?= $movie['total_reviews'] ?> reviews)</small>
                  </div>
                  <a href="movie_details.php?id=<?= $movie['movie_id']; ?>" class="btn btn-sm me-2 col_red_bg text-white">View Details</a>
                  <a href="<?= $link ?>" class="btn btn-sm col_red_bg text-white">Book Now</a>
                </div>
              </div>
            </div>
          <?php
              }
              echo '</div></div>';
              $active = "";
          }
          ?>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="next">
          <span class="carousel-control-next-icon"></span>
        </button>

      </div>
    </div>

    <?php endforeach; ?>

  </div>
</section>

<style>
.col_red_bg {
    background-color: #e50914;
    border: none;
}
.col_red_bg:hover {
    background-color: #b0060f;
}
.custom-card-body {
    background-color: #1f2124;
}
.overlay {
    position: absolute;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background: rgba(0,0,0,0.5);
    opacity: 0;
    transition: opacity 0.3s;
}
.position-relative:hover .overlay {
    opacity:1;
}
</style>

<?php include "footer.php"; ?>
