<?php
session_start(); // بدء الجلسة
include '../../db.php'; // تأكد من أن ملف db.php يحتوي على اتصال قاعدة البيانات

// التحقق مما إذا كان هناك تصنيف معين مُرسل عبر GET
$category_filter = isset($_GET['category']) ? intval($_GET['category']) : null;

// جلب المنتجات بناءً على الفئة المحددة
$query = "SELECT * FROM products";
if (!empty($category_filter)) {
    $query .= " WHERE category_id = $category_filter";
}

$result = mysqli_query($conn, $query);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

// جلب جميع التصنيفات من قاعدة البيانات
$category_query = "SELECT * FROM categories";
$category_result = mysqli_query($conn, $category_query);

if (!$category_result) {
    die("Query failed: " . mysqli_error($conn));
}

$categories = mysqli_fetch_all($category_result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* تنسيقات عامة */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }

        .main-content {
            padding: 20px;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 30px;
            color: #333;
        }

        /* تنسيقات نافبار التصنيفات */
        .category-nav {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .category-nav .nav-link {
            color: #333;
            font-weight: 500;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }

        .category-nav .nav-link:hover {
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
        }

        .category-nav .nav-link.active {
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
        }

        /* تنسيقات الكاردات */
        .card {
            margin-bottom: 20px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .card-text {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 5px;
        }

        .card-text strong {
            color: #333;
        }

        /* زر العودة إلى الأعلى */
        #back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: none;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
            text-align: center;
            line-height: 50px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease;
        }

        #back-to-top:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php include "../../nav.php" ?>
    <!-- المحتوى الرئيسي -->
    <div class="main-content">
        <h1 class="text-center">Our Products</h1>

        <!-- نافبار التصنيفات -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light category-nav">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#categoryNavbar" aria-controls="categoryNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="categoryNavbar">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" href="products.php?category=all">All Products</a>
                        </li>
                        <?php if (isset($categories) && is_array($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="products.php?category=<?php echo $category['id']; ?>">
                                        <?php echo $category['name']; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- عرض رسائل الجلسة -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type']; ?>">
                <?php echo $_SESSION['message']; ?>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
        <?php endif; ?>

        <!-- عرض المنتجات في شكل كاردات -->
        <div class="container">
            <div class="row">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-4">
                            <div class="card">
                                <img src="/proj/pages/product_admin/<?php echo $product['image']; ?>" class="card-img-top" alt="Product Image">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $product['name']; ?></h5>
                                    <p class="card-text"><?php echo $product['description']; ?></p>
                                    <p class="card-text"><strong>Price:</strong> $<?php echo $product['price']; ?></p>
                                    <p class="card-text"><strong>Stock:</strong> <?php echo $product['stock']; ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <p class="text-center">No products found in this category.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- زر العودة إلى الأعلى -->
        <button id="back-to-top" title="Go to top">↑</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // زر العودة إلى الأعلى
        $(document).ready(function() {
            $(window).scroll(function() {
                if ($(this).scrollTop() > 100) {
                    $('#back-to-top').fadeIn();
                } else {
                    $('#back-to-top').fadeOut();
                }
            });

            $('#back-to-top').click(function() {
                $('html, body').animate({ scrollTop: 0 }, 'slow');
                return false;
            });
        });
    </script>
</body>
</html>