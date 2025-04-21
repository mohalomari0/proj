<?php
session_start(); // بدء الجلسة
include '../../db.php'; // تأكد من أن ملف db.php يحتوي على اتصال قاعدة البيانات
// include 'db.php'; // تأكد من أن ملف db.php يحتوي على اتصال قاعدة البيانات

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category_id = $_POST['category_id']; // الحصول على category_id من النموذج

    // معالجة رفع الصورة
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/"; // المجلد الذي سيتم حفظ الصور فيه
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true); // إنشاء المجلد إذا لم يكن موجودًا
        }

        $target_file = $target_dir . basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // التحقق من أن الملف صورة
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check === false) {
            $_SESSION['message'] = "File is not an image.";
            $_SESSION['message_type'] = "danger";
            header("Location: products.php");
            exit();
        }

        // التحقق من حجم الملف (مثال: 5MB كحد أقصى)
        elseif ($_FILES['image']['size'] > 5000000) {
            $_SESSION['message'] = "File is too large.";
            $_SESSION['message_type'] = "danger";
            header("Location: products.php");
            exit();
        }

        // التحقق من نوع الملف (الصيغ المسموحة)
        elseif (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            $_SESSION['message'] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
            $_SESSION['message_type'] = "danger";
            header("Location: products.php");
            exit();
        }

        // إذا لم يكن هناك أخطاء، قم برفع الملف
        else {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                // تخزين بيانات المنتج في قاعدة البيانات
                $query = "INSERT INTO products (name, description, price, stock, image, category_id) VALUES ('$name', '$description', $price, $stock, '$target_file', $category_id)";
                if (mysqli_query($conn, $query)) {
                    $_SESSION['message'] = "Product added successfully!";
                    $_SESSION['message_type'] = "success";
                } else {
                    $_SESSION['message'] = "Error: " . mysqli_error($conn);
                    $_SESSION['message_type'] = "danger";
                }
            } else {
                $_SESSION['message'] = "Error uploading file.";
                $_SESSION['message_type'] = "danger";
            }
        }
    } else {
        $_SESSION['message'] = "No image uploaded.";
        $_SESSION['message_type'] = "danger";
    }
    header("Location: products.php");
    exit();
}
?>