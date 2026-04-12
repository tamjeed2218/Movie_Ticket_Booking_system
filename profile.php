<?php
include "auth.php";
requireLogin();
include "configdb.php"; // ensures user is logged in

$user_id = $_SESSION['user_id'];

// Fetch user details
$userQuery = mysqli_query($conn, "SELECT * FROM users WHERE user_id=$user_id");
$user = mysqli_fetch_assoc($userQuery);

// Fetch bookings with seat count and price
$bookingsQuery = mysqli_query($conn, "
    SELECT b.booking_id, b.booking_date, s.show_date, s.show_time, s.seat_class, bd.seat_count, bd.seat_price, m.title, c.name AS cinema_name
    FROM bookings b
    JOIN booking_details bd ON b.booking_id = bd.booking_id
    JOIN shows s ON b.show_id = s.show_id
    JOIN movies m ON s.movie_id = m.movie_id
    JOIN cinemas c ON s.cinema_id = c.cinema_id
    WHERE b.user_id=$user_id
    ORDER BY b.booking_date DESC
");

// Fetch user reviews
$reviewsQuery = mysqli_query($conn, "
    SELECT r.review_text, r.rating, c.name AS cinema_name
    FROM user_reviews r
    JOIN cinemas c ON r.cinema_id = c.cinema_id
    WHERE r.user_id=$user_id
    ORDER BY r.review_id DESC
");
?>

<?php include 'header.php'; include "navbar.php"; ?>

<section id="user-profile" class="py-5" style="background:#121212; color:#fff;">
  <div class="container">

    <!-- User Info Section -->
    <div class="row justify-content-center mb-5">
      <div class="col-lg-6 col-md-8">
        <div class="card shadow-sm border-0 rounded-3" style="background-color:#1f1f1f;">
          <div class="card-body text-center">
            <h3 class="card-title" style="color:#e50914;"><?= htmlspecialchars($user['name']); ?></h3>
            <p class="text-light mb-1"><i class="fa fa-envelope me-2"></i><?= htmlspecialchars($user['email']); ?></p>
            <p class="text-light"><i class="fa fa-birthday-cake me-2"></i><?= htmlspecialchars($user['age']); ?> years</p>
            <a href="edit_profile.php" class="btn me-2" style="background-color:#e50914; color:#fff; border:none;">Edit Profile</a>
          </div>
        </div>
      </div>
    </div>

    <!-- My Bookings Section -->
    <div class="row mb-5">
      <div class="col-12">
        <h4 style="color:#e50914; margin-bottom:15px;">My Bookings</h4>
        <?php if(mysqli_num_rows($bookingsQuery) > 0): ?>
          <div class="list-group">
            <?php while($booking = mysqli_fetch_assoc($bookingsQuery)): ?>
              <div class="list-group-item mb-3 shadow-sm rounded-3" style="background-color:#1f1f1f; color:#fff; border-left:5px solid #e50914;">
                <h5 style="color:#e50914;"><?= htmlspecialchars($booking['title']); ?></h5>
                <small class="text-light"><?= htmlspecialchars($booking['cinema_name']); ?> | <?= date('d M Y', strtotime($booking['show_date'])); ?> at <?= date('H:i', strtotime($booking['show_time'])); ?></small>
                <p class="mb-1">
                  Class: <strong><?= htmlspecialchars($booking['seat_class']); ?></strong> | 
                  Seats: <strong><?= intval($booking['seat_count']); ?></strong> | 
                  Price: <strong>Rs: <?= number_format($booking['seat_price']); ?></strong>
                </p>
                <small class="text-light">Booked on <?= date('d M Y H:i', strtotime($booking['booking_date'])); ?></small>
              </div>
            <?php endwhile; ?>
          </div>
        <?php else: ?>
          <p class="text-light">You have no bookings yet.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- My Reviews Section -->
    <div class="row">
      <div class="col-12">
        <h4 style="color:#e50914; margin-bottom:15px;">My Reviews</h4>
        <?php if(mysqli_num_rows($reviewsQuery) > 0): ?>
          <div class="list-group">
            <?php while($review = mysqli_fetch_assoc($reviewsQuery)): ?>
              <div class="list-group-item mb-3 shadow-sm rounded-3" style="background-color:#1f1f1f; color:#fff; border-left:5px solid #e50914;">
                <h6 style="color:#e50914;"><?= htmlspecialchars($review['cinema_name']); ?></h6>
                <p class="mb-1"><?= htmlspecialchars($review['review_text']); ?></p>
                <small class="text-warning"><?= str_repeat('★', $review['rating']); ?><?= str_repeat('☆', 5 - $review['rating']); ?></small>
              </div>
            <?php endwhile; ?>
          </div>
        <?php else: ?>
          <p class="text-light">You have not submitted any reviews yet.</p>
        <?php endif; ?>
      </div>
    </div>

  </div>
</section>

<?php include 'footer.php'; ?>
