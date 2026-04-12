<?php
include "auth.php";
requireLogin();
include "configdb.php";

$user_id = intval($_SESSION['user_id']);

// Fetch user age
$userRow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT age FROM users WHERE user_id=$user_id LIMIT 1"));
$userAge = intval($userRow['age'] ?? 18);

// Get show_id or cinema_id from URL
$show_id = intval($_GET['show_id'] ?? 0);
$cinema_id = intval($_GET['cinema_id'] ?? 0);

if(!$show_id && !$cinema_id){
    header("Location: movies.php?msg=Please select a movie/show first");
    exit();
}

// Fetch show info
if($show_id){
    $show_sql = "SELECT s.show_id, s.show_date, s.show_time, c.name AS cinema_name, m.title AS movie_title
                 FROM shows s
                 JOIN cinemas c ON s.cinema_id=c.cinema_id
                 JOIN movies m ON s.movie_id=m.movie_id
                 WHERE s.show_id=$show_id
                 LIMIT 1";
    $show_res = mysqli_query($conn, $show_sql);
    $show = mysqli_fetch_assoc($show_res);
} elseif($cinema_id){
    $show_sql = "SELECT s.show_id, s.show_date, s.show_time, c.name AS cinema_name, m.title AS movie_title
                 FROM shows s
                 JOIN cinemas c ON s.cinema_id=c.cinema_id
                 JOIN movies m ON s.movie_id=m.movie_id
                 WHERE s.cinema_id=$cinema_id
                 ORDER BY s.show_date, s.show_time";
    $show_res = mysqli_query($conn, $show_sql);
    $show = mysqli_fetch_assoc($show_res);
}

if(!$show){
    echo '<p class="text-center text-light py-5">Show not found.</p>';
    include "footer.php"; exit;
}

// Handle booking + payment
$error = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $seat_class = $_POST['seat_class'] ?? '';
    $seat_count = intval($_POST['seat_count'] ?? 0);
    $payment_method = $_POST['payment_method'] ?? '';

    $show_id_post = intval($_POST['show_id'] ?? $show['show_id']);

    if(!$seat_class || $seat_count<=0 || !$payment_method){
        $error = "Please fill all fields.";
    } else {
        $prices = ["Gold"=>500,"Platinum"=>800,"Box"=>1200];
        if(!isset($prices[$seat_class])){
            $error = "Invalid seat class selected.";
        } else {
            $seat_price = $prices[$seat_class];
            if($userAge>=3 && $userAge<=12){ $seat_price *= 0.5; }
            $total_price = $seat_price * $seat_count;

            $q1 = "INSERT INTO bookings (user_id, show_id) VALUES ($user_id, $show_id_post)";
            if(mysqli_query($conn,$q1)){
                $booking_id = mysqli_insert_id($conn);

                $q2 = "INSERT INTO booking_details (booking_id, seat_count, user_age, seat_price)
                       VALUES ($booking_id, $seat_count, $userAge, $seat_price)";
                if(mysqli_query($conn,$q2)){
                    $q3 = "INSERT INTO payments (booking_id, amount, payment_date, payment_method)
                           VALUES ($booking_id, $total_price, NOW(), '$payment_method')";
                    mysqli_query($conn,$q3);

                    header("Location: my_bookings.php?msg=Booking Successful & Paid via $payment_method");
                    exit();
                } else {
                    mysqli_query($conn,"DELETE FROM bookings WHERE booking_id=$booking_id");
                    $error = "Booking failed: ".mysqli_error($conn);
                }
            } else {
                $error = "Booking failed: ".mysqli_error($conn);
            }
        }
    }
}
?>

<?php include "header.php"; include "navbar.php"; ?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card bg-dark border-0">
        <div class="card-body">
          <h3 class="fw-bold mb-3">Book Ticket for <?= htmlspecialchars($show['movie_title']) ?></h3>
          <p class="text-center mb-4">
            <strong>Cinema:</strong> <?= htmlspecialchars($show['cinema_name']) ?> | 
            <strong>Date:</strong> <?= date("d M Y", strtotime($show['show_date'])) ?> | 
            <strong>Time:</strong> <?= date("h:i A", strtotime($show['show_time'])) ?>
          </p>

          <?php if($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>

          <form method="post">

            <?php if($cinema_id && $show_res && mysqli_num_rows($show_res) > 1): ?>
            <div class="mb-3">
              <label class="form-label">Select Show</label>
              <select name="show_id" class="form-select" required>
                <?php
                mysqli_data_seek($show_res, 0);
                while($s = mysqli_fetch_assoc($show_res)){
                    $selected = ($s['show_id']==$show['show_id']) ? "selected" : "";
                    echo "<option value='{$s['show_id']}' $selected>{$s['movie_title']} - ".date("d M Y",strtotime($s['show_date']))." ".date("h:i A",strtotime($s['show_time']))."</option>";
                }
                ?>
              </select>
            </div>
            <?php endif; ?>

            <div class="mb-3">
              <label class="form-label">Seat Class</label>
              <select name="seat_class" id="seat_class" class="form-select" required>
                <option value="">-- Select Class --</option>
                <option value="Gold">Gold</option>
                <option value="Platinum">Platinum</option>
                <option value="Box">Box</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Number of Seats</label>
              <input type="number" id="seat_count" name="seat_count" min="1" value="1" class="form-control" required>
            </div>

            <div class="mb-3">
              <label for="payment_method" class="form-label">Payment Method</label>
              <select name="payment_method" id="payment_method" class="form-select" required>
                  <option value="">-- Select Payment Method --</option>
                  <option value="JazzCash">JazzCash</option>
                  <option value="Easypaisa">Easypaisa</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Total Price (PKR)</label>
              <input type="text" id="total_price" class="form-control" value="0.00" readonly>
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-brand">Book Ticket</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
const seatPrices = {"Gold":500,"Platinum":800,"Box":1200};
const userAge = <?= $userAge ?>;

function updateTotal(){
    const seatClass = document.getElementById('seat_class').value;
    const seatCount = parseInt(document.getElementById('seat_count').value) || 0;
    let total = 0;
    if(seatClass && seatCount>0){
        total = seatPrices[seatClass]*seatCount;
        if(userAge>=3 && userAge<=12){ total*=0.5; }
    }
    document.getElementById('total_price').value = total.toLocaleString('en-US',{minimumFractionDigits:2});
}

document.getElementById('seat_class').addEventListener('change',updateTotal);
document.getElementById('seat_count').addEventListener('input',updateTotal);
updateTotal();
</script>

<?php include "footer.php"; ?>

<style>
body{background:#0b0b0b;color:#fff}
.form-control, .form-select { background-color:#1f2124 !important;color:#fff !important;border:1px solid #333 !important; }
.form-control:focus, .form-select:focus, .form-control:hover, .form-select:hover { border-color:#e50914 !important; box-shadow:0 0 5px #e50914 !important; }
.btn-brand { background:#e50914;color:#fff;border:none;font-weight:600;padding:0.65rem 0;width:100%;border-radius:0.35rem; transition:background 0.3s; }
.btn-brand:hover { background:#b20710;color:#fff; }
.card-body h3 { color:#e50914; text-align:center; }
.card-body p { color:#fff; font-size:1rem; text-align:center; margin-bottom:1.5rem; }
</style>
