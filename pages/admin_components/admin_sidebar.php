<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>
    <style>
        /* تنسيق عام للسايدبار */
        .sidebar {
            height: 100vh; /* ارتفاع كامل للصفحة */
            width: 250px; /* عرض ثابت للسايدبار */
            background-color: #343a40; /* لون خلفية داكن */
            color: white; /* لون النص */
            padding: 20px; /* تباعد داخلي */
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1); /* ظل على الجانب الأيمن */
            position: fixed; /* تثبيت السايدبار */
            top: 0; /* تثبيت من الأعلى */
            left: 0; /* تثبيت من اليسار */
            overflow-y: auto; /* إمكانية التمرير إذا كان المحتوى طويلاً */
        }

        /* تنسيق الروابط */
        .sidebar a {
            color: white; /* لون النص */
            text-decoration: none; /* إزالة الخط التحتي */
            display: block; /* جعل الروابط ككتل */
            padding: 10px 15px; /* تباعد داخلي */
            margin: 5px 0; /* تباعد خارجي */
            border-radius: 5px; /* حواف مستديرة */
            transition: background-color 0.3s ease, color 0.3s ease; /* تأثيرات انتقالية */
        }

        /* تأثيرات التحويم على الروابط */
        .sidebar a:hover {
            background-color: #495057; /* لون خلفية عند التحويم */
            color: #f8f9fa; /* لون النص عند التحويم */
        }

        /* تنسيق القائمة */
        .sidebar ul {
            list-style: none; /* إزالة النقاط من القائمة */
            padding: 0; /* إزالة التباعد الداخلي */
            margin: 0; /* إزالة التباعد الخارجي */
        }

        /* تنسيق عناصر القائمة */
        .sidebar li {
            margin: 10px 0; /* تباعد بين العناصر */
        }

        /* تنسيق العنوان (اختياري) */
        .sidebar h3 {
            text-align: center; /* توسيط النص */
            margin-bottom: 20px; /* تباعد أسفل العنوان */
            font-size: 1.5rem; /* حجم الخط */
            color: #f8f9fa; /* لون النص */
        }
    </style>
</head>

<body>
    <!-- السايدبار -->
    <div class="sidebar">
        <h3>Admin Panel</h3> <!-- عنوان للسايدبار -->
        <ul class="list-unstyled">
            <li><a href="/proj/pages/admin_main.php">Home</a></li>
            <li><a href="/proj/pages/customers_admin/users.php">Users</a></li>
            <li><a href="/proj/pages/product_admin/products.php">Products</a></li>
            <li><a href="/proj/pages/orders_admin/orders.php">Orders</a></li>
            <li><a href="/proj/pages/admin_category/category.php">Category</a></li>
            <li><a href="/proj/pages/admin_offers/offers.php">Offers</a></li>
            <li><a href="/proj/pages/login.php">Logout</a></li>
        </ul>
    </div>
</body>

</html>