<?php
include "../../db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $role = $_POST['role'];

    $query = "UPDATE users SET name='$name', email='$email', phone='$phone', address='$address', role='$role' WHERE id=$id";
    mysqli_query($conn, $query);

    header("Location: users.php");
    exit();
}
?>