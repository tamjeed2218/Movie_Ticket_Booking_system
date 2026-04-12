<?php 
include "header.php";
include "navbar.php";
include "configdb.php";

$movie_id = intval($_GET['id'] ?? 0);

// Fetch movie details
$movie_sql = "SELECT * FROM movies WHERE movie_id=$movie_id LIMIT 1";
$movie_res = mysqli_query($conn, $movie_sql);
$movie = mysqli_fetch_assoc($movie_res);

if(!$movie){
    echo '<p class="text-center text-light py-5">Movie not found.</p>';
    include "footer.php";
    exit;
}

// Fetch upcoming shows for this movie
$show_sql = "SELECT s.show_id, c.name AS cinema_name, s.show_date, s.show_time
             FROM shows s
             JOIN cinemas c ON s.cinema_id=c.cinema_id
             WHERE s.movie_id=$movie_id AND s.show_date >= CURDATE()
             ORDER BY s.show_date, s.show_time";
$show_res = mysqli_query($conn, $show_sql);
$showtimes = [];
while($row = mysqli_fetch_assoc($show_res)){
    $showtimes[] = $row;
}
?>

<section class="pt-4 pb-4 bg-dark text-light">
  <div class="container">
    <div class="row">
      <!-- Movie Poster -->
      <div class="col-md-4 mb-3 position-relative">
        <img src="<?= htmlspecialchars($movie['image_path']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>" class="img-fluid rounded shadow" style="object-fit:cover; width:100%; height:100%;">
        
        <?php if($movie['trailer_link']): ?>
        <a href="<?= $movie['trailer_link'] ?>" target="_blank" class="trailer-icon">
            <i class="fa fa-youtube-play fa-2x"></i>
        </a>
        <?php endif; ?>
      </div>

      <!-- Movie Info -->
      <div class="col-md-8">
        <h2 class="movie-title"><?= htmlspecialchars($movie['title']); ?></h2>
        <p><strong>Genre:</strong> <?= htmlspecialchars($movie['genre']); ?></p>
        <p><strong>Duration:</strong> <?= htmlspecialchars($movie['duration']); ?> mins</p>
        <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($movie['description'])); ?></p>

        <h5 class="mt-4">Upcoming Shows</h5>
        <?php if(count($showtimes) > 0): ?>
        <ul class="list-group list-group-dark mb-3">
          <?php foreach($showtimes as $s): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <?= htmlspecialchars($s['cinema_name']); ?> - <?= date("d M Y", strtotime($s['show_date'])); ?> | <?= htmlspecialchars($s['show_time']); ?>
            <a href="book_ticket.php?show_id=<?= $s['show_id'] ?>" class="btn btn-sm book-btn">Book Now</a>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php else: ?>
          <p>No upcoming shows available.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<style>
/* Background */
.bg-dark { background-color: #0b0b0b !important; }

/* Trailer icon */
.trailer-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: rgba(0,0,0,0.6);
    border-radius: 50%;
    padding: 20px;
    color: #e50914;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s ease, background-color 0.3s ease, box-shadow 0.3s ease;
    text-decoration: none;
    z-index: 2;
}
.trailer-icon:hover {
    transform: translate(-50%, -50%) scale(1.2);
    background-color: rgba(0,0,0,0.8);
    box-shadow: 0 0 15px #e50914;
}

/* Movie title */
.movie-title {
    color: #e50914 !important;
}

/* Book Now button */
.book-btn {
    background-color: #e50914 !important;
    border-color: #e50914 !important;
    color: #fff !important;
    transition: background-color 0.3s ease, transform 0.3s ease;
}
.book-btn:hover {
    background-color: #b00610 !important;
    transform: scale(1.05);
}

/* Show list items */
.list-group-dark .list-group-item {
    background-color:#1f2124; 
    color:#fff; 
    border:none; 
    margin-bottom:5px; 
    transition: background-color 0.3s ease;
}
.list-group-dark .list-group-item:hover {
    background-color:#2c2f33; 
}
.list-group-dark .list-group-item a { text-decoration:none; }
</style>

<?php include "footer.php"; ?>
