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

<!-- نافبار التصنيفات -->
<nav class="navbar navbar-expand-lg navbar-light bg-light category-nav">
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
        </div>
    </div>
</nav>