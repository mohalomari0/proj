<?php
session_start();
include '../../../db.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cart_id = $_POST['cart_id'];
    $quantity = $_POST['quantity'];
    $user_id = $_SESSION['user_id'];

    // التحقق من أن الكمية صحيحة
    if ($quantity > 0) {
        // تحديث الكمية في السلة
        $update_query = "UPDATE cart SET quantity = $quantity WHERE id = $cart_id AND user_id = $user_id";
        if (mysqli_query($conn, $update_query)) {
            $_SESSION['message'] = "Cart updated successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error: " . mysqli_error($conn);
            $_SESSION['message_type'] = "danger";
        }
    } else {
        $_SESSION['message'] = "Quantity must be greater than 0.";
        $_SESSION['message_type'] = "danger";
    }
}

header("Location: cart.php");
exit();
?>