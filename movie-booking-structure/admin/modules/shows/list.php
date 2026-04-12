<?php 
// admin/modules/shows/list.php

include '../../includes/configdb.php';
include '../../includes/header.php';
?>

<style>
/* 🔴 Make danger alert more attractive */
.alert-danger {
    background-color: #dc3545 !important; /* Strong red */
    color: #fff !important;
    font-weight: bold;
    border-radius: 6px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

/* ✅ Success alert style tweak */
.alert-success {
    background-color: #28a745 !important; /* Fresh green */
    color: #fff !important;
    font-weight: bold;
    border-radius: 6px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
</style>

<?php
// ✅ Success message after delete
if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        ✅ Show deleted successfully!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php
// ✅ Error message if bookings prevent deletion
if (isset($_GET['error']) && $_GET['error'] === 'bookings'): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        ❌ Cannot delete this show because bookings exist.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<script>
// 🔥 Auto dismiss alerts after 2 seconds
setTimeout(() => {
    let alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        let bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 2000);

// 🚀 Prevent alerts from reappearing on refresh
if (window.location.search.includes('msg=') || window.location.search.includes('error=')) {
    window.history.replaceState({}, document.title, window.location.pathname);
}
</script>

<?php
// ✅ Fetch shows with related movie + cinema details
$shows = $conn->query("
    SELECT s.show_id, s.show_date, s.show_time, s.seat_class, s.price,
           m.title AS movie_title, 
           c.name AS cinema_name
    FROM shows s
    JOIN movies m ON s.movie_id = m.movie_id
    JOIN cinemas c ON s.cinema_id = c.cinema_id
    ORDER BY s.show_date, s.show_time
")->fetch_all(MYSQLI_ASSOC);
?>

<h2 class="text-danger">Shows List</h2>

<div class="d-flex justify-content-between mb-3">
    <a href="add.php" class="btn btn-primary">+ Add Show</a>
    <a href="../../dashboard.php" class="btn btn-dark">Go Back</a>
</div>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Movie</th>
            <th>Cinema</th>
            <th>Date</th>
            <th>Time</th>
            <th>Seat Class</th>
            <th>Price (PKR)</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($shows) > 0): ?>
            <?php foreach($shows as $show): ?>
                <tr>
                    <td><?= $show['show_id'] ?></td>
                    <td><?= htmlspecialchars($show['movie_title']) ?></td>
                    <td><?= htmlspecialchars($show['cinema_name']) ?></td>
                    <td><?= htmlspecialchars($show['show_date']) ?></td>
                    <td><?= date('h:i A', strtotime($show['show_time'])) ?></td>
                    <td><?= htmlspecialchars($show['seat_class']) ?></td>
                    <td><?= number_format($show['price']) ?></td>
                    <td>
                        <div class="btn-group d-flex justify-content-center" style="gap:10px;">
                            <a href="view.php?id=<?= $show['show_id'] ?>" 
                               class="btn btn-info btn-sm text-white" title="View Show">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="edit.php?id=<?= $show['show_id'] ?>" 
                               class="btn btn-warning btn-sm text-white" title="Edit Show">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a href="delete.php?id=<?= $show['show_id'] ?>" 
                               class="btn btn-danger btn-sm" title="Delete Show"
                               onclick="return confirm('Are you sure you want to delete this show?');">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" class="text-center text-muted">No shows available.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<a href="add.php" class="btn btn-danger mt-3">Add New Show</a>
<a href="../../dashboard.php" class="btn btn-secondary mt-3">Go Back</a>

<?php include '../../includes/footer.php'; ?>
