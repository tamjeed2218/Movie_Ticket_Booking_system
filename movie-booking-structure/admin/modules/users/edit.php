<?php
include '../../includes/configdb.php';

if (!isset($_GET['id'])) {
    header("Location: list.php");
    exit;
}

$user_id = intval($_GET['id']);

$userQuery = "SELECT * FROM users WHERE user_id=$user_id LIMIT 1";
$userResult = mysqli_query($conn, $userQuery);

if (!$userResult || mysqli_num_rows($userResult) === 0) {
    header("Location: list.php");
    exit;
}

$user = mysqli_fetch_assoc($userResult);
$name = $user['name'];
$email = $user['email'];
$age = $user['age'];
$role_id = $user['role_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $age = intval($_POST['age']);
    $role_id = intval($_POST['role_id']);

    if (!empty($name) && !empty($email) && !empty($age) && !empty($role_id)) {
        $updateQuery = "UPDATE users SET name='$name', email='$email', age=$age, role_id=$role_id WHERE user_id=$user_id";
        mysqli_query($conn, $updateQuery);
    }

    header("Location: list.php");
    exit;
}

include '../../includes/header.php';
?>

<div class="container mt-4">
    <h3>Edit User</h3>

    <form method="POST" class="mt-3">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($name); ?>" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email); ?>" required>
        </div>
        <div class="mb-3">
            <label>Age</label>
            <input type="number" name="age" class="form-control" value="<?= htmlspecialchars($age); ?>" required>
        </div>
        <div class="mb-3">
            <label>Role</label>
            <select name="role_id" class="form-control" required>
                <option value="">Select Role</option>
                <?php
                $roles = mysqli_query($conn, "SELECT role_id, role_name FROM roles");
                while ($r = mysqli_fetch_assoc($roles)) {
                    $selected = $role_id == $r['role_id'] ? 'selected' : '';
                    echo "<option value='{$r['role_id']}' $selected>{$r['role_name']}</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Update User</button>
        <a href="list.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>
