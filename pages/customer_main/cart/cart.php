<?php
session_start();
include '../../../db.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// جلب المنتجات من السلة
$cart_query = "SELECT cart.*, products.name, products.image, products.price 
               FROM cart 
               JOIN products ON cart.product_id = products.id 
               WHERE cart.user_id = ?";
$stmt = mysqli_prepare($conn, $cart_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$cart_result = mysqli_stmt_get_result($stmt);
$cart_items = mysqli_fetch_all($cart_result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include "../customer_components/nav.php"?>


    <div class="container my-5">
        <h1 class="text-center">Your Cart</h1>

        <!-- عرض رسائل الجلسة -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type']; ?> text-center">
                <?php echo $_SESSION['message']; ?>
            </div>
            <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
        <?php endif; ?>

        <?php if ($cart_items): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total_price = 0;
                    foreach ($cart_items as $row): 
                        $subtotal = $row['price'] * $row['quantity'];
                        $total_price += $subtotal;
                    ?>
                        <tr>
                            <td><?php echo $row['name']; ?></td>
                            <td><img src="/proj/pages/product_admin/<?php echo $row['image']; ?>" width="50" alt="<?php echo $row['name']; ?>"></td>
                            <td>$<?php echo $row['price']; ?></td>
                            <td>
                                <form action="update_cart.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="cart_id" value="<?php echo $row['id']; ?>">
                                    <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" min="1" class="form-control" style="width: 80px;">
                                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                </form>
                            </td>
                            <td>$<?php echo $subtotal; ?></td>
                            <td>
                                <a href="remove_from_cart.php?cart_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to remove this item?');">Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3 class="text-end">Total: $<?php echo number_format($total_price, 2); ?></h3>

            <div class="text-center">
                <form action="confirm_order.php" method="POST">
                    <button type="submit" class="btn btn-success">Confirm Order</button>
                </form>
            </div>
        <?php else: ?>
            <p class="text-center">Your cart is empty.</p>
        <?php endif; ?>
    </div>
</body>
</html>
