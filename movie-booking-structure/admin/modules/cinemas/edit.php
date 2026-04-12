<?php
include '../../includes/configdb.php';

$id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM cinemas WHERE cinema_id=$id");
$cinema = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);

    if (!empty($_FILES['image']['name'])) {
        $image_name = basename($_FILES['image']['name']);
        $image_path = "img/" . $image_name;  // save in img/ folder
        move_uploaded_file($_FILES['image']['tmp_name'], "../../" . $image_path);
    } else {
        $image_path = $cinema['image_path']; // keep old path
    }

    $query = "UPDATE cinemas SET name='$name', location='$location', image_path='$image_path' WHERE cinema_id=$id";
    mysqli_query($conn, $query);
    
    header("Location: list.php?msg=updated");
    exit;
}

include '../../includes/header.php';
?>

<div class="container mt-4">
    <h2>Edit Cinema</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group mb-3">
            <label>Cinema Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($cinema['name']); ?>" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label>Location</label>
            <input type="text" name="location" value="<?= htmlspecialchars($cinema['location']); ?>" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label>Current Image</label><br>
            <img src="../../<?= htmlspecialchars($cinema['image_path']); ?>" width="100"><br><br>
            <input type="file" name="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-danger">Update Cinema</button>
        <a href="list.php" class="btn btn-secondary">Go Back</a>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>
