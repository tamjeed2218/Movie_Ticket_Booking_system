<?php
include '../../includes/configdb.php';

if (isset($_GET['id'])) {
    $review_id = intval($_GET['id']);
    $conn->query("DELETE FROM user_reviews WHERE review_id=$review_id");
}

header("Location:list.php");
exit;
