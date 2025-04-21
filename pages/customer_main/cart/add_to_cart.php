<?php
session_start();
include '../../../db.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    $_SESSION['message'] = "يجب عليك تسجيل الدخول قبل إضافة المنتجات إلى السلة!";
    $_SESSION['message_type'] = "warning";
    
    // إعادة المستخدم إلى صفحة تسجيل الدخول
    header("Location: login.php");
    exit();
}

// إذا كان المستخدم مسجلاً للدخول، يتم تنفيذ باقي الكود
$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

// التحقق مما إذا كان المنتج موجودًا بالفعل في السلة
$check_query = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
$stmt = mysqli_prepare($conn, $check_query);
mysqli_stmt_bind_param($stmt, "ii", $user_id, $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$cart_item = mysqli_fetch_assoc($result);

if ($cart_item) {
    // تحديث الكمية إذا كان المنتج موجودًا
    $new_quantity = $cart_item['quantity'] + $quantity;
    $update_query = "UPDATE cart SET quantity = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "ii", $new_quantity, $cart_item['id']);
} else {
    // إضافة المنتج إلى السلة
    $insert_query = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, "iii", $user_id, $product_id, $quantity);
}

// تنفيذ الاستعلام
if (mysqli_stmt_execute($stmt)) {
    // تحديث الـ stock للمنتج
    $update_stock_query = "UPDATE products SET stock = stock - ? WHERE id = ?";
    $stmt_stock = mysqli_prepare($conn, $update_stock_query);
    mysqli_stmt_bind_param($stmt_stock, "ii", $quantity, $product_id);
    
    if (mysqli_stmt_execute($stmt_stock)) {
        $_SESSION['message'] = "تمت إضافة المنتج إلى السلة بنجاح!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "فشل في تحديث كمية المنتج في المخزن.";
        $_SESSION['message_type'] = "danger";
    }
} else {
    $_SESSION['message'] = "فشل في إضافة المنتج إلى السلة.";
    $_SESSION['message_type'] = "danger";
}

// إعادة التوجيه إلى صفحة المنتجات
header("Location: ../cmain.php");
exit();
?>