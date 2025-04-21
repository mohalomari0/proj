<?php
session_start(); // بدء الجلسة
include '../../db.php'; // تأكد من أن ملف db.php يحتوي على اتصال قاعدة البيانات

// معالجة حذف الأوردر
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $query = "DELETE FROM orders WHERE id = $delete_id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Order deleted successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error deleting order: " . mysqli_error($conn);
        $_SESSION['message_type'] = "danger";
    }
    header("Location: orders.php"); // إعادة التوجيه لتحديث الصفحة
    exit();
}

// التحقق مما إذا كان هناك حالة معينة مُرسلة عبر GET
$status_filter = isset($_GET['status']) ? $_GET['status'] : null;

// جلب الأوردرات بناءً على الحالة المحددة
$query = "SELECT * FROM orders";
if (!empty($status_filter)) {
    $query .= " WHERE status = '$status_filter'";
}

$result = mysqli_query($conn, $query);
$orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

     /* تنسيقات للنافبار الثانوية */
     .mid-nav {
            background-color: #2c3e50; /* لون خلفية داكن */
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
            border-radius: 5px; /* زوايا مدورة */
        }
        .mid-nav a {
            margin: 0 10px;
            text-decoration: none;
            color: #ecf0f1; /* لون النص الفاتح */
            font-weight: bold; /* نص عريض */
            transition: color 0.3s ease; /* تأثير انتقالي لتغيير اللون */
        }
        .mid-nav a:hover {
            color: #3498db; /* لون النص عند التمرير */
        }
        .mid-nav a.active {
            color: #3498db; /* لون النص للرابط النشط */
            text-decoration: underline; /* خط تحتي للرابط النشط */
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
    <div class="main-content">
        <h1 class="text-center mt-5">Orders Management</h1>

        <!-- عرض رسائل الجلسة -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type']; ?>">
                <?php echo $_SESSION['message']; ?>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
        <?php endif; ?>

        <!-- النافبار الثانوية للفلترة بحسب الحالة -->
        <div class="mid-nav">
            <a href="orders.php">All Orders</a>
            <a href="orders.php?status=pending">Pending</a>
            <a href="orders.php?status=shipped">Shipped</a>
            <a href="orders.php?status=delivered">Delivered</a>
            <a href="orders.php?status=cancelled">Cancelled</a>
        </div>

        <!-- جدول عرض الأوردرات -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr data-status="<?php echo $order['status']; ?>">
                            <td><?php echo $order['id']; ?></td>
                            <td><?php echo $order['user_id']; ?></td>
                            <td><?php echo $order['total_price']; ?></td>
                            <td><?php echo $order['status']; ?></td>
                            <td><?php echo $order['created_at']; ?></td>
                            <td>
                                <a href="orders.php?delete_id=<?php echo $order['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this order?')">Delete</a>
                                <a href="update.php?id=<?php echo $order['id']; ?>" class="btn btn-warning btn-sm">Update</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center">No orders found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // عند النقر على رابط في النافبار الثانوية
            $('.mid-nav a').on('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('href');
                window.location.href = url; // إعادة التوجيه إلى الرابط المحدد
            });
        });
    </script>
</body>
</html>