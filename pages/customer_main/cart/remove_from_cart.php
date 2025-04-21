<?php
session_start();
include '../../../db.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['cart_id'])) {
    $cart_id = $_GET['cart_id'];
    $user_id = $_SESSION['user_id'];

    // حذف العنصر من السلة
    $delete_query = "DELETE FROM cart WHERE id = $cart_id AND user_id = $user_id";
    if (mysqli_query($conn, $delete_query)) {
        $_SESSION['message'] = "Item removed from cart successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error: " . mysqli_error($conn);
        $_SESSION['message_type'] = "danger";
    }
}

header("Location: cart.php");
exit();
?>