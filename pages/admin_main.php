<?php
session_start();
include '../db.php';

// جلب الإحصائيات العامة
$query_users = "SELECT COUNT(*) as total_users FROM users";
$result_users = mysqli_query($conn, $query_users);
$total_users = mysqli_fetch_assoc($result_users)['total_users'];

$query_admins = "SELECT COUNT(*) as total_admins FROM users WHERE role = 'admin'";
$result_admins = mysqli_query($conn, $query_admins);
$total_admins = mysqli_fetch_assoc($result_admins)['total_admins'];

$query_customers = "SELECT COUNT(*) as total_customers FROM users WHERE role = 'customer'";
$result_customers = mysqli_query($conn, $query_customers);
$total_customers = mysqli_fetch_assoc($result_customers)['total_customers'];

$query_orders = "SELECT COUNT(*) as total_orders FROM orders";
$result_orders = mysqli_query($conn, $query_orders);
$total_orders = mysqli_fetch_assoc($result_orders)['total_orders'];

$query_sales = "SELECT SUM(total_price) as total_sales FROM orders WHERE status = 'delivered'";
$result_sales = mysqli_query($conn, $query_sales);
$total_sales = mysqli_fetch_assoc($result_sales)['total_sales'];

$query_products = "SELECT COUNT(*) as total_products FROM products";
$result_products = mysqli_query($conn, $query_products);
$total_products = mysqli_fetch_assoc($result_products)['total_products'];

// جلب الطلبات حسب الحالة
$query_orders_by_status = "SELECT status, COUNT(*) as count FROM orders GROUP BY status";
$result_orders_by_status = mysqli_query($conn, $query_orders_by_status);
$orders_by_status = [];
while ($row = mysqli_fetch_assoc($result_orders_by_status)) {
    $orders_by_status[$row['status']] = $row['count'];
}

// جلب المبيعات الشهرية
$query_monthly_sales = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total_price) as total 
                        FROM orders 
                        WHERE status = 'delivered' 
                        GROUP BY DATE_FORMAT(created_at, '%Y-%m') 
                        ORDER BY month DESC 
                        LIMIT 6";
$result_monthly_sales = mysqli_query($conn, $query_monthly_sales);
$monthly_sales_labels = [];
$monthly_sales_data = [];
while ($row = mysqli_fetch_assoc($result_monthly_sales)) {
    $monthly_sales_labels[] = $row['month'];
    $monthly_sales_data[] = $row['total'];
}

// جلب المنتجات الأكثر مبيعًا
$query_top_products = "SELECT p.name, SUM(od.quantity) as total_sold 
                       FROM order_details od 
                       JOIN products p ON od.product_id = p.id 
                       GROUP BY p.id 
                       ORDER BY total_sold DESC 
                       LIMIT 5";
$result_top_products = mysqli_query($conn, $query_top_products);
$top_products_labels = [];
$top_products_data = [];
while ($row = mysqli_fetch_assoc($result_top_products)) {
    $top_products_labels[] = $row['name'];
    $top_products_data[] = $row['total_sold'];
}

// جلب المنتجات قليلة المخزون
$query_low_stock = "SELECT name, stock FROM products WHERE stock < 10 LIMIT 5";
$result_low_stock = mysqli_query($conn, $query_low_stock);
$low_stock_products = [];
while ($row = mysqli_fetch_assoc($result_low_stock)) {
    $low_stock_products[] = $row;
}

// جلب الفئات والمنتجات في كل فئة
$query_categories = "SELECT c.name, COUNT(p.id) as product_count 
                     FROM categories c 
                     LEFT JOIN products p ON c.id = p.category_id 
                     GROUP BY c.id";
$result_categories = mysqli_query($conn, $query_categories);
$categories_data = [];
while ($row = mysqli_fetch_assoc($result_categories)) {
    $categories_data[] = $row;
}

// جلب العروض الخاصة
$query_offers = "SELECT po.product_name, po.original_price, po.offer_price 
                 FROM product_offers po 
                 JOIN products p ON po.product_id = p.id 
                 LIMIT 5";
$result_offers = mysqli_query($conn, $query_offers);
$offers_data = [];
while ($row = mysqli_fetch_assoc($result_offers)) {
    $offers_data[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .main-area {
            padding: 20px;
            margin-left: 250px; /* ترك مساحة للسايدبار */
        }
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
        .card {
            margin-bottom: 20px;
            height: 100%; /* جعل الكاردات بنفس الارتفاع */
        }
        .chart-container {
            margin-bottom: 20px;
            height: 300px; /* تحديد ارتفاع الرسوم البيانية */
        }
        .stat-card {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            height: 100%; /* جعل الكاردات بنفس الارتفاع */
        }
        .stat-card h5 {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }
        .stat-card p {
            font-size: 1.5rem;
            font-weight: bold;
        }
                /* إخفاء العناصر على الشاشات الكبيرة */
                @media (min-width: 992px) {
            .sidebar {
                display: flex !important; /* إظهار القائمة على الشاشات الصغيرة */
                flex-direction: column; /* جعل العناصر تظهر بشكل عمودي */
            }
        }
        
        /* إظهار العناصر على الشاشات الصغيرة */
        @media (max-width: 991.98px) {
            .sidebar {
                display: none !important; /* إخفاء القائمة على الشاشات الكبيرة */
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <?php include "./admin_components/navbar.php"; ?>
        <div class="row">
            <!-- السايدبار -->
            <div class="sidebar">
                <?php include "./admin_components/admin_sidebar.php"; ?>
            </div>

            <!-- Main Area -->
            <div class="col-md-9 col-lg-10 main-area mt-5">
                <h1>Admin Dashboard</h1>

                <!-- الإحصائيات العامة -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h5>Total Users</h5>
                            <p><?php echo $total_users; ?></p>
                            <small>Admins: <?php echo $total_admins; ?></small><br>
                            <small>Customers: <?php echo $total_customers; ?></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h5>Total Orders</h5>
                            <p><?php echo $total_orders; ?></p>
                            <small>Pending: <?php echo $orders_by_status['pending'] ?? 0; ?></small><br>
                            <small>Shipped: <?php echo $orders_by_status['shipped'] ?? 0; ?></small><br>
                            <small>Delivered: <?php echo $orders_by_status['delivered'] ?? 0; ?></small><br>
                            <small>Cancelled: <?php echo $orders_by_status['cancelled'] ?? 0; ?></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h5>Total Sales</h5>
                            <p>$<?php echo number_format($total_sales, 2); ?></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <h5>Total Products</h5>
                            <p><?php echo $total_products; ?></p>
                        </div>
                    </div>
                </div>

                <!-- الرسوم البيانية -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Orders by Status</h5>
                                <div class="chart-container">
                                    <canvas id="ordersByStatusChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Monthly Sales</h5>
                                <div class="chart-container">
                                    <canvas id="monthlySalesChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- المنتجات الأكثر مبيعًا -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Top Selling Products</h5>
                                <div class="chart-container">
                                    <canvas id="topProductsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Low Stock Products</h5>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($low_stock_products as $product): ?>
                                            <tr>
                                                <td><?php echo $product['name']; ?></td>
                                                <td><?php echo $product['stock']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- الفئات والمنتجات -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Categories and Products</h5>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>Product Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($categories_data as $category): ?>
                                            <tr>
                                                <td><?php echo $category['name']; ?></td>
                                                <td><?php echo $category['product_count']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Active Offers</h5>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Original Price</th>
                                            <th>Offer Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($offers_data as $offer): ?>
                                            <tr>
                                                <td><?php echo $offer['product_name']; ?></td>
                                                <td>$<?php echo $offer['original_price']; ?></td>
                                                <td>$<?php echo $offer['offer_price']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Orders by Status Chart
        const ordersByStatusCtx = document.getElementById('ordersByStatusChart').getContext('2d');
        const ordersByStatusChart = new Chart(ordersByStatusCtx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode(array_keys($orders_by_status)); ?>,
                datasets: [{
                    label: 'Orders by Status',
                    data: <?php echo json_encode(array_values($orders_by_status)); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            }
        });

        // Monthly Sales Chart
        const monthlySalesCtx = document.getElementById('monthlySalesChart').getContext('2d');
        const monthlySalesChart = new Chart(monthlySalesCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($monthly_sales_labels); ?>,
                datasets: [{
                    label: 'Monthly Sales',
                    data: <?php echo json_encode($monthly_sales_data); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            }
        });

        // Top Selling Products Chart
        const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
        const topProductsChart = new Chart(topProductsCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($top_products_labels); ?>,
                datasets: [{
                    label: 'Total Sold',
                    data: <?php echo json_encode($top_products_data); ?>,
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>