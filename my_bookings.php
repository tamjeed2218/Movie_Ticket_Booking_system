<?php
include "auth.php";
requireLogin();
include "configdb.php";

$user_id = intval($_SESSION['user_id']);

$sql = "SELECT b.booking_id, b.booking_date, 
        m.title AS movie_title, 
        c.name AS cinema_name, 
        s.show_date, s.show_time, s.seat_class,
        bd.seat_count, bd.user_age, bd.seat_price,
        p.amount AS payment_amount,
        p.payment_date,
        p.payment_method
        FROM bookings b
        JOIN booking_details bd ON b.booking_id = bd.booking_id
        JOIN shows s ON b.show_id = s.show_id
        JOIN movies m ON s.movie_id = m.movie_id
        JOIN cinemas c ON s.cinema_id = c.cinema_id
        LEFT JOIN payments p ON p.booking_id = b.booking_id
        WHERE b.user_id = $user_id
        ORDER BY b.booking_date DESC";

$res = mysqli_query($conn, $sql);
?>

<?php include "header.php"; include "navbar.php"; ?>

<div class="container py-5">
    <h3 class="mb-4">My Bookings</h3>

    <?php if(mysqli_num_rows($res) > 0): ?>
        <!-- Large Screen Table -->
        <div class="d-none d-md-block table-responsive">
        <table class="table table-dark table-striped table-bordered align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Movie</th>
                    <th>Cinema</th>
                    <th>Show</th>
                    <th>Seat Class</th>
                    <th>Seats</th>
                    <th>User Age</th>
                    <th>Total Price (PKR)</th>
                    <th>Booking Date</th>
                    <th>Payment Status</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = mysqli_fetch_assoc($res)):
                $total = $row['seat_count'] * $row['seat_price'];
                $paid = !empty($row['payment_amount']);
            ?>
            <tr>
                <td><?= $row['booking_id'] ?></td>
                <td><?= htmlspecialchars($row['movie_title']) ?></td>
                <td><?= htmlspecialchars($row['cinema_name']) ?></td>
                <td>
                    <span class="d-block"><?= date("Y-m-d", strtotime($row['show_date'])) ?></span>
                    <span class="d-block"><?= date("h:i A", strtotime($row['show_time'])) ?></span>
                </td>
                <td><?= htmlspecialchars($row['seat_class']) ?></td>
                <td><?= $row['seat_count'] ?></td>
                <td><?= $row['user_age'] ?></td>
                <td><?= number_format($total, 2) ?></td>
                <td><?= date("Y-m-d H:i:s", strtotime($row['booking_date'])) ?></td>
                <td>
                    <?php if($paid): ?>
                        <span class="badge bg-success">Paid (<?= htmlspecialchars($row['payment_method']) ?>)</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Not Paid</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        </div>

        <!-- Small Screen Cards -->
        <div class="d-md-none">
        <?php
        mysqli_data_seek($res, 0); // Reset result pointer
        while($row = mysqli_fetch_assoc($res)):
            $total = $row['seat_count'] * $row['seat_price'];
            $paid = !empty($row['payment_amount']);
        ?>
            <div class="card mb-3 bg-dark text-light shadow-sm">
                <div class="card-body p-3">
                    <h5 class="card-title text-danger mb-2"><?= htmlspecialchars($row['movie_title']) ?></h5>
                    <p class="mb-1"><strong>Cinema:</strong> <?= htmlspecialchars($row['cinema_name']) ?></p>
                    <p class="mb-1"><strong>Show:</strong> <?= date("Y-m-d", strtotime($row['show_date'])) ?> at <?= date("h:i A", strtotime($row['show_time'])) ?></p>
                    <p class="mb-1"><strong>Seat Class:</strong> <?= htmlspecialchars($row['seat_class']) ?></p>
                    <p class="mb-1"><strong>Seats:</strong> <?= $row['seat_count'] ?> | <strong>User Age:</strong> <?= $row['user_age'] ?></p>
                    <p class="mb-1"><strong>Total Price:</strong> Rs <?= number_format($total, 2) ?></p>
                    <p class="mb-1"><strong>Booking Date:</strong> <?= date("Y-m-d H:i:s", strtotime($row['booking_date'])) ?></p>
                    <p class="mb-0">
                        <strong>Payment:</strong> 
                        <?php if($paid): ?>
                            <span class="badge bg-success">Paid (<?= htmlspecialchars($row['payment_method']) ?>)</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Not Paid</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        <?php endwhile; ?>
        </div>

    <?php else: ?>
        <p class="text-light">You have no bookings yet.</p>
    <?php endif; ?>
</div>

<?php include "footer.php"; ?>

<style>
.card-title {
    word-break: break-word;
}

.badge {
    font-size: 0.8rem;
    padding: 0.3em 0.5em;
}

@media (max-width: 575.98px) {
    .card-body p {
        font-size: 0.85rem;
        margin-bottom: 0.3rem;
    }
}/* Define the main red color */
/* Define main red color */
:root {
    --main-red: #e50914;
}

/* Headings */
h3, .card-title, .col_red {
    color: var(--main-red) !important;
}

/* Paid and Not Paid badges */
.badge.bg-success {
    background-color: var(--main-red) !important; /* Paid badge in red */
}

.badge.bg-danger {
    background-color: #b00610 !important; /* Not Paid badge slightly darker */
}

/* Optional: Buttons if any are added later */
.btn, .btn-warning, .btn-outline-light {
    background-color: var(--main-red) !important;
    border-color: var(--main-red) !important;
    color: #fff !important;
}

.btn-outline-light:hover {
    background-color: var(--main-red) !important;
    color: #fff !important;
    border-color: var(--main-red) !important;
}


</style>
