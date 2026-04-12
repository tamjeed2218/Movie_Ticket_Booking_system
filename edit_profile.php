<?php
include "auth.php";
requireLogin();
include "configdb.php"; // ensures user is logged in

$user_id = $_SESSION['user_id'];

// Fetch user details
$userQuery = mysqli_query($conn, "SELECT * FROM users WHERE user_id=$user_id");
$user = mysqli_fetch_assoc($userQuery);

// Handle form submission
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $age = intval($_POST['age']);

    // Update user info
    $updateQuery = "UPDATE users SET name='$name', email='$email', age=$age WHERE user_id=$user_id";
    if(mysqli_query($conn, $updateQuery)){
        // Redirect to profile page after successful update
        header("Location: profile.php");
        exit;
    } else {
        // If update fails, optionally you can handle error (still redirect back)
        header("Location: profile.php");
        exit;
    }
}
?>

<?php include 'header.php'; include 'navbar.php'; ?>

<section id="edit-profile" class="py-5" style="background:#121212; color:#fff;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8">
        <div class="card shadow-sm border-0 rounded-3" style="background-color:#1f1f1f;">
          <div class="card-body">
            <h3 class="card-title text-center mb-4" style="color:#e50914;">Edit Profile</h3>

            <form method="POST">
              <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($user['name']); ?>" required>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>" required>
              </div>
              <div class="mb-3">
                <label for="age" class="form-label">Age</label>
                <input type="number" id="age" name="age" class="form-control" value="<?= intval($user['age']); ?>" required min="1" max="120">
              </div>
            <div class="text-center">
    <button type="submit" class="btn me-2" style="background-color:#e50914; color:#fff; border:none;">Save Changes</button>
    <a href="profile.php" class="btn btn-light">Cancel</a>
</div>

            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
