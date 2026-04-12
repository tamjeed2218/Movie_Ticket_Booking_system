<?php
include '../../includes/configdb.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);

    if (!empty($_FILES['image']['name'])) {
        $image_name = basename($_FILES['image']['name']);
        $image_path = "img/" . $image_name;  // save in img/ folder
        move_uploaded_file($_FILES['image']['tmp_name'], "../../" . $image_path);
    } else {
        $image_path = "img/default_cinema.jpg"; // default placeholder
    }

    $query = "INSERT INTO cinemas (name, location, image_path) VALUES ('$name', '$location', '$image_path')";
    mysqli_query($conn, $query);

    header("Location: list.php?msg=added");
    exit;
}

include '../../includes/header.php';
?>

<div class="container mt-4">
    <h2>Add New Cinema</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group mb-3">
            <label>Cinema Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label>Location</label>
            <input type="text" name="location" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label>Cinema Image</label>
            <input type="file" name="image" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Cinema</button>
        <a href="list.php" class="btn btn-secondary">Go Back</a>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>
