<?php include '../../includes/configdb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);
    
    // First delete ratings of this user
    mysqli_query($conn, "DELETE FROM movie_ratings WHERE user_id = $user_id");

    // Then delete the user
    mysqli_query($conn, "DELETE FROM users WHERE user_id = $user_id");
}

header("Location: list.php");
exit;
 ?>