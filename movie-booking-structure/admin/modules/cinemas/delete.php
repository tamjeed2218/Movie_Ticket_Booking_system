<?php
include '../../includes/configdb.php';

$id = intval($_GET['id']); // sanitize input

// Begin transaction (optional, safer)
mysqli_begin_transaction($conn);

try {
    // 1. Delete booking_details → bookings → shows
    $shows_res = mysqli_query($conn, "SELECT show_id FROM shows WHERE cinema_id=$id");
    while ($show = mysqli_fetch_assoc($shows_res)) {
        $show_id = $show['show_id'];
        
        // Delete booking_details
        mysqli_query($conn, "DELETE FROM booking_details WHERE booking_id IN (SELECT booking_id FROM bookings WHERE show_id=$show_id)");
        
        // Delete bookings
        mysqli_query($conn, "DELETE FROM bookings WHERE show_id=$show_id");
    }

    // Delete shows
    mysqli_query($conn, "DELETE FROM shows WHERE cinema_id=$id");

    // 2. Delete user_reviews for this cinema
    mysqli_query($conn, "DELETE FROM user_reviews WHERE cinema_id=$id");

    // 3. Delete cinema
    mysqli_query($conn, "DELETE FROM cinemas WHERE cinema_id=$id");

    // Commit transaction
    mysqli_commit($conn);

    header("Location: list.php?msg=deleted");
    exit;
} catch (Exception $e) {
    mysqli_rollback($conn);
    die("Error deleting cinema: " . $e->getMessage());
}
?>
