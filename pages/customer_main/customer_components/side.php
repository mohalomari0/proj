<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .sidebar {
            height: 100vh;
            background-color: #343a40;
            color: white;
            padding: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            margin: 10px 0;
        }
        .sidebar a:hover {
            color: #f8f9fa;
        }
    </style>

</head>
    <body>
        <div class="col-md-3 col-lg-2 sidebar">
            <h2>Sidebar</h2>
            <ul class="list-unstyled">
                <li><a href="/proj/pages/customer_main/cmain.php">Home</a></li>
                <li><a href="/proj/pages/customer_main/cart/cart.php">Cart</a></li>
                <li><a href="/proj/pages/login.php">Logout</a></li>
            </ul>
        </div>
    </body>
</html>