<?php
include '../../includes/configdb.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $movie_id = intval($_GET['id']);

    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // 1️⃣ Delete booking_details → bookings → shows
        $shows_res = mysqli_query($conn, "SELECT show_id FROM shows WHERE movie_id=$movie_id");
        while ($show = mysqli_fetch_assoc($shows_res)) {
            $show_id = $show['show_id'];

            // Delete booking_details
            mysqli_query($conn, "DELETE FROM booking_details WHERE booking_id IN (SELECT booking_id FROM bookings WHERE show_id=$show_id)");

            // Delete bookings
            mysqli_query($conn, "DELETE FROM bookings WHERE show_id=$show_id");
        }

        // Delete shows
        mysqli_query($conn, "DELETE FROM shows WHERE movie_id=$movie_id");

        // Delete movie_ratings
        mysqli_query($conn, "DELETE FROM movie_ratings WHERE movie_id=$movie_id");

        // Delete the movie
        mysqli_query($conn, "DELETE FROM movies WHERE movie_id=$movie_id");

        // Commit transaction
        mysqli_commit($conn);

        header("Location: list.php?msg=deleted");
        exit;

    } catch (Exception $e) {
        mysqli_rollback($conn);
        die("Error deleting movie: " . $e->getMessage());
    }
}

header("Location: list.php");
exit;
?>
