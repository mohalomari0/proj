<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* تنسيق عام للنافبار */
        .navbar {
            background-color: #343a40; /* لون خلفية داكن */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* ظل أسفل النافبار */
            padding: 10px 0; /* تباعد داخلي */
        }

        /* تنسيق العلامة التجارية (Brand) */
        .navbar-brand {
            color: #f8f9fa !important; /* لون النص */
            font-size: 1.5rem; /* حجم الخط */
            font-weight: bold; /* سمك الخط */
            transition: color 0.3s ease; /* تأثيرات انتقالية */
        }

        .navbar-brand:hover {
            color: #007bff !important; /* لون النص عند التحويم */
        }

        /* تنسيق الروابط */
        .navbar-nav .nav-link {
            color: #f8f9fa !important; /* لون النص */
            padding: 10px 15px; /* تباعد داخلي */
            margin: 0 5px; /* تباعد خارجي */
            border-radius: 5px; /* حواف مستديرة */
            transition: background-color 0.3s ease, color 0.3s ease; /* تأثيرات انتقالية */
        }

        .navbar-nav .nav-link:hover {
            background-color: #495057; /* لون خلفية عند التحويم */
            color: #007bff !important; /* لون النص عند التحويم */
        }

        /* تنسيق زر التوجيه (Toggler) */
        .navbar-toggler {
            border-color: #f8f9fa; /* لون الحدود */
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(248, 249, 250, 1)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
        }

        /* تنسيق القائمة المنسدلة */
        .dropdown-menu {
            background-color: #343a40; /* لون خلفية القائمة المنسدلة */
            border: none; /* إزالة الحدود */
        }

        .dropdown-item {
            color: #f8f9fa !important; /* لون النص */
            transition: background-color 0.3s ease, color 0.3s ease; /* تأثيرات انتقالية */
        }

        .dropdown-item:hover {
            background-color: #495057; /* لون خلفية عند التحويم */
            color: #007bff !important; /* لون النص عند التحويم */
        }

        /* إخفاء العناصر على الشاشات الكبيرة */
        @media (min-width: 992px) {
            #navbarNav {
                display: none !important; /* إخفاء القائمة على الشاشات الكبيرة */
            }
        }

        /* إظهار العناصر على الشاشات الصغيرة */
        @media (max-width: 991.98px) {
            #navbarNav {
                display: flex !important; /* إظهار القائمة على الشاشات الصغيرة */
                flex-direction: column; /* جعل العناصر تظهر بشكل عمودي */
            }
        }
    </style>
</head>

<body>
    <!-- النافبار -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="/proj/pages/admin_main.php">ElectroHome</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/proj/pages/admin_main.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/proj/pages/customers_admin/users.php">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/proj/pages/product_admin/products.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/proj/pages/orders_admin/orders.php">Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/proj/pages/admin_category/category.php">Category</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/proj/pages/admin_offers/offers.php">Offers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/proj/pages/login.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>