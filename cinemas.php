<?php
include "configdb.php";
?>
<?php include "header.php"; include "navbar.php";?>

<!-- Hero Banner -->
<section class="cinema-hero position-relative text-white">
    <div class="overlay"></div>
    <div class="container text-center hero-content position-relative">
        <h1 class="fw-bold mb-2"><i class="fa fa-building me-2"></i> Our Cinemas</h1>
        <p class="lead mb-3">Find your nearest cinema and enjoy your favorite movies in style.</p>
    </div>
</section>

<!-- Cinema Cards -->
<section class="cinema-list py-5">
    <div class="container">
        <div class="row g-4">
            <?php
            $sql = "SELECT * FROM cinemas ORDER BY cinema_id DESC";
            $res = mysqli_query($conn, $sql);
            while($cinema = mysqli_fetch_assoc($res)):
            ?>
            <div class="col-md-4 col-12">
                <div class="card shadow-sm border-0 h-100 bg-dark text-white cinema-card">
                    <img src="<?= htmlspecialchars($cinema['image_path']); ?>" 
                         class="card-img-top" 
                         alt="<?= htmlspecialchars($cinema['name']); ?>" 
                         style="height:200px;object-fit:cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?= htmlspecialchars($cinema['name']); ?></h5>
                        <p class="small">
                            <i class="fa fa-map-marker me-2 text-danger"></i>
                            <a href="https://www.google.com/maps/search/<?= urlencode($cinema['location']); ?>" 
                               target="_blank" 
                               class="text-white text-decoration-none">
                                <?= htmlspecialchars($cinema['location']); ?>
                            </a>
                        </p>
                        <a href="cinema_details.php?id=<?= $cinema['cinema_id']; ?>" class="btn btn-sm col_red_bg text-white me-2">View Details</a>
                        <?php
if (session_status() === PHP_SESSION_NONE) session_start();

$cinema_id = $cinema['cinema_id'];
if (isset($_SESSION['user_id'])) {
    $link = "book_ticket.php?cinema_id=$cinema_id";
} else {
    $link = "login.php?redirect=" . urlencode("book_ticket.php?cinema_id=$cinema_id");
}
?>
<a href="<?= $link ?>" class="btn btn-sm col_red_bg text-white me-2">Book Now</a>

                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<?php include "footer.php"; ?>

<style>
/* Hero Banner */
.hero-content h1 {
    font-size: 2.8rem;
    color: #e50914; /* Theme red */
    animation: fadeInDown 1s ease;
}
.cinema-hero {
    background: url('img/cinema_banner.jpg') center/cover no-repeat;
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
.hero-content p {
    font-size: 1.2rem;
    animation: fadeInUp 1s ease;
}

/* Card Hover */
.cinema-card {
    transition: transform 0.3s ease;
    border-radius: 10px;
}
.cinema-card:hover { transform: translateY(-5px); }

/* Buttons */
.col_red_bg { background-color: #e50914; border: none; }
.col_red_bg:hover { background-color: #b20710; }

/* Animations */
@keyframes fadeInDown {0% {opacity:0; transform: translateY(-30px);} 100% {opacity:1; transform: translateY(0);} }
@keyframes fadeInUp {0% {opacity:0; transform: translateY(30px);} 100% {opacity:1; transform: translateY(0);} }

@media(max-width:768px){
    .hero-content h1 { font-size:2rem; }
    .hero-content p { font-size:1rem; }
}
</style>
