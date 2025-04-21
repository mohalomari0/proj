<?php
session_start(); // بدء الجلسة لعرض الرسائل
include '../db.php';
$result = mysqli_query($conn, "SELECT * FROM products");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home Appliances Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* تنسيقات مخصصة للكاردات */
        .card {
            height: 100%; /* ارتفاع ثابت للكارد */
            display: flex;
            flex-direction: column;
        }
        .card-img-top {
            height: 200px; /* ارتفاع ثابت للصورة */
            object-fit: cover; /* لتجنب تشويه الصورة */
        }
        .card-body {
            flex-grow: 1; /* لجعل محتوى الكارد يتمدد لملء المساحة المتبقية */
            display: flex;
            flex-direction: column;
            justify-content: space-between; /* توزيع العناصر بشكل متساوٍ */
        }
        .card-title, .card-text {
            margin-bottom: 0.5rem; /* تباعد بين العناصر */
        }
        .btn-primary {
            width: 100%; /* زر يأخذ العرض الكامل */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center my-4">Home Appliances Store</h1>

        <!-- زر عرض السلة -->
        <div class="text-end mb-4">
            <a href="cart.php" class="btn btn-success">View Cart</a>
        </div>

        <!-- عرض رسائل الجلسة -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type']; ?> text-center">
                <?php echo $_SESSION['message']; ?>
            </div>
            <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
        <?php endif; ?>

        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                    <img src="/proj/pages/product_admin/uploads/<?php echo $row['image']; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>">
                    <div class="card-body">
                            <h5 class="card-title"><?php echo $row['name']; ?></h5>
                            <p class="card-text"><?php echo $row['description']; ?></p>
                            <p class="card-text"><strong>Price: $<?php echo $row['price']; ?></strong></p>
                            <p class="card-text">Stock: <?php echo $row['stock']; ?></p>
                            <form action="add_to_cart.php" method="POST" onsubmit="return confirmAddToCart()">
                                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                <input type="number" name="quantity" value="1" min="1" max="<?php echo $row['stock']; ?>" class="form-control mb-2">
                                <button type="submit" class="btn btn-primary">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // دالة لتأكيد إضافة المنتج إلى السلة
        function confirmAddToCart() {
            if (confirm("Are you sure you want to add this product to your cart?")) {
                // إذا تم التأكيد، قم بإرسال النموذج
                return true;
            } else {
                // إذا تم الإلغاء، لا تقم بإرسال النموذج
                return false;
            }
        }
    </script>
</body>
</html>