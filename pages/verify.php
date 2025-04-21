<?php
session_start();

if (!isset($_SESSION['otp'])) {
    header("Location: signup.php"); // إذا لم يتم إنشاء OTP، إعادة التوجيه إلى التسجيل
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_otp = $_POST['otp'];

    if ($user_otp == $_SESSION['otp']) {
        // إذا كان OTP صحيحًا، قم بإضافة المستخدم إلى قاعدة البيانات
        $name = $_SESSION['signup_data']['name'];
        $email = $_SESSION['signup_data']['email'];
        $password = $_SESSION['signup_data']['password'];
        $phone = $_SESSION['signup_data']['phone'];
        $address = $_SESSION['signup_data']['address'];

        include '../db.php';
        $query = "INSERT INTO users (name, email, password, phone, address, role) VALUES ('$name', '$email', '$password', '$phone', '$address', 'customer')";
        if (mysqli_query($conn, $query)) {
            echo "Signup Successful!";
            header("Location: login.php"); // إعادة التوجيه إلى صفحة التحقق

            session_destroy(); // حذف الجلسة بعد التسجيل الناجح
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Invalid OTP. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Verify OTP</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="otp" class="form-label">Enter OTP</label>
                                <input type="text" class="form-control" id="otp" name="otp" placeholder="OTP" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Verify</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>