<?php
include '../../includes/configdb.php';
include '../../includes/header.php';

// Fetch all bookings with payments
$result = $conn->query("
    SELECT 
        b.booking_id,
        u.name AS user_name,
        m.title AS movie_title,
        c.name AS cinema_name,
        s.show_date,
        s.show_time,
        s.seat_class,
        bd.seat_count,
        bd.seat_price,
        p.amount AS payment_amount,
        p.payment_date,
        p.payment_method
    FROM bookings b
    JOIN users u ON b.user_id = u.user_id
    JOIN shows s ON b.show_id = s.show_id
    JOIN movies m ON s.movie_id = m.movie_id
    JOIN cinemas c ON s.cinema_id = c.cinema_id
    JOIN booking_details bd ON bd.booking_id = b.booking_id
    JOIN payments p ON p.booking_id = b.booking_id   -- ✅ only successful payments
    ORDER BY b.booking_id DESC
");
?>

<div class="container mt-5">
    <h2 class="text-danger mb-4">All Bookings / Payments</h2>
    <div class="d-flex justify-content-between mb-3">
        <a href="../../dashboard.php" class="btn btn-dark">Go Back</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered text-center align-middle">
            <thead class="bg-dark text-white">
                <tr>
                    <th>Booking ID</th>
                    <th>User</th>
                    <th>Movie</th>
                    <th>Cinema</th>
                    <th>Show Date</th>
                    <th>Show Time</th>
                    <th>Seat Class</th>
                    <th>Seats</th>
                    <th>Total Price (PKR)</th>
                    <th>Payment Date</th>
                    <th>Payment Amount (PKR)</th>
                    <th>Payment Method</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): 
                    $total_price = $row['seat_count'] * $row['seat_price'];
                ?>
                <tr>
                    <td><?= $row['booking_id'] ?></td>
                    <td><?= htmlspecialchars($row['user_name']) ?></td>
                    <td><?= htmlspecialchars($row['movie_title']) ?></td>
                    <td><?= htmlspecialchars($row['cinema_name']) ?></td>
                    <td><?= date('Y-m-d', strtotime($row['show_date'])) ?></td>
                    <td><?= date('h:i A', strtotime($row['show_time'])) ?></td>
                    <td><?= htmlspecialchars($row['seat_class']) ?></td>
                    <td><?= $row['seat_count'] ?></td>
                    <td><?= number_format($total_price, 2) ?></td>
                    <td><?= date('Y-m-d H:i:s', strtotime($row['payment_date'])) ?></td>
                    <td><?= number_format($row['payment_amount'], 2) ?></td>
                    <td>
    <?php if ($row['payment_method'] === "JazzCash"): ?>
        <span class="badge" style="color:#fff; padding:6px 12px; border-radius:5px; font-size:0.9rem;">
            JazzCash
        </span>
    <?php elseif ($row['payment_method'] === "Easypaisa"): ?>
        <span class="badge" style="background:#1dbf73; color:#fff; padding:6px 12px; border-radius:5px; font-size:0.9rem;">
            Easypaisa
        </span>
    <?php else: ?>
        <span class="badge" style="background:#444; color:#fff; padding:6px 12px; border-radius:5px; font-size:0.9rem;">
            <?= htmlspecialchars($row['payment_method']) ?>
        </span>
    <?php endif; ?>
</td>

                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
