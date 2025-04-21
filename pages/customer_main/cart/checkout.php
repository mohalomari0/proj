<?php
session_start();
include '../../../db.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// جلب عناصر السلة
$cart_query = "SELECT cart.*, products.name, products.price, products.image 
               FROM cart 
               JOIN products ON cart.product_id = products.id 
               WHERE cart.user_id = $user_id";
$cart_result = mysqli_query($conn, $cart_query);

// حساب المجموع الكلي
$total = 0;
while ($row = mysqli_fetch_assoc($cart_result)) {
    $total += $row['price'] * $row['quantity'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center">Checkout</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                mysqli_data_seek($cart_result, 0); // إعادة تعيين مؤشر النتائج
                while ($row = mysqli_fetch_assoc($cart_result)): ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><img src="<?php echo $row['image']; ?>" width="50" alt="<?php echo $row['name']; ?>"></td>
                        <td>$<?php echo $row['price']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td>$<?php echo $row['price'] * $row['quantity']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="text-end">
            <h4>Total: $<?php echo $total; ?></h4>
        </div>
        <div class="text-center">
            <a href="confirm_order.php" class="btn btn-success">Confirm Order</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>