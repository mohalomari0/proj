<?php
include "../../db.php";

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $query = "DELETE FROM users WHERE id = $user_id";
    mysqli_query($conn, $query);
}

header("Location: users.php");
exit();
?>