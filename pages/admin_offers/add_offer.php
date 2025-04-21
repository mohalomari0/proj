<?php
session_start();
include '../../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $original_price = $_POST['original_price'];
    $offer_price = $_POST['offer_price'];

    // جلب اسم المنتج
    $query = "SELECT name FROM products WHERE id = $product_id";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);
    $product_name = $product['name'];

    // إضافة العرض إلى جدول الخصومات
    $query = "INSERT INTO product_offers (product_id, product_name, original_price, offer_price) VALUES ('$product_id', '$product_name', '$original_price', '$offer_price')";
    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Offer added successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error adding offer: " . mysqli_error($conn);
        $_SESSION['message_type'] = "danger";
    }
    header("Location: offers.php");
    exit();
}
?>