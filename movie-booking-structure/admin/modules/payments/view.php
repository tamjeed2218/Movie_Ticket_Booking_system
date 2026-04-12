<?php
include '../../includes/configdb.php';
include '../../includes/header.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location:list.php");
    exit;
}

$payment_id = intval($_GET['id']);

$payment = $conn->query("
    SELECT p.payment_id, b.booking_id, u.name AS user_name, 
           bd.seat_count, bd.seat_price, p.amount, p.payment_date, p.payment_method
    FROM payments p
    JOIN bookings b ON p.booking_id = b.booking_id
    JOIN users u ON b.user_id = u.user_id
    JOIN booking_details bd ON bd.booking_id = b.booking_id
    WHERE p.payment_id = $payment_id
")->fetch_assoc();
?>

<div class="container mt-5">
    <h2>Payment Details</h2>
    <table class="table table-bordered">
        <tr><th>Payment ID</th><td><?= $payment['payment_id'] ?></td></tr>
        <tr><th>Booking ID</th><td><?= $payment['booking_id'] ?></td></tr>
        <tr><th>User</th><td><?= htmlspecialchars($payment['user_name']) ?></td></tr>
        <tr><th>Seat Count</th><td><?= $payment['seat_count'] ?></td></tr>
        <tr><th>Seat Price</th><td><?= number_format($payment['seat_price'], 2) ?></td></tr>
        <tr><th>Total Amount</th><td><?= number_format($payment['amount'], 2) ?></td></tr>
        <tr><th>Payment Method</th><td><?= htmlspecialchars($payment['payment_method']) ?></td></tr>
        <tr><th>Payment Date</th><td><?= $payment['payment_date'] ?></td></tr>
    </table>
    <a href="list.php" class="btn btn-secondary">Go Back</a>
</div>

<?php include '../../includes/footer.php'; ?>
