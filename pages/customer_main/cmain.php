<?php
session_start(); // بدء الجلسة لعرض الرسائل
include '../../db.php';

// جلب الفئات من جدول categories
$categories_result = mysqli_query($conn, "SELECT * FROM categories");

// التحقق من وجود category_id في الرابط
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';

// بناء الاستعلام بناءً على category_id المحدد
if ($category_id) {
    $result = mysqli_query($conn, "SELECT * FROM products WHERE category_id = '$category_id'");
} else {
    $result = mysqli_query($conn, "SELECT * FROM products");
}
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

        /* تنسيقات للسايدبار */
        .sidebar {
            width: 250px; /* عرض ثابت للسايدبار */
            background-color: #f8f9fa; /* لون خلفية السايدبار */
            padding: 20px;
            height: 100vh; /* لجعل السايدبار يمتد على طول الصفحة */
            position: fixed; /* لجعل السايدبار ثابتًا */
            top: 0;
            left: 0;
            overflow-y: auto; /* إضافة scroll إذا كان المحتوى طويلاً */
        }

        /* تنسيقات للمحتوى الرئيسي */
        .main-content {
            margin-left: 250px; /* ترك مسافة للسايدبار */
            padding: 20px;
        }

        /* تنسيقات للنافبار */
        .navbar {
            width: 100%;
            background-color: #f8f9fa; /* لون خلفية النافبار */
            padding: 10px 20px;
            position: fixed; /* لجعل النافبار ثابتًا في الأعلى */
            top: 0;
            left: 0;
            z-index: 1000; /* للتأكد من أن النافبار فوق العناصر الأخرى */
        }

        /* تنسيقات لـ mid-nav */
        .mid-nav {
            margin-top: 60px; /* ترك مسافة للنافبار */
            padding: 10px;
            background-color: #e9ecef; /* لون خلفية mid-nav */
            text-align: center;
        }
        .mid-nav a {
            margin: 0 10px;
            text-decoration: none;
            color: #007bff;
        }
        .mid-nav a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- النافبار -->
    <nav class="navbar">
        <?php include "./customer_components/nav.php"; ?>
    </nav>

    <!-- السايدبار -->
    <div class="sidebar">
        <?php include "./customer_components/side.php"; ?>
    </div>

    <!-- mid-nav لعرض الفئات -->
    <div class="mid-nav">
        <a href="?category_id=">All</a>
        <?php while ($category = mysqli_fetch_assoc($categories_result)): ?>
            <a href="?category_id=<?php echo $category['id']; ?>"><?php echo $category['name']; ?></a>
        <?php endwhile; ?>
    </div>

    <!-- المحتوى الرئيسي -->
    <div class="main-content mt-5">
        <div class="container">
            <div class="row">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="/proj/pages/product_admin/<?php echo $row['image']; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row['name']; ?></h5>
                                <p class="card-text"><?php echo $row['description']; ?></p>
                                <p class="card-text"><strong>Price: $<?php echo $row['price']; ?></strong></p>
                                <p class="card-text">Stock: <?php echo $row['stock']; ?></p>
                                <form action="cart/add_to_cart.php" method="POST" onsubmit="return confirmAddToCart()">
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