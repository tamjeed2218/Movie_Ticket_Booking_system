<?php
include '../../includes/configdb.php';
include '../../includes/header.php';
// 
// Fetch cinemas
$query = "SELECT * FROM cinemas ORDER BY cinema_id DESC";
$result = mysqli_query($conn, $query);
?>

<div class="container mt-4">
    <h2 class="mb-4">Manage Cinemas</h2>
     <div class="d-flex justify-content-between mb-3">
    <a href="add.php" class="btn btn-primary">+ Add Cinema</a>
    <a href="\movie-booking-structure\admin\dashboard.php" class="btn btn-dark">Go Back</a>
</div>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cinema Name</th>
                <th>Location</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['cinema_id'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['location'] ?></td>
<td><img src="../../<?= htmlspecialchars($row['image_path']); ?>" width="80"></td>
                  <td>
    <div class="btn-group d-flex justify-content-center" role="group" aria-label="Actions" style="gap:15px;">
        <a href="view.php?id=<?= $row['cinema_id'] ?>" 
           class="btn btn-info btn-sm text-white" title="View Cinema">
            <i class="fa fa-eye"></i>
        </a>
        <a href="edit.php?id=<?= $row['cinema_id'] ?>" 
           class="btn btn-warning btn-sm text-white" title="Edit Cinema">
            <i class="fa fa-edit"></i>
        </a>
        <a href="delete.php?id=<?= $row['cinema_id'] ?>" 
           class="btn btn-danger btn-sm" title="Delete Cinema"
           onclick="return confirm('Are you sure you want to delete this cinema?');">
            <i class="fa fa-trash"></i>
        </a>
    </div>
</td>

                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>
