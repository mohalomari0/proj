<?php
include "../../db.php";

$row = [];
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM orders WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "No order found";
    }
} else {
    echo "No ID provided";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id'], $_POST['user_id'], $_POST['total_price'], $_POST['status'])) {
        $id = $_POST['id'];
        $user_id = $_POST['user_id'];
        $total_price = $_POST['total_price'];
        $status = $_POST['status'];

        $sql = "UPDATE orders SET user_id = '$user_id', total_price = '$total_price', status = '$status' WHERE id = $id";
        if ($conn->query($sql)) {
            echo "تم التحديث بنجاح!";
            header("Location: orders.php"); // إعادة التوجيه بعد التحديث
            exit();
        } else {
            echo "خطأ في التحديث: " . $conn->error;
        }
    } else {
        echo "جميع الحقول مطلوبة!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .form-container h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .form-container label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }
        .form-container input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #45a049;
        }
        .dropdown-menu li {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Update Order</h1>
        <form method="POST" action="update.php">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <label for="user_id">User ID:</label>
            <input type="text" name="user_id" value="<?php echo $row['user_id']; ?>">
            <label for="total_price">Total Price:</label>
            <input type="text" name="total_price" value="<?php echo $row['total_price']; ?>">
            <label for="status">Status:</label>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php echo $row['status'] ?? 'Select Status'; ?>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item" href="#" data-value="pending">Pending</a></li>
                    <li><a class="dropdown-item" href="#" data-value="shipped">Shipped</a></li>
                    <li><a class="dropdown-item" href="#" data-value="delivered">Delivered</a></li>
                    <li><a class="dropdown-item" href="#" data-value="cancelled">Cancelled</a></li>
                </ul>
                <input type="hidden" name="status" id="status" value="<?php echo $row['status']; ?>">
            </div>
            <button type="submit" name="update" class="mt-3">Update</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownItems = document.querySelectorAll('.dropdown-item');
            const statusInput = document.getElementById('status');
            const dropdownButton = document.getElementById('dropdownMenuButton1');

            dropdownItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const selectedValue = this.getAttribute('data-value');
                    statusInput.value = selectedValue;
                    dropdownButton.textContent = this.textContent;
                });
            });
        });
    </script>
</body>
</html>