<?php
include "../../db.php"; 

// معالجة إضافة فئة جديدة
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["category_name"])) {
    $categoryName = $_POST["category_name"];

    $sql = "INSERT INTO categories (NAME) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $categoryName);
    $stmt->execute();
    $stmt->close();
}

// معالجة حذف الفئة
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $query = "DELETE FROM categories WHERE id = $delete_id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Category deleted successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error deleting category: " . mysqli_error($conn);
        $_SESSION['message_type'] = "danger";
    }
    header("Location: category.php"); // إعادة التوجيه لتحديث الصفحة
    exit();
}

// جلب جميع الفئات
$sql = "SELECT * FROM categories";
$result = $conn->query($sql);

// تحقق من وجود بيانات
if ($result && $result->num_rows > 0) {
    $rows = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $rows = []; // إذا لم تكن هناك بيانات، قم بتعيين $rows كمصفوفة فارغة
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        /* تنسيق السايدبار */
        .sidebar {
            width: 250px;
            background-color: #f8f9fa;
            padding: 20px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
        }

        /* تنسيق النافبار */
        .navbar {
            width: calc(100% - 250px); /* العرض الكلي ناقص عرض السايدبار */
            margin-left: 250px; /* إزاحة النافبار ليكون بجانب السايدبار */
            position: fixed;
            top: 0;
            z-index: 1000;
        }

        /* تنسيق المحتوى الرئيسي */
        .main-content {
            margin-left: 250px; /* إزاحة المحتوى الرئيسي ليكون بجانب السايدبار */
            margin-top: 60px; /* إزاحة المحتوى الرئيسي ليكون تحت النافبار */
            padding: 20px;
        }

        /* تنسيق الجدول */
        .custom-table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .custom-table th, .custom-table td {
            padding: 12px;
            text-align: center;
        }
        .custom-table thead {
            background-color: rgb(119, 120, 121);
            color: white;
        }

        /* تنسيق زر الإضافة */
        .btn-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- النافبار -->
        <?php include "../admin_components/navbar.php"; ?>

        <!-- السايدبار -->
        <div class="sidebar">
            <?php include "../admin_components/admin_sidebar.php"; ?>
        </div>

        <!-- المحتوى الرئيسي -->
        <div class="main-content">
        <h1 class="text-center mt-5">Categories Management</h1>

            <table class="table custom-table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">NAME</th>
                        <th scope="col">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($rows)): ?>
                        <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= $row["id"] ?></td>
                            <td><?= $row["name"] ?></td>
                            <td>
                                <a href="edit_category.php?id=<?= $row["id"] ?>" class="btn btn-warning">Edit</a>
                                <a href="category.php?delete_id=<?= $row["id"] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">No categories found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- زر Add Category -->
            <div class="btn-container">
                <button type="button" class="btn btn-success" onclick="showForm()">Add Category</button>
            </div>

            <!-- نموذج إضافة فئة -->
            <div id="addCategoryForm" style="display: none; margin-top: 20px; text-align: center;">
                <form method="POST" action="">
                    <input type="text" name="category_name" placeholder="Enter category name" required>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // دالة لإظهار النموذج
        function showForm() {
            document.getElementById("addCategoryForm").style.display = "block";
        }
    </script>
</body>
</html>