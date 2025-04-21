<?php
session_start(); // بدء الجلسة
include '../../db.php'; // تأكد من أن ملف db.php يحتوي على اتصال قاعدة البيانات

// معالجة حذف المنتج
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $query = "DELETE FROM products WHERE id = $delete_id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Product deleted successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error deleting product: " . mysqli_error($conn);
        $_SESSION['message_type'] = "danger";
    }
    header("Location: products.php"); // إعادة التوجيه لتحديث الصفحة
    exit();
}

// التحقق مما إذا كان هناك تصنيف معين مُرسل عبر GET
$category_filter = isset($_GET['category']) ? intval($_GET['category']) : null;

// جلب المنتجات بناءً على الفئة المحددة
$query = "SELECT * FROM products";
if (!empty($category_filter)) {
    $query .= " WHERE category_id = $category_filter";
}

$result = mysqli_query($conn, $query);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .edit-mode input {
            width: 100%;
            padding: 5px;
            box-sizing: border-box;
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
            margin-bottom: 20px; /* تباعد أسفل النافبار */
        }
        
    </style>

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
    <div class="main-content mt-5 mb-5">
        <h1 class="text-center">Products Management</h1>

        <!-- عرض رسائل الجلسة -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type']; ?>">
                <?php echo $_SESSION['message']; ?>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
        <?php endif; ?>

        <?php include "./mid_nav.php"; ?>

        <!-- زر إضافة منتج جديد -->
        <!-- <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addProductModal">
            Add New Product
        </button> -->

        <!-- جدول عرض المنتجات -->
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
                            <td><input type="text" class="form-control" name="name" value="<?php echo $product['name']; ?>" disabled></td>
                            <td><input type="text" class="form-control" name="description" value="<?php echo $product['description']; ?>" disabled></td>
                            <td><input type="number" step="0.01" class="form-control" name="price" value="<?php echo $product['price']; ?>" disabled></td>
                            <td><input type="number" class="form-control" name="stock" value="<?php echo $product['stock']; ?>" disabled></td>
                            <td><img src="<?php echo $product['image']; ?>" alt="Product Image" style="width: 100px;"></td>
                            <td>
                                <button class="btn btn-warning btn-sm btn-edit">Edit</button>
                                <button class="btn btn-success btn-sm btn-confirm" style="display: none;">Confirm</button>
                                <a href="products.php?delete_id=<?php echo $product['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center">No products found in this category.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

<!-- نافذة منبثقة (Modal) لإضافة منتج جديد -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- نموذج إضافة منتج جديد -->
                <form method="POST" action="add_product.php" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="stock" name="stock" required>
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-control" id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            <?php
                            // استعلام لجلب الفئات من جدول categories
                            $categories_query = "SELECT * FROM categories";
                            $categories_result = mysqli_query($conn, $categories_query);
                            while ($category = mysqli_fetch_assoc($categories_result)) {
                                echo "<option value='{$category['id']}'>{$category['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Product Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </form>
            </div>
        </div>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // عند النقر على زر Edit
            $('.btn-edit').on('click', function() {
                const $row = $(this).closest('tr');
                const $inputs = $row.find('input');

                // تفعيل حقول الإدخال
                $inputs.prop('disabled', false);
                $row.addClass('edit-mode');

                // إظهار زر Confirm وإخفاء زر Edit
                $(this).hide();
                $row.find('.btn-confirm').show();
            });

            // عند النقر على زر Confirm
            $('.btn-confirm').on('click', function() {
                const $row = $(this).closest('tr');
                const productId = $row.data('id');
                const $inputs = $row.find('input');

                // جمع البيانات من حقول الإدخال
                const data = {
                    id: productId,
                    name: $row.find('input[name="name"]').val(),
                    description: $row.find('input[name="description"]').val(),
                    price: $row.find('input[name="price"]').val(),
                    stock: $row.find('input[name="stock"]').val()
                };

                console.log("Data being sent:", data); // تحقق من البيانات المرسلة

                // إرسال التعديلات إلى الخادم باستخدام AJAX
                $.ajax({
                    url: 'update_product.php',
                    method: 'POST',
                    data: data,
                    dataType: 'json', // تأكد من أن الخادم يُرجع JSON
                    success: function(response) {
                        console.log("Server response:", response); // تحقق من الرد من الخادم
                        if (response.success) {
                            // تعطيل حقول الإدخال بعد التحديث
                            $inputs.prop('disabled', true);
                            $row.removeClass('edit-mode');

                            // إظهار زر Edit وإخفاء زر Confirm
                            $row.find('.btn-edit').show();
                            $row.find('.btn-confirm').hide();

                            alert('Product updated successfully!');
                        } else {
                            alert('Error updating product: ' + (response.message || 'Unknown error'));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX error:", xhr.responseText); // تحقق من الخطأ
                        alert('Error updating product. Check console for details.');
                    }
                });
            });
        });
    </script>
</body>
</html>