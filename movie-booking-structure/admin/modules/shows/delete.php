<?php
include '../../includes/configdb.php';

if (!isset($_GET['id'])) {
    header('Location: list.php');
    exit;
}

$show_id = intval($_GET['id']);

// Check if bookings exist for this show
$check = $conn->prepare("SELECT COUNT(*) FROM bookings WHERE show_id = ?");
$check->bind_param("i", $show_id);
$check->execute();
$check->bind_result($count);
$check->fetch();
$check->close();

if ($count > 0) {
    header("Location: list.php?error=bookings");
    exit;
}


// If no bookings → delete
$stmt = $conn->prepare("DELETE FROM shows WHERE show_id = ?");
$stmt->bind_param("i", $show_id);

if ($stmt->execute()) {
    header('Location: list.php?msg=deleted');
    exit;
} else {
    header('Location: list.php?error=delete_failed');
    exit;
}

$stmt->close();
$conn->close();
?>
