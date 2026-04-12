<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<section id="header">
<nav class="navbar navbar-expand-md navbar-dark bg-black" id="navbar_sticky">
  <div class="container">

    <!-- Brand -->
    <a class="navbar-brand fw-bold" href="index.php">
      <i class="fa fa-video-camera col_red me-1"></i> Planet
    </a>

    <!-- Mobile: Login + Toggler -->
    <div class="d-flex align-items-center d-md-none ms-auto">
      <?php if (!isset($_SESSION['user_id'])): ?>
        <a href="login.php" class="btn bg_red text-white p-0 d-flex align-items-center justify-content-center me-2"
           style="width: 40px; height: 38px; border-radius: 0;">
          <i class="fa fa-user"></i>
        </a>
      <?php endif; ?>
      <button class="navbar-toggler border-0 shadow-none p-0" type="button" 
              data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>

    <!-- Navbar Links -->
    <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
      <ul class="navbar-nav mb-0">
        <?php if (!isset($_SESSION['user_id'])): ?>
          <!-- Visitor links as per requirement file -->
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="movies.php">Movies</a></li>
          <li class="nav-item"><a class="nav-link" href="cinemas.php">Cinemas</a></li>
          <li class="nav-item"><a class="nav-link" href="showtimes.php">Showtimes</a></li>
          <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>

        <?php else: ?>
          <!-- Logged-in user links (full navbar) -->
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="movies.php">Movies</a></li>
          <li class="nav-item"><a class="nav-link" href="cinemas.php">Cinemas</a></li>
          <li class="nav-item"><a class="nav-link" href="showtimes.php">Showtimes</a></li>
          <li class="nav-item"><a class="nav-link" href="my_bookings.php">My Bookings</a></li>
          <li class="nav-item"><a class="nav-link" href="ratings.php">Ratings</a></li>
          <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
          <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
          <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
        <?php endif; ?>
      </ul>
    </div>

  </div>
</nav>
</section>

<style>
.bg_red { background-color: #e50914 !important; }
#navbar_sticky { background-color: #000; }
.nav-link { color: #fff !important; }
.nav-link:hover { color: #e50914 !important; }
</style>
