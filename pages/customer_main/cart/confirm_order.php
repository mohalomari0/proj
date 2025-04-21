<?php
session_start();
include '../../../db.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['HTTP_REFERER']; // حفظ الصفحة الحالية
    $_SESSION['message'] = "يجب تسجيل الدخول أولاً لتأكيد الطلب.";
    $_SESSION['message_type'] = "warning";
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// حساب إجمالي الطلب
$total_query = "SELECT SUM(cart.quantity * products.price) AS total_price 
                FROM cart 
                JOIN products ON cart.product_id = products.id 
                WHERE cart.user_id = ?";
$stmt = mysqli_prepare($conn, $total_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$total = mysqli_fetch_assoc($result)['total_price'];

if ($total > 0) {
    // إنشاء طلب جديد في `orders`
    $insert_order_query = "INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, 'pending')";
    $stmt = mysqli_prepare($conn, $insert_order_query);
    mysqli_stmt_bind_param($stmt, "id", $user_id, $total);
    mysqli_stmt_execute($stmt);
    $order_id = mysqli_insert_id($conn);

    // نقل المنتجات إلى `order_details`
    $insert_details_query = "INSERT INTO order_details (order_id, product_id, quantity) 
                             SELECT ?, product_id, quantity FROM cart WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $insert_details_query);
    mysqli_stmt_bind_param($stmt, "ii", $order_id, $user_id);
    mysqli_stmt_execute($stmt);

    // تفريغ `cart`
    $delete_cart_query = "DELETE FROM cart WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $delete_cart_query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);

    $_SESSION['message'] = "تم تأكيد الطلب بنجاح!";
    $_SESSION['message_type'] = "success";
} else {
    $_SESSION['message'] = "سلة التسوق فارغة.";
    $_SESSION['message_type'] = "danger";
}

header("Location: cart.php");
exit();
?>