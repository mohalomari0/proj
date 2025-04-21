<?php
session_start(); // بدء الجلسة
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // استعلام للتحقق من صحة بيانات تسجيل الدخول
    $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // تم تسجيل الدخول بنجاح
        $user = mysqli_fetch_assoc($result); // جلب بيانات المستخدم
        $_SESSION['user_id'] = $user['id']; // تخزين user_id في الجلسة
        $_SESSION['email'] = $user['email']; // تخزين البريد الإلكتروني (اختياري)
        $_SESSION['role'] = $user['role']; // تخزين دور المستخدم في الجلسة

        // التحقق من قيمة role وتوجيه المستخدم إلى الصفحة المناسبة
        if ($user['role'] == 'admin') {
            header("Location: ./admin_main.php"); // الانتقال إلى صفحة الإدارة
            exit();
        } elseif ($user['role'] == 'customer') {
            // header("Location: ./costumer_main.php"); // الانتقال إلى الصفحة الرئيسية للعميل
            header("Location: ./customer_main/cmain.php"); // الانتقال إلى الصفحة الرئيسية للعميل
            exit();
        }
    } else {
        // بيانات تسجيل الدخول غير صحيحة
        $_SESSION['message'] = "Invalid Credentials"; // تخزين رسالة الخطأ في الجلسة
        $_SESSION['message_type'] = "danger"; // نوع الرسالة (للعرض باستخدام Bootstrap)
        header("Location: login.php"); // إعادة توجيه المستخدم إلى صفحة تسجيل الدخول
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include "../nav.php" ?>

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card" style="width: 24rem;">
            <div class="card-body">
                <h5 class="card-title text-center">Login</h5>

                <!-- عرض رسائل الجلسة -->
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?php echo $_SESSION['message_type']; ?> text-center">
                        <?php echo $_SESSION['message']; ?>
                    </div>
                    <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email address</label>
                        <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="exampleInputPassword1" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Remember me</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                    <h5>dont have account yet? <a href="./signup.php" >Sign Up</a></h5>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>