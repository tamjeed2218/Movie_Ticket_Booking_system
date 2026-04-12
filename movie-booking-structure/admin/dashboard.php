<?php
// admin/dashboard.php
include 'includes/auth.php';
include 'includes/configdb.php';
include 'includes/header.php';

// Totals
$total_movies   = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM movies"))['c'];
$total_users    = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM users"))['c'];
$total_bookings = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM bookings"))['c'];
$total_cinemas  = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM cinemas"))['c'];
$total_shows    = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM shows"))['c'];

// Recent Movies
$recent_movies = mysqli_query($conn, "SELECT title, genre FROM movies ORDER BY movie_id DESC LIMIT 5");

// Recent Bookings
$recent_bookings = mysqli_query($conn, "
    SELECT b.booking_id, u.name AS user_name, m.title AS movie_title, 
           c.name AS cinema_name, s.show_date, s.show_time, b.booking_date
    FROM bookings b
    JOIN users u ON u.user_id = b.user_id
    JOIN shows s ON s.show_id = b.show_id
    JOIN movies m ON m.movie_id = s.movie_id
    JOIN cinemas c ON c.cinema_id = s.cinema_id
    ORDER BY b.booking_date DESC LIMIT 5
");

// Booking Trends (12 months)
$trend_q = mysqli_query($conn, "
    SELECT DATE_FORMAT(booking_date, '%b %Y') AS month, COUNT(*) AS cnt
    FROM bookings
    GROUP BY month
    ORDER BY booking_date ASC
    LIMIT 12
");
$labels = []; $data = [];
while($row = mysqli_fetch_assoc($trend_q)) {
    $labels[] = $row['month'];
    $data[] = (int)$row['cnt'];
}
?>

<div class="container-fluid mt-4">
    <h2 class="text-center mb-4">🎬 Admin Dashboard</h2>

    <!-- KPI Cards -->
    <div class="row g-4">
        <div class="col-md-2">
            <div class="card shadow-sm p-3 text-center border-start border-primary border-4 bg-light">
                <h6 class="text-primary">Movies</h6>
                <h3 class="fw-bold"><?php echo $total_movies; ?></h3>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow-sm p-3 text-center border-start border-success border-4 bg-light">
                <h6 class="text-success">Users</h6>
                <h3 class="fw-bold"><?php echo $total_users; ?></h3>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow-sm p-3 text-center border-start border-warning border-4 bg-light">
                <h6 class="text-warning">Bookings</h6>
                <h3 class="fw-bold"><?php echo $total_bookings; ?></h3>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow-sm p-3 text-center border-start border-danger border-4 bg-light">
                <h6 class="text-danger">Cinemas</h6>
                <h3 class="fw-bold"><?php echo $total_cinemas; ?></h3>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow-sm p-3 text-center border-start border-info border-4 bg-light">
                <h6 class="text-info">Shows</h6>
                <h3 class="fw-bold"><?php echo $total_shows; ?></h3>
            </div>
        </div>
    </div>

    <!-- Charts + Recent Movies -->
    <div class="row mt-4">
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light fw-bold">
                    📈 Booking Trends
                </div>
                <div class="card-body">
                    <canvas id="bookingChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light fw-bold">
                    🎥 Recent Movies
                </div>
                <ul class="list-group list-group-flush">
                    <?php while($m = mysqli_fetch_assoc($recent_movies)) { ?>
                        <li class="list-group-item d-flex justify-content-between text-dark">
                            <span><?php echo $m['title']; ?></span>
                            <span class="badge bg-light"><?php echo $m['genre']; ?></span>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- Recent Bookings Table -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light fw-bold">🕒 Recent Bookings</div>
        <div class="card-body table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th><th>User</th><th>Movie</th>
                        <th>Cinema</th><th>Showtime</th><th>Booked On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($b = mysqli_fetch_assoc($recent_bookings)) { ?>
                        <tr>
                            <td>#<?php echo $b['booking_id']; ?></td>
                            <td><?php echo $b['user_name']; ?></td>
                            <td><?php echo $b['movie_title']; ?></td>
                            <td><?php echo $b['cinema_name']; ?></td>
                            <td><?php echo $b['show_date'].' '.$b['show_time']; ?></td>
                            <td><?php echo $b['booking_date']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row mt-4">
        <div class="col-md-3">
            <a href="modules/movies/list.php" class="btn btn-outline-primary w-100 shadow-sm">🎬 Manage Movies</a>
        </div>
        <div class="col-md-3">
            <a href="modules/cinemas/list.php" class="btn btn-outline-danger w-100 shadow-sm">🏢 Manage Cinemas</a>
        </div>
        <div class="col-md-3">
            <a href="modules/shows/list.php" class="btn btn-outline-warning w-100 shadow-sm">📅 Manage Shows</a>
        </div>
        <div class="col-md-3">
            <a href="modules/users/list.php" class="btn btn-outline-success w-100 shadow-sm">👤 Manage Users</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('bookingChart'), {
    type: 'line',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            label: 'Bookings',
            data: <?php echo json_encode($data); ?>,
            borderColor: '#007bff',
            backgroundColor: 'rgba(0,123,255,0.2)',
            tension: 0.3,
            fill: true,
            pointRadius: 4,
            pointBackgroundColor: '#007bff'
        }]
    }
});
</script>

<?php include 'includes/footer.php'; ?>
