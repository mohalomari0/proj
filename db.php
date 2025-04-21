<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "home_store";

// إنشاء الاتصال
$conn = mysqli_connect($servername, $username, $password, $dbname);

// التحقق من الاتصال
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>