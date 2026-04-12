<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Movie Booking Admin Dashboard">
  <meta name="author" content="">
<link href="/movie-booking-structure/admin/assets/images/logo/logo.png" rel="icon">
  <title>Admin Panel - Movie Booking</title>

  <!-- Vendor CSS -->
  <link href="/movie-booking-structure/admin/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="/movie-booking-structure/admin/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="/movie-booking-structure/admin/assets/css/ruang-admin.min.css" rel="stylesheet"> <link rel="stylesheet" href="\movie-booking-structure\admin\assets\css\style.css">
</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
    <ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
        <div class="sidebar-brand-icon">
          <img src="/movie-booking-structure/admin/assets/images/logo/logo2.png" alt="Logo">
        </div>
        <div class="sidebar-brand-text mx-3">Movie Admin</div>
      </a>
      <hr class="sidebar-divider my-0">

      <li class="nav-item active">
        <a class="nav-link" href="/movie-booking-structure/admin/dashboard.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <hr class="sidebar-divider">
      <div class="sidebar-heading">Management</div>
         <li class="nav-item">
         <a class="nav-link" href="/movie-booking-structure/admin/modules/cinemas/list.php">
         <i class="fas fa-building"></i><span>Cinemas</span>
       </a>
    </li>
      <li class="nav-item">
        <a class="nav-link" href="/movie-booking-structure/admin/modules/movies/list.php">
          <i class="fas fa-film"></i>
          <span>Movies</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="/movie-booking-structure/admin/modules/shows/list.php">
          <i class="fas fa-calendar-day"></i>
          <span>Shows</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="/movie-booking-structure/admin/modules/bookings/list.php">
          <i class="fas fa-ticket-alt"></i>
          <span>Bookings</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="/movie-booking-structure/admin/modules/payments/list.php">
          <i class="fas fa-credit-card"></i>
          <span>Payments</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="/movie-booking-structure/admin/modules/ratings/list.php">
          <i class="fas fa-star"></i>
          <span>Ratings</span></a>
      </li>

      <hr class="sidebar-divider">
      <div class="sidebar-heading">Account</div>

      <li class="nav-item">
        <a class="nav-link" href="/movie-booking-structure/admin/modules/users/list.php">
          <i class="fas fa-users"></i>
          <span>Users</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="/movie-booking-structure/admin/logout.php">
          <i class="fas fa-sign-out-alt"></i>
          <span>Logout</span></a>
      </li>
    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
        <nav class="navbar navbar-expand navbar-light bg-navbar topbar mb-4 static-top">
          <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <ul class="navbar-nav ml-auto">
            <div class="topbar-divider d-none d-sm-block"></div>
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                <img class="img-profile rounded-circle" src="/movie-booking-structure/admin/assets/images/boy.png" style="max-width: 60px">
                <span class="ml-2 d-none d-lg-inline text-white small">
                  <?= htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>
                </span>
              </a>
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="/movie-booking-structure/admin/profile.php">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="/movie-booking-structure/admin/logout.php">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>
          </ul>
        </nav>
        <!-- End TopBar -->
