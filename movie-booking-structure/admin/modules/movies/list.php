<?php
include '../../includes/configdb.php';
include '../../includes/header.php';

// Fetch all movies
$result = $conn->query("SELECT * FROM movies");
?>

<div class="container mt-5">
    <h2>All Movies</h2>
    <div class="d-flex justify-content-between mb-3">
        <a href="add.php" class="btn btn-primary">+ Add Movie</a>
        <a href="\movie-booking-structure\admin\dashboard.php" class="btn btn-dark">Go Back</a>
    </div>    

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Genre</th>
                <th>Duration</th>
                <th>Description</th>
                <th>Image</th>
                <th>Trailer</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['movie_id'] ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['genre']) ?></td>
                    <td><?= htmlspecialchars($row['duration'] ?? '-') ?></td>
                    <td><?= htmlspecialchars(substr($row['description'],0,50)) ?>...</td>
                    <td>
                        <?php 
                        $imgFullPath = '../../' . $row['image_path'];
                        if (!empty($row['image_path']) && file_exists($imgFullPath)): ?>
                            <img src="<?= $imgFullPath ?>" alt="<?= htmlspecialchars($row['title']); ?>" width="80">
                        <?php else: ?>
                            <span class="text-danger">No Image</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= $row['trailer_link'] ?>" target="_blank" class="btn btn-sm btn-primary">Watch</a>
                    </td>
                    <td>
                        <div class="d-flex justify-content-center" style="gap:15px;">
                            <a href="view.php?id=<?= $row['movie_id'] ?>" 
                               class="btn btn-info btn-sm text-white" title="View Movie">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="edit.php?id=<?= $row['movie_id'] ?>" 
                               class="btn btn-warning btn-sm text-white" title="Edit Movie">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a href="delete.php?id=<?= $row['movie_id'] ?>" 
                               class="btn btn-danger btn-sm" title="Delete Movie"
                               onclick="return confirm('Are you sure you want to delete this movie?');">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>
