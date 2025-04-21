<?php
session_start(); // بدء الجلسة
include '../../db.php'; // تأكد من أن ملف db.php يحتوي على اتصال قاعدة البيانات

// معالجة حذف الخصم
if (isset($_GET['delete_offer_id'])) {
    $delete_offer_id = $_GET['delete_offer_id'];
    $query = "DELETE FROM product_offers WHERE id = $delete_offer_id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Offer deleted successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error deleting offer: " . mysqli_error($conn);
        $_SESSION['message_type'] = "danger";
    }
    header("Location: offers.php"); // إعادة التوجيه لتحديث الصفحة
    exit();
}

// التحقق مما إذا كان هناك تصنيف معين مُرسل عبر GET
$category_filter = isset($_GET['category']) ? intval($_GET['category']) : null;

// جلب جميع المنتجات التي ليس عليها خصومات بناءً على التصنيف المحدد
$query = "SELECT p.* FROM products p 
          LEFT JOIN product_offers po ON p.id = po.product_id 
          WHERE po.product_id IS NULL";
if (!empty($category_filter)) {
    $query .= " AND p.category_id = $category_filter";
}
$result = mysqli_query($conn, $query);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

// جلب المنتجات التي عليها خصومات بناءً على التصنيف المحدد
$query_offers = "SELECT po.*, p.name AS product_name 
                 FROM product_offers po 
                 JOIN products p ON po.product_id = p.id";
if (!empty($category_filter)) {
    $query_offers .= " WHERE p.category_id = $category_filter";
}
$result_offers = mysqli_query($conn, $query_offers);
$offers = mysqli_fetch_all($result_offers, MYSQLI_ASSOC);

// جلب جميع التصنيفات من قاعدة البيانات
$category_query = "SELECT * FROM categories";
$category_result = mysqli_query($conn, $category_query);
$categories = mysqli_fetch_all($category_result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- لأيقونات Font Awesome -->
    <style>
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
            margin-bottom: 20px; /* تباعد أسفل النافبار */
        }

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
    <link rel="stylesheet" href="/proj/admin_styles.css">
</head>
<body>
    <!-- النافبار -->
    <nav class="navbar">
        <?php include "../admin_components/navbar.php"; ?>
    </nav>

    <!-- السايدبار -->
    <div class="sidebar">
        <?php include "../admin_components/admin_sidebar.php"; ?>
    </div>

    <!-- المحتوى الرئيسي -->
    <div class="main-content">
        <h1 class="text-center mt-5 mb-3">Offers Management</h1>

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
                            <a class="nav-link active" href="offers.php?category=all">All Products</a>
                        </li>
                        <!-- تصنيفات المنتجات -->
                        <?php if (isset($categories) && is_array($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="offers.php?category=<?php echo $category['id']; ?>">
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

        <!-- جدول عرض المنتجات التي عليها خصومات -->
        <h2>Products with Offers</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Original Price</th>
                    <th>Offer Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($offers)): ?>
                    <?php foreach ($offers as $offer): ?>
                        <tr>
                            <td><?php echo $offer['id']; ?></td>
                            <td><?php echo $offer['product_name']; ?></td>
                            <td><?php echo $offer['original_price']; ?></td>
                            <td><?php echo $offer['offer_price']; ?></td>
                            <td>
                                <a href="offers.php?delete_offer_id=<?php echo $offer['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to remove this offer?')">Remove Offer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center">No offers found in this category.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- جدول عرض جميع المنتجات التي ليس عليها خصومات -->
        <h2>Products without Offers</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <tr data-id="<?php echo $product['id']; ?>">
                            <td><?php echo $product['id']; ?></td>
                            <td><?php echo $product['name']; ?></td>
                            <td><?php echo $product['description']; ?></td>
                            <td><?php echo $product['price']; ?></td>
                            <td><?php echo $product['stock']; ?></td>
                            <td>
                                <img src="/proj/pages/product_admin/<?php echo $product['image']; ?>" alt="Product Image" style="width: 100px;">
                            </td>
                            <td>
                                <button class="btn btn-info btn-sm btn-offer" data-bs-toggle="modal" data-bs-target="#addOfferModal" data-product-id="<?php echo $product['id']; ?>" data-product-price="<?php echo $product['price']; ?>">Add Offer</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center">No products found in this category.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- نافذة منبثقة (Modal) لإضافة عرض (Offer) -->
    <div class="modal fade" id="addOfferModal" tabindex="-1" aria-labelledby="addOfferModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addOfferModalLabel">Add Offer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- نموذج إضافة عرض (Offer) -->
                    <form method="POST" action="add_offer.php">
                        <input type="hidden" id="product_id" name="product_id">
                        <div class="mb-3">
                            <label for="original_price" class="form-label">Original Price</label>
                            <input type="number" step="0.01" class="form-control" id="original_price" name="original_price" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="offer_price" class="form-label">Offer Price</label>
                            <input type="number" step="0.01" class="form-control" id="offer_price" name="offer_price" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Offer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // عند النقر على زر Add Offer
            $('.btn-offer').on('click', function() {
                const productId = $(this).data('product-id');
                const productPrice = $(this).data('product-price');

                // تعبئة البيانات في النافذة المنبثقة
                $('#product_id').val(productId);
                $('#original_price').val(productPrice);
            });
        });
    </script>
</body>
</html>