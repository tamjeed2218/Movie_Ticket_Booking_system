<?php
include '../../includes/configdb.php';
include '../../includes/header.php';

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM cinemas WHERE cinema_id=$id");
$cinema = mysqli_fetch_assoc($result);
?>

<div class="container mt-4 cl text-dark">
    <h2>Cinema Details</h2>
    <p><strong>ID:</strong> <?= $cinema['cinema_id'] ?></p>
    <p><strong>Name:</strong> <?= $cinema['name'] ?></p>
    <p><strong>Location:</strong> <?= $cinema['location'] ?></p>
    <p><strong>Image:</strong></p>
<img src="../../<?= htmlspecialchars($cinema['image_path']); ?>" width="200"><br><br>
    <a href="list.php" class="btn btn-secondary">Go Back</a>
</div>

<?php include '../../includes/footer.php'; ?>
