<?php
include '../../includes/configdb.php';
include '../../includes/header.php';
?>

<div class="container mt-5">
    <h2>All Users</h2>
    <div class="d-flex justify-content-between mb-3">
        <a href="add.php" class="btn btn-primary">+ Add User</a>
        <a href="\movie-booking-structure\admin\dashboard.php" class="btn btn-dark">Go Back</a>
    </div>    

    <?php
    // Alert messages
    if (isset($_GET['msg'])) {
        $msg = htmlspecialchars($_GET['msg']);
        $alertClass = isset($_GET['type']) && $_GET['type'] === 'error' ? 'alert-danger' : 'alert-success';
        echo "<div class='alert $alertClass alert-dismissible fade show' role='alert'>
                $msg
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
    }
    ?>

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Age</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT u.user_id, u.name, u.email, u.age, r.role_name
                          FROM users u
                          LEFT JOIN roles r ON u.role_id = r.role_id
                          ORDER BY u.user_id ASC";
                $result = mysqli_query($conn, $query);
                $count = 1;

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <tr>
                            <td><?= $count++; ?></td>
                            <td><?= htmlspecialchars($row['name']); ?></td>
                            <td><?= htmlspecialchars($row['email']); ?></td>
                            <td><?= intval($row['age']); ?></td>
                            <td><?= htmlspecialchars($row['role_name']); ?></td>
                         <td>
                        <div class="d-flex justify-content-center" style="gap:15px;">
                            <a href="edit.php?id=<?= $row['user_id'] ?>" 
                               class="btn btn-warning btn-sm text-white" title="Edit User">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a href="delete.php?id=<?= $row['user_id'] ?>" 
                               class="btn btn-danger btn-sm" title="Delete User"
                               onclick="return confirm('Are you sure you want to delete this user?');">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                    </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo '<tr><td colspan="6" class="text-center">No users found.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

</div>

<?php
include '../../includes/footer.php';
?>
