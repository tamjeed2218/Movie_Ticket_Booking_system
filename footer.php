<!-- includes/footer.php -->
<link rel="stylesheet" href="free_movie_zip/css/global.css">

<footer class="footer bg-black text-light pt-5 pb-4 mt-5">
  <div class="container">
    <div class="row">
      <!-- About -->
      <div class="col-md-4 mb-4">
        <h4 class="fw-bold">
          <i class="fa fa-video-camera me-2"></i> Movie Booking
        </h4>
        <p class="small text-light mt-3">
          Book your favorite movies anytime, anywhere. Enjoy a seamless booking
          experience with real-time seat selection and instant confirmation.
        </p>
      </div>

      <!-- Quick Links -->
      <div class="col-md-4 mb-4">
        <h5 class="text-uppercase fw-bold mb-3">Quick Links</h5>
        <ul class="list-unstyled">
          <li><a href="index.php" class="footer-link">Home</a></li>
          <li><a href="movies.php" class="footer-link">Movies</a></li>
          <li><a href="cinemas.php" class="footer-link">Cinemas</a></li>
          <li><a href="about.php" class="footer-link">About</a></li>
        </ul>
      </div>

      <!-- Contact -->
      <div class="col-md-4 mb-4">
        <h5 class="text-uppercase fw-bold mb-3">Contact Us</h5>
        <p class="small mb-2"><i class="fa fa-map-marker me-2"></i> Karachi, Pakistan</p>
        <p class="small mb-2"><i class="fa fa-envelope me-2"></i> support@moviebooking.com</p>
        <p class="small mb-2"><i class="fa fa-phone me-2"></i> +92 300 1234567</p>

        <!-- Social Icons -->
        <div class="mt-3">
          <a href="#" class="social-icon"><i class="fa fa-facebook"></i></a>
          <a href="#" class="social-icon"><i class="fa fa-twitter"></i></a>
          <a href="#" class="social-icon"><i class="fa fa-instagram"></i></a>
          <a href="#" class="social-icon"><i class="fa fa-youtube"></i></a>
        </div>
      </div>
    </div>

    <hr class="border-secondary">

    <div class="text-center small text-secondary">
      &copy; <?php echo date("Y"); ?> <span class="text-white">Movie Booking System</span> | All Rights Reserved
    </div>
  </div>
</footer>

<!-- Footer CSS -->
<style>
/* Headings and icons in red */
footer h4,
footer h5,
footer h4 i,
footer h5 i {
    color: #e50914 !important;
}

/* Footer links */
footer a.footer-link {
    color: #ffffff;
    text-decoration: none;
}
footer a.footer-link:hover {
    color: #e50914;
}

/* Social icons hover effect */
footer .social-icon {
    color: #ffffff;
    font-size: 18px;
    margin-right: 10px;
    transition: color 0.3s ease;
}
footer .social-icon:hover {
    color: #e50914;
}
</style>

<!-- Bootstrap JS -->
<script src="assets/js/bootstrap.bundle.min.js"></script>
