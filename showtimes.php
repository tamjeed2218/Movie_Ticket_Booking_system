<?php 
include "header.php";
include "navbar.php";
include "configdb.php";
?>

<section id="center" class="center_o pt-2 pb-2">
  <div class="container-xl">
    <div class="row center_o1">
      <div class="col-md-5">
        <div class="center_o1l">
          <h2 class="mb-0">Showtimes</h2>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="showtimes" class="pt-5 pb-5 text-light">
  <div class="container">

    <?php
    $sql = "SELECT m.movie_id, m.title, m.image_path, m.trailer_link,
                   s.show_id, s.cinema_id, s.show_date, s.show_time, s.seat_class, s.price
            FROM movies m
            LEFT JOIN shows s ON m.movie_id = s.movie_id
            WHERE m.status='released'
            ORDER BY m.title, s.show_date, s.show_time";
    $res = mysqli_query($conn, $sql);

    $movies = [];
    while($row = mysqli_fetch_assoc($res)) {
        $movies[$row['movie_id']]['title'] = $row['title'];
        $movies[$row['movie_id']]['image_path'] = $row['image_path'];
        $movies[$row['movie_id']]['trailer_link'] = $row['trailer_link'];
        $movies[$row['movie_id']]['showtimes'][] = $row;
    }

    if(count($movies) > 0):
        foreach($movies as $movie_id => $movie):
    ?>
    <div class="card mb-4 shadow card-theme">
      <div class="row g-0 align-items-center flex-column flex-md-row">
        <div class="col-md-2 text-center p-2">
          <div class="position-relative">
            <img src="<?= $movie['image_path'] ?>" class="img-fluid rounded movie-img" alt="<?= htmlspecialchars($movie['title']); ?>">
            <?php if($movie['trailer_link']): ?>
            <a href="<?= $movie['trailer_link']; ?>" target="_blank" class="trailer-icon">
              <i class="fa fa-youtube-play"></i>
            </a>
            <?php endif; ?>
          </div>
        </div>
        <div class="col-md-10">
          <div class="card-body p-2 p-md-3">
            <h5 class="card-title"><?= htmlspecialchars($movie['title']); ?></h5>
            <div class="table-responsive">
              <table class="table table-sm table-bordered table-theme mb-0">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Seat Class</th>
                    <th>Price</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($movie['showtimes'] as $s): ?>
                    <tr>
                      <td><?= date("d M Y", strtotime($s['show_date'])); ?></td>
                      <td><?= date("h:i A", strtotime($s['show_time'])); ?></td>
                      <td><?= htmlspecialchars($s['seat_class']); ?></td>
                      <td>Rs: <?= number_format($s['price'], 2); ?></td>
                     <td>
<?php
if (isset($_SESSION['user_id'])) {
    // Logged-in user → go directly to booking page
    $link = "book_ticket.php?show_id=" . $s['show_id'];
} else {
    // Visitor → redirect to login page and pass the intended booking page
    $link = "login.php?redirect=" . urlencode("book_ticket.php?show_id=" . $s['show_id']);
}
?>
<a href="<?= $link ?>" class="btn btn-action col_red_bg text-white">Book Now</a>
</td>

                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php
        endforeach;
    else:
        echo '<p class="text-center text-muted">No showtimes available at the moment.</p>';
    endif;
    ?>

  </div>
</section>

<?php include "footer.php"; ?>

<style>
.card-title {
    color: #e50914;  /* theme red */
    font-weight: 600;
    word-break: break-word;
}
.btn-action {
    background-color: #e50914; 
    border: none; 
    border-radius: 0;      
    padding: 8px 18px;     
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}
.btn-action:hover { 
    background-color: #b20710; 
    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
}

.trailer-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #e50914; 
    font-size: 1.8rem;
    opacity: 0.9;
}

.movie-img {
    max-width: 100%;
    height: auto;
}

.card-theme {
    background-color: #1f2124;  /* Dark background */
    color: #f8f9fa;             /* Light text */
    border: none;
    transition: transform 0.2s ease;
}
.card-theme:hover { transform: translateY(-4px); }

.table-theme thead { background-color: #1f2124; color: #f8f9fa; }
.table-theme tbody { background-color: #2c2f33; color: #f8f9fa; }
.table-theme tbody tr:hover { background-color: #3a3d42; }

@media (max-width: 991.98px) {
    .flex-column.flex-md-row { flex-direction: column !important; }
    .card-title { font-size: 1rem; }
    .trailer-icon { font-size: 1.5rem; }
}
@media (max-width: 575.98px) {
    .card-title { font-size: 0.95rem; }
    table th, table td { font-size: 0.75rem; padding: 5px 3px; }
    .trailer-icon { font-size: 1.2rem; }
    .btn-action { padding: 6px 12px; font-size: 0.8rem; }
}
</style>
