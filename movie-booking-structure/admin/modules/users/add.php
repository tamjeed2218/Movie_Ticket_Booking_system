<?php
include '../../includes/configdb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $age = intval($_POST['age']);
    $role_id = intval($_POST['role_id']);

    if (!empty($name) && !empty($email) && !empty($age) && !empty($role_id)) {
        $insertQuery = "INSERT INTO users (name, email, age, role_id) VALUES ('$name', '$email', $age, $role_id)";
        mysqli_query($conn, $insertQuery);
    }

    header("Location: list.php"); // redirect to list page
    exit;
}

include '../../includes/header.php';
?>

<div class="container mt-4">
    <h3>Add User</h3>

    <form method="POST" class="mt-3">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Age</label>
            <input type="number" name="age" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Role</label>
            <select name="role_id" class="form-control" required>
                <option value="">Select Role</option>
                <?php
                $roles = mysqli_query($conn, "SELECT role_id, role_name FROM roles");
                while ($r = mysqli_fetch_assoc($roles)) {
                    echo "<option value='{$r['role_id']}'>{$r['role_name']}</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-danger">Add User</button>
        <a href="list.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>
