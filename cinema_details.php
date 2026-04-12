<?php 
include "configdb.php";
include "auth.php";

if(!isset($_GET['id'])){
    header("Location: cinemas.php");
    exit();
}

$cinema_id = intval($_GET['id']);
$sql = "SELECT * FROM cinemas WHERE cinema_id = $cinema_id";
$res = mysqli_query($conn, $sql);

if(mysqli_num_rows($res) == 0){
    echo "<h2 class='text-center text-white mt-5'>Cinema not found!</h2>";
    exit();
}

$cinema = mysqli_fetch_assoc($res);

// Fetch shows for this cinema
$shows_sql = "SELECT s.show_id, m.title AS movie_title, m.image_path AS movie_image, 
                     s.show_date, s.show_time, s.seat_class, s.price
              FROM shows s
              JOIN movies m ON s.movie_id = m.movie_id
              WHERE s.cinema_id = $cinema_id
              ORDER BY s.show_date, s.show_time";
$shows_res = mysqli_query($conn, $shows_sql);

$shows = [];
while($s = mysqli_fetch_assoc($shows_res)) {
    $shows[$s['movie_title']][] = $s;
}
?>
<?php include "header.php"; include "navbar.php";?>

<!-- Hero -->
<section class="cinema-hero position-relative text-white">
    <div class="overlay"></div>
    <div class="container text-center hero-content position-relative">
        <h1 class="fw-bold mb-2"><?= htmlspecialchars($cinema['name']); ?></h1>
        <p class="lead mb-3"><?= htmlspecialchars($cinema['location']); ?></p>
    </div>
</section>

<!-- Cinema Map -->
<section class="cinema-map py-5">
    <div class="container">
        <div class="card card-theme mb-4">
            <div class="card-body">
                <h4 class="card-title text-danger"><i class="fa fa-map me-2"></i>Location Map</h4>
                <div class="ratio ratio-16x9">
                    <iframe src="https://www.google.com/maps?q=<?= urlencode($cinema['location']); ?>&output=embed" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Available Shows -->
<section class="cinema-shows py-5">
    <div class="container">
        <?php if(count($shows) > 0): ?>
            <?php foreach($shows as $movie_title => $movie_shows): ?>
                <div class="card card-theme mb-4 shadow-sm">
                    <div class="row g-0 align-items-center flex-column flex-md-row">
                        <div class="col-md-3 text-center p-2">
                            <img src="<?= htmlspecialchars($movie_shows[0]['movie_image']); ?>" class="img-fluid rounded" style="height:120px; object-fit:cover;" alt="<?= htmlspecialchars($movie_title); ?>">
                        </div>
                        <div class="col-md-9">
                            <div class="card-body p-2 p-md-3">
                                <h5 class="card-title"><?= htmlspecialchars($movie_title); ?></h5>
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
                                            <?php foreach($movie_shows as $s): ?>
                                            <tr>
                                                <td><?= date("d M Y", strtotime($s['show_date'])); ?></td>
                                                <td><?= date("h:i A", strtotime($s['show_time'])); ?></td>
                                                <td><?= htmlspecialchars($s['seat_class']); ?></td>
                                                <td>Rs: <?= number_format($s['price'], 2); ?></td>
                                                <td>
                                                    <a href="book_ticket.php?show_id=<?= $s['show_id']; ?>" class="btn btn-action col_red_bg text-white">Book Now</a>
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
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-muted">No shows available for this cinema currently.</p>
        <?php endif; ?>
    </div>
</section>

<?php include "footer.php"; ?>

<style>
/* Hero Banner */
.cinema-hero {
    background: url('<?= htmlspecialchars($cinema['image_path']); ?>') center/cover no-repeat;
    height: 350px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    position: relative;
}
.cinema-hero .overlay {
    position: absolute;
    top:0; left:0;
    width:100%; height:100%;
    background: rgba(0,0,0,0.6);
}
.hero-content h1 {
    font-size: 2.8rem;
    color: #e50914; /* Theme red */
    animation: fadeInDown 1s ease;
}
.hero-content p {
    font-size: 1.2rem;
    color: #fff;
    animation: fadeInUp 1s ease;
}

/* Animations */
@keyframes fadeInDown {0% {opacity:0; transform: translateY(-30px);} 100% {opacity:1; transform: translateY(0);} }
@keyframes fadeInUp {0% {opacity:0; transform: translateY(30px);} 100% {opacity:1; transform: translateY(0);} }

/* Cards */
.card-theme {
    background-color: #1f2124; 
    color: #f8f9fa; 
    border: none;
    transition: transform 0.2s ease;
}
.card-theme:hover { transform: translateY(-4px); }
.card-title { color: #e50914; font-weight: 600; }

/* Table */
.table-theme thead { background-color: #1f2124; color: #f8f9fa; }
.table-theme tbody { background-color: #2c2f33; color: #f8f9fa; }
.table-theme tbody tr:hover { background-color: #3a3d42; }

/* Buttons */
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

/* Map */
.cinema-map .ratio { height: 400px; border-radius: 8px; overflow: hidden; }

/* Responsive */
@media (max-width: 991.98px) { .flex-column.flex-md-row { flex-direction: column !important; } }
@media (max-width: 575.98px) { table th, table td { font-size: 0.8rem; padding: 6px 4px; } }
</style>
