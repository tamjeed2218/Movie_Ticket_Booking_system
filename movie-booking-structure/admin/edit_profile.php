<?php
session_start();
include 'includes/configdb.php';

// Only admin
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];

// Fetch current admin info
$stmt = $conn->prepare("SELECT name, email, password FROM users WHERE user_id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// Handle update
if (isset($_POST['update'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = !empty($_POST['password'])
        ? password_hash($_POST['password'], PASSWORD_DEFAULT)
        : $admin['password'];

    $update = $conn->prepare("UPDATE users SET name=?, email=?, password=? WHERE user_id=?");
    $update->bind_param("sssi", $name, $email, $password, $admin_id);
    $update->execute();

    // Redirect back to profile page
    header("Location: profile.php");
    exit();
}
?>
<?php include 'includes/header.php'; ?>

<style>
.edit-card {
    max-width: 500px;
    margin: 60px auto;
    padding: 30px;
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}
.edit-card h2 {
    text-align: center;
    margin-bottom: 25px;
    font-weight: 700;
    color: #222;
}
.edit-card label {
    font-weight: 600;
    color: #333;
}
.edit-card input {
    width: 100%;
    padding: 12px;
    margin: 8px 0 20px 0;
    border-radius: 8px;
    border: 1px solid #ccc;
}
.edit-card input:focus {
    border-color: #e63946;
    outline: none;
}
.edit-card .btn-update {
    padding: 12px 25px;
    border-radius: 8px;
    border: none;
    background-color: #e63946;
    color: #fff;
    cursor: pointer;
    transition: 0.3s;
}
.edit-card .btn-update:hover {
    background-color: #d62839;
}
.edit-card .btn-back {
    margin-left: 10px;
    padding: 12px 25px;
    border-radius: 8px;
    background-color: #6c757d;
    color: #fff;
    text-decoration: none;
}
.edit-card .btn-back:hover {
    background-color: #5a6268;
}
</style>

<div class="edit-card">
    <h2>Edit Profile</h2>
    <form method="POST">
        <div>
            <label>Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($admin['name']); ?>" required>
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($admin['email']); ?>" required>
        </div>
        <div>
            <label>Change Password</label>
            <input type="password" name="password" placeholder="Leave blank to keep current password">
        </div>
        <div style="text-align:center;">
            <button type="submit" name="update" class="btn-update">Update Profile</button>
            <a href="profile.php" class="btn-back">Cancel</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
