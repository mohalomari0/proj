<?php
// تضمين ملف الاتصال بقاعدة البيانات
include '../../db.php';

// جلب جميع التصنيفات من قاعدة البيانات
$category_query = "SELECT * FROM categories";
$category_result = mysqli_query($conn, $category_query);

// التحقق من نجاح الاستعلام
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- لأيقونات Font Awesome -->
    <style>
        /* تنسيقات category-nav */
        .category-nav {
            background-color: #2c3e50; /* لون خلفية داكن */
            padding: 10px 20px; /* تباعد داخلي */
            border-radius: 5px; /* زوايا مدورة */
            margin-bottom: 20px; /* تباعد أسفل category-nav */
        }

        .category-nav .navbar-nav .nav-link {
            color: #ecf0f1; /* لون النص الفاتح */
            font-weight: bold; /* نص عريض */
            margin-right: 15px; /* تباعد بين الروابط */
            transition: color 0.3s ease; /* تأثير انتقالي لتغيير اللون */
        }

        .category-nav .navbar-nav .nav-link:hover {
            color: #3498db; /* لون النص عند التمرير */
        }

        .category-nav .navbar-nav .nav-link.active {
            color: #3498db; /* لون النص للرابط النشط */
            text-decoration: underline; /* خط تحتي للرابط النشط */
        }

        .category-nav .btn-primary {
            background-color: #3498db; /* لون زر الإضافة */
            border-color: #3498db; /* لون حدود الزر */
            font-weight: bold; /* نص عريض */
            transition: background-color 0.3s ease; /* تأثير انتقالي لتغيير اللون */
        }

        .category-nav .btn-primary:hover {
            background-color: #2980b9; /* لون الزر عند التمرير */
            border-color: #2980b9; /* لون حدود الزر عند التمرير */
        }
    </style>
</head>
<body>
    <!-- نافبار التصنيفات -->
    <nav class="navbar navbar-expand-lg navbar-light category-nav">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#categoryNavbar" aria-controls="categoryNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="categoryNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <!-- زر "All Products" -->
                    <li class="nav-item">
                        <a class="nav-link active" href="products.php?category=all">All Products</a>
                    </li>
                    <!-- تصنيفات المنتجات -->
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
                <!-- زر إضافة منتج جديد -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    <i class="fas fa-plus"></i> Add New
                </button>
            </div>
        </div>
    </nav>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>