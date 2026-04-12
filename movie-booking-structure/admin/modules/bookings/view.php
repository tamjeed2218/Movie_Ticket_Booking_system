<?php
include '../../includes/configdb.php';
include '../../includes/header.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: list.php");
    exit;
}

$booking_id = intval($_GET['id']);

// ✅ Join bookings with booking_details, users, shows, movies, cinemas
$booking = $conn->query("
    SELECT b.booking_id, b.booking_date,
           u.name AS user_name, 
           m.title AS movie_title, 
           c.name AS cinema_name, 
           s.show_date, s.show_time, s.seat_class,
           bd.seat_count, bd.user_age, bd.seat_price
    FROM bookings b
    JOIN users u ON b.user_id = u.user_id
    JOIN shows s ON b.show_id = s.show_id
    JOIN movies m ON s.movie_id = m.movie_id
    JOIN cinemas c ON s.cinema_id = c.cinema_id
    JOIN booking_details bd ON bd.booking_id = b.booking_id
    WHERE b.booking_id = $booking_id
")->fetch_assoc();

if (!$booking) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Booking not found.</div></div>";
    include '../../includes/footer.php';
    exit;
}

$total_price = $booking['seat_count'] * $booking['seat_price'];
?>

<div class="container mt-5">
    <h2 class="text-danger">Booking Details</h2>
    <table class="table table-bordered">
        <tr><th>Booking ID</th><td><?= $booking['booking_id'] ?></td></tr>
        <tr><th>User</th><td><?= htmlspecialchars($booking['user_name']) ?></td></tr>
        <tr><th>Movie</th><td><?= htmlspecialchars($booking['movie_title']) ?></td></tr>
        <tr><th>Cinema</th><td><?= htmlspecialchars($booking['cinema_name']) ?></td></tr>
        <tr>
            <th>Show</th>
            <td>
                <?= date('Y-m-d', strtotime($booking['show_date'])) ?><br>
                <?= date('h:i A', strtotime($booking['show_time'])) ?><br>
                <span class="badge bg-info"><?= htmlspecialchars($booking['seat_class']) ?></span>
            </td>
        </tr>
        <tr><th>Seats</th><td><?= $booking['seat_count'] ?></td></tr>
        <tr><th>User Age</th><td><?= $booking['user_age'] ?></td></tr>
        <tr class="table-warning"><th>Total Price (PKR)</th><td><strong><?= number_format($total_price, 2) ?></strong></td></tr>
        <tr><th>Booking Date</th><td><?= date('Y-m-d H:i:s', strtotime($booking['booking_date'])) ?></td></tr>
    </table>

    <div class="d-flex gap-2">
        <a href="edit.php?id=<?= $booking_id ?>" class="btn btn-primary">Edit Booking</a>
        <a href="list.php" class="btn btn-secondary">Go Back</a>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
