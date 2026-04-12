<?php
session_start();
include 'includes/configdb.php';

// Only admin
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];

// Fetch admin info
$stmt = $conn->prepare("SELECT name, email FROM users WHERE user_id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
?>
<?php include 'includes/header.php'; ?>

<style>
.profile-card {
    max-width: 500px;
    margin: 60px auto;
    padding: 30px;
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    text-align: center;
}
.profile-card h2 {
    margin-bottom: 20px;
    font-weight: 700;
    color: #222;
}
.profile-card p {
    font-size: 16px;
    margin: 10px 0;
    color: #555;
}
.profile-card .btn-edit {
    margin-top: 20px;
    padding: 10px 25px;
    border-radius: 8px;
    background-color: #e63946;
    color: #fff;
    border: none;
    cursor: pointer;
    transition: 0.3s;
}
.profile-card .btn-edit:hover {
    background-color: #d62839;
}
</style>

<div class="profile-card">
    <h2><?= htmlspecialchars($admin['name']); ?></h2>
    <p><strong>Email:</strong> <?= htmlspecialchars($admin['email']); ?></p>
    <a href="edit_profile.php" class="btn-edit">Edit Profile</a>
</div>

<?php include 'includes/footer.php'; ?>
