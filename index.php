<?php
include "header.php";
include "navbar.php";
?>
<section id="center" class="center_home">
 <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">

  <!-- Indicators -->
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>

  <!-- Slides -->
  <div class="carousel-inner">

    <!-- Movie 1 -->
    <div class="carousel-item active">
      <img src="img/avatar2.jpg" class="d-block w-100 carousel-img" alt="Avatar: The Way of Water">
      <div class="carousel-caption d-md-block text-start">
        <h1 class="font_60">Avatar: The Way of Water</h1>
        <h6 class="mt-3">
          <span class="col_red me-3">
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star-half-o"></i>
          </span>
          7.6 (IMDb) | Year: 2022
          <a class="bg_red p-2 pe-4 ps-4 ms-3 text-white d-inline-block">Sci-Fi</a>
        </h6>
        <p class="mt-3">Jake Sully lives with his newfound family on Pandora...</p>
        <h6 class="mt-4"><a class="button" href="https://www.youtube.com/watch?v=d9MyW72ELq0" target="_blank"><i class="fa fa-play-circle me-1"></i> Watch Trailer</a></h6>
      </div>
    </div>

    <!-- Movie 2 -->
    <div class="carousel-item">
      <img src="img/johnwick4.jpg" class="d-block w-100 carousel-img" alt="John Wick: Chapter 4">
      <div class="carousel-caption d-md-block text-start">
        <h1 class="font_60">John Wick: Chapter 4</h1>
        <h6 class="mt-3">
          <span class="col_red me-3">
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star-half-o"></i>
          </span>
          7.8 (IMDb) | Year: 2023
          <a class="bg_red p-2 pe-4 ps-4 ms-3 text-white d-inline-block">Action</a>
        </h6>
        <p class="mt-3">With the price on his head ever increasing...</p>
        <h6 class="mt-4"><a class="button" href="https://www.youtube.com/watch?v=qEVUtrk8_B4" target="_blank"><i class="fa fa-play-circle me-1"></i> Watch Trailer</a></h6>
      </div>
    </div>

    <!-- Movie 3 -->
    <div class="carousel-item">
      <img src="img/oppenheimer.jpg" class="d-block w-100 carousel-img" alt="Oppenheimer">
      <div class="carousel-caption d-md-block text-start">
        <h1 class="font_60">Oppenheimer</h1>
        <h6 class="mt-3">
          <span class="col_red me-3">
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star-half-o"></i>
          </span>
          8.5 (IMDb) | Year: 2023
          <a class="bg_red p-2 pe-4 ps-4 ms-3 text-white d-inline-block">Biography</a>
        </h6>
        <p class="mt-3">The story of American scientist J. Robert Oppenheimer...</p>
        <h6 class="mt-4 mb-0"><a class="button" href="https://www.youtube.com/watch?v=uYPbbksJxIg" target="_blank"><i class="fa fa-play-circle me-1"></i> Watch Trailer</a></h6>
      </div>
    </div>

  </div>

  <!-- Controls -->
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>

 </div>
</section>

<!-- Add this CSS to your stylesheet -->
<style>
.carousel-img {
    height: 500px;       /* fixed height for all carousel images */
    object-fit: cover;   /* cover entire area without stretching */
}
</style>



<?php
include 'configdb.php';

// Fetch movies with average rating & reviews
$sql = "
    SELECT m.movie_id, m.title, m.description, m.image_path, m.trailer_link,
           IFNULL(ROUND(AVG(r.rating),1), 0) AS avg_rating,
           COUNT(r.rating_id) AS total_reviews
    FROM movies m
    LEFT JOIN movie_ratings r ON m.movie_id = r.movie_id
    GROUP BY m.movie_id
    ORDER BY m.movie_id DESC
    LIMIT 8
";
$result = mysqli_query($conn, $sql);

$movies = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $movies[] = $row;
    }
}

// Split into groups of 4 for carousel
$chunks = array_chunk($movies, 4);
?>

<section id="trend" class="pt-4 pb-5">
 <div class="container">
   <div class="row trend_1">
     <div class="col-md-12">
       <div class="trend_1l">
         <h4 class="mb-0"><i class="fa fa-youtube-play align-middle col_red me-1"></i> Latest <span class="col_red">Movies</span></h4>
       </div>
     </div>
   </div>

   <div class="row trend_2 mt-4">
     <div id="carouselExampleCaptions1" class="carousel slide" data-bs-ride="carousel">
       <div class="carousel-indicators">
         <?php for($i=0; $i<count($chunks); $i++): ?>
           <button type="button" data-bs-target="#carouselExampleCaptions1" data-bs-slide-to="<?php echo $i; ?>" class="<?php echo $i==0?'active':''; ?>"></button>
         <?php endfor; ?>
       </div>

       <div class="carousel-inner">
         <?php
         $active = "active";
         foreach ($chunks as $chunk) {
           echo '<div class="carousel-item '.$active.'"><div class="trend_2i row">';
           foreach ($chunk as $movie) {
         ?>
           <div class="col-md-3 col-6 mb-4">
             <div class="trend_2im position-relative">
               <figure class="mb-0">
                 <a href="movie-details.php?id=<?php echo $movie['movie_id']; ?>">
                   <img src="<?php echo $movie['image_path']; ?>" class="w-100 movie-img" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                 </a>
               </figure>
               <div class="trend_2im2 text-center position-absolute w-100 top-0">
                 <a class="col_red fs-3" href="<?php echo $movie['trailer_link']; ?>" target="_blank"><i class="fa fa-youtube-play"></i></a>
               </div>
             </div>
             <div class="trend_2ilast bg_grey p-3 text-center">
               <h5><a class="col_red" href="movie-details.php?id=<?php echo $movie['movie_id']; ?>"><?php echo $movie['title']; ?></a></h5>
               <p class="mb-2"><?php echo substr($movie['description'], 0, 60).'...'; ?></p>
               <span class="col_red">
                 <?php
                   $stars = round($movie['avg_rating']);
                   for ($i=1; $i<=5; $i++) {
                     echo $i <= $stars ? '<i class="fa fa-star"></i>' : '<i class="fa fa-star-o"></i>';
                   }
                 ?>
               </span>
               <p class="mb-0"><?php echo $movie['total_reviews']; ?> Reviews</p>
             </div>  
           </div>
         <?php
           }
           echo '</div></div>';
           $active = ""; // only first item active
         }
         ?>
       </div>
     </div>
   </div>
 </div>
</section>

<style>
/* Latest Movies card fixes */
.movie-img {
    height: 250px;
    object-fit: cover;
}

.trend_2im {
    overflow: hidden;
    border-radius: 5px;
}

.trend_2im2 {
    top: 10px;
}

.trend_2ilast h5, .trend_2ilast p, .trend_2ilast span, .trend_2ilast .mb-0 {
    font-size: 0.9rem;
}

.trend_2ilast {
    min-height: 160px;
}
</style>




<section id="upcome" class="pt-4 pb-5">
<div class="container">
 <div class="row trend_1">
  <div class="col-md-6 col-6">
   <div class="trend_1l">
    <h4 class="mb-0"><i class="fa fa-youtube-play align-middle col_red me-1"></i> Upcoming <span class="col_red">Movies</span></h4>
   </div>
  </div>
  <div class="col-md-6 col-6">
   
  </div>
 </div>

 <?php
   // Fetch 6 upcoming movies
   $query = "SELECT * FROM movies WHERE status='upcoming' LIMIT 6";
   $result = mysqli_query($conn, $query);

   $movies = [];
   while($row = mysqli_fetch_assoc($result)) {
       $movies[] = $row;
   }
 ?>

 <div class="row trend_2 mt-4">
   <div id="carouselExampleCaptions2" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#carouselExampleCaptions2" data-bs-slide-to="0" class="active"></button>
      <?php if(count($movies) > 3): ?>
        <button type="button" data-bs-target="#carouselExampleCaptions2" data-bs-slide-to="1"></button>
      <?php endif; ?>
    </div>

    <div class="carousel-inner">

      <!-- First Slide -->
      <div class="carousel-item active">
        <div class="trend_2i row">
          <?php for($i=0; $i<3 && $i<count($movies); $i++): ?>
            <div class="col-md-4">
              <div class="trend_2im clearfix position-relative">
               <div class="trend_2im1 clearfix">
                 <div class="grid">
                    <figure class="effect-jazz mb-0">
                      <a href="#"><img src="<?= $movies[$i]['image_path'] ?>" class="w-100" alt="<?= $movies[$i]['title'] ?>"></a>
                    </figure>
                 </div>
               </div>
               <div class="trend_2im2 clearfix text-center position-absolute w-100 top-0">
                 <span class="fs-1"><a class="col_red" href="<?= $movies[$i]['trailer_link'] ?>" target="_blank"><i class="fa fa-youtube-play"></i></a></span>
               </div>
              </div>
              <div class="trend_2ilast bg_grey p-3 clearfix">
                <h5><a class="col_red" href="#"><?= $movies[$i]['title'] ?></a></h5>
                <p class="mb-2"><?= substr($movies[$i]['description'],0,60) ?>...</p>
                <span class="col_red">
                 <i class="fa fa-star"></i>
                 <i class="fa fa-star"></i>
                 <i class="fa fa-star"></i>
                 <i class="fa fa-star"></i>
                 <i class="fa fa-star"></i>
                </span>
                <p class="mb-0"><?= $movies[$i]['genre'] ?></p>
              </div>  
            </div>
          <?php endfor; ?>
        </div>
      </div>

      <!-- Second Slide -->
      <?php if(count($movies) > 3): ?>
      <div class="carousel-item">
        <div class="trend_2i row">
          <?php for($i=3; $i<6 && $i<count($movies); $i++): ?>
            <div class="col-md-4">
              <div class="trend_2im clearfix position-relative">
               <div class="trend_2im1 clearfix">
                 <div class="grid">
                    <figure class="effect-jazz mb-0">
                      <a href="#"><img src="<?= $movies[$i]['image_path'] ?>" class="w-100" alt="<?= $movies[$i]['title'] ?>"></a>
                    </figure>
                 </div>
               </div>
               <div class="trend_2im2 clearfix text-center position-absolute w-100 top-0">
                 <span class="fs-1"><a class="col_red" href="<?= $movies[$i]['trailer_link'] ?>" target="_blank"><i class="fa fa-youtube-play"></i></a></span>
               </div>
              </div>
              <div class="trend_2ilast bg_grey p-3 clearfix">
                <h5><a class="col_red" href="#"><?= $movies[$i]['title'] ?></a></h5>
                <p class="mb-2"><?= substr($movies[$i]['description'],0,60) ?>...</p>
                <span class="col_red">
                 <i class="fa fa-star"></i>
                 <i class="fa fa-star"></i>
                 <i class="fa fa-star"></i>
                 <i class="fa fa-star"></i>
                 <i class="fa fa-star"></i>
                </span>
                <p class="mb-0"><?= $movies[$i]['genre'] ?></p>
              </div>  
            </div>
          <?php endfor; ?>
        </div>
      </div>
      <?php endif; ?>

    </div>
   </div>
 </div>
</div>
</section>


<section id="popular" class="pt-4 pb-5 bg_grey">
<div class="container">
 <div class="row trend_1">
  <div class="col-md-12">
   <div class="trend_1l">
    <h4 class="mb-0"><i class="fa fa-youtube-play align-middle col_red me-1"></i> Trending <span class="col_red">Movies</span></h4>
   </div>
  </div>
 </div>

 <?php
   // Fetch latest 8 movies (you can change limit)
   $moviesResult = $conn->query("SELECT * FROM movies ORDER BY movie_id DESC LIMIT 8");
   $movies = $moviesResult->fetch_all(MYSQLI_ASSOC);
 ?>

 <div class="popular_2 row mt-4">
   <div class="tab-content">
     <div class="tab-pane active" id="home">
       <div class="popular_2i row">
         <?php foreach ($movies as $movie): ?>
           <div class="col-md-6 mb-4">
             <div class="popular_2i1 row">
               <div class="col-md-4 col-4">
                 <div class="popular_2i1lm position-relative clearfix">
                   <div class="popular_2i1lm1 clearfix">
                     <div class="grid">
                       <figure class="effect-jazz mb-0">
                         <a href="view.php?id=<?= $movie['movie_id'] ?>">
                           <img src="<?= htmlspecialchars($movie['image_path']); ?>" class="w-100" alt="<?= htmlspecialchars($movie['title']); ?>">
                         </a>
                       </figure>
                     </div>
                   </div>
                   <div class="popular_2i1lm2 position-absolute top-0 w-100 text-center clearfix">
                     <ul>
                       <li class="d-inline-block"><a href="<?= $movie['trailer_link'] ?>" target="_blank"><i class="fa fa-youtube-play col_red"></i></a></li>
                       <li class="d-inline-block"><a href="view.php?id=<?= $movie['movie_id'] ?>"><i class="fa fa-search col_red"></i></a></li>
                     </ul>
                   </div>
                 </div>
               </div>
               <div class="col-md-8 col-8">
                 <div class="popular_2i1r">
                   <h5><a class="col_red" href="view.php?id=<?= $movie['movie_id'] ?>"><?= htmlspecialchars($movie['title']); ?></a></h5>
                   <h6><?= htmlspecialchars($movie['genre']); ?> | Runtime: <?= $movie['duration']; ?> min</h6>
                   <p><?= htmlspecialchars(substr($movie['description'], 0, 120)) ?>...</p>
                   <h6 class="mb-0"><a class="button" href="<?= $movie['trailer_link'] ?>" target="_blank">Watch Trailer</a></h6>
                 </div>
               </div>
             </div>
           </div>
         <?php endforeach; ?>
       </div>
     </div>
   </div>
 </div>
</div>
</section>




<section id="play">
<div class="play_m clearfix">
 <div class="container">
  <div class="row trend_1">
   <div class="col-md-12">
    <div class="trend_1l">
     <h4 class="mb-0"><i class="fa fa-youtube-play align-middle col_red me-1"></i> Top <span class="col_red">10 Playlist</span></h4>
    </div>
   </div>
  </div>

  <?php
    // Fetch Top 10 Movies (you can define your own logic: ORDER BY rating, views, etc.)
    $topMoviesResult = $conn->query("SELECT * FROM movies ORDER BY movie_id DESC LIMIT 10");
    $topMovies = $topMoviesResult->fetch_all(MYSQLI_ASSOC);

    // First movie (main big one)
    $mainMovie = $topMovies[0];
    // Remaining 9
    $sideMovies = array_slice($topMovies, 1, 6); // right column small thumbnails
    $featuredMovie = $topMovies[7] ?? null; // big featured one below
  ?>

  <!-- First Playlist Row -->
  <div class="play1 row mt-4 bg_grey pt-3 pb-3">
    <div class="col-md-9">
      <div class="play1l">
        <div class="grid clearfix">
          <figure class="effect-jazz mb-0">
            <a href="view.php?id=<?= $mainMovie['movie_id'] ?>">
              <img src="<?= htmlspecialchars($mainMovie['image_path']); ?>" class="w-100" height="450" alt="<?= htmlspecialchars($mainMovie['title']); ?>">
            </a>
          </figure>
        </div>
      </div>
    </div>
    <div class="col-md-3 ps-0">
      <div class="play1r">
        <?php foreach ($sideMovies as $sm): ?>
        <div class="play1ri mt-3">
          <div class="grid clearfix">
            <figure class="effect-jazz mb-0">
              <a href="view.php?id=<?= $sm['movie_id'] ?>">
                <img src="<?= htmlspecialchars($sm['image_path']); ?>" class="w-100" alt="<?= htmlspecialchars($sm['title']); ?>">
              </a>
            </figure>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Featured Movie Section -->
  <?php if ($featuredMovie): ?>
  <div class="play2 row mt-4">
    <div class="col-md-4 p-0">
      <div class="play2l">
        <div class="grid clearfix">
          <figure class="effect-jazz mb-0">
            <a href="view.php?id=<?= $featuredMovie['movie_id'] ?>">
              <img src="<?= htmlspecialchars($featuredMovie['image_path']); ?>" height="515" class="w-100" alt="<?= htmlspecialchars($featuredMovie['title']); ?>">
            </a>
          </figure>
        </div>
      </div>
    </div>
    <div class="col-md-8 p-0">
      <div class="play2r bg_grey p-4">
        <h5><span class="col_red">BEST MOVIE OF THE MONTH :</span> <?= strtoupper($featuredMovie['title']); ?></h5>
        <h5 class="mt-3"><?= htmlspecialchars($featuredMovie['genre']); ?></h5>
        <hr class="line">
        <p class="mt-3"><?= htmlspecialchars(substr($featuredMovie['description'], 0, 250)); ?>...</p>

        <div class="play2ri row mt-4">
          <div class="col-md-6">
            <div class="play2ril">
              <h6 class="fw-normal">
                Running Time: <span class="pull-right"><?= $featuredMovie['duration']; ?> min</span></h6>
              <hr class="hr_1">
              <h6 class="fw-normal">
                Genre: <span class="pull-right"><?= htmlspecialchars($featuredMovie['genre']); ?></span></h6>
              <hr class="hr_1">
              <h6 class="fw-normal">
                Director: <span class="pull-right">Unknown</span></h6> <!-- You don’t have director col -->
              <hr class="hr_1">
              <h6 class="fw-normal">
                Stars: <span class="pull-right">N/A</span></h6> <!-- You don’t have stars col -->
              <hr class="hr_1">
              <h6 class="fw-normal">
                Release Date: <span class="pull-right">Coming Soon</span></h6> <!-- No release_date col -->
              <hr class="hr_1 mb-0">
            </div>
          </div>
          <div class="col-md-6">
            <div class="play2rir">
              <h6 class="fw-normal">Imdb - 8.5</h6>
              <div class="progress"><div class="progress-bar" style="width: 85%;"></div></div>
              <h6 class="fw-normal mt-3">Rotten - 7.5</h6>
              <div class="progress"><div class="progress-bar" style="width: 75%;"></div></div>
              <h6 class="fw-normal mt-3">Metacritic - 8.0</h6>
              <div class="progress"><div class="progress-bar" style="width: 80%;"></div></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>

 </div>
</div>
</section>





<?php
include "footer.php";
?>