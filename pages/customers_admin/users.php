<?php
include "../../db.php"; // تأكد من أن ملف db.php يحتوي على اتصال قاعدة البيانات

// التحقق من الدور المحدد للتصفية
$role_filter = isset($_GET['role']) ? $_GET['role'] : 'all';

// استعلام لجلب المستخدمين بناءً على الدور المحدد
if ($role_filter === 'all') {
    $query = "SELECT * FROM users";
} else {
    $query = "SELECT * FROM users WHERE role = '$role_filter'";
}

$result = mysqli_query($conn, $query);
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: #fff;
            height: 100vh;
            position: fixed;
            top: 56px; /* ارتفاع النافبار */
            left: 0;
            overflow-y: auto;
        }

        .main-content {
            margin-left: 250px;
            margin-top: 56px; /* ارتفاع النافبار */
            padding: 20px;
            flex: 1;
            background-color: #f8f9fa;
        }

        .table-custom {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table-custom th,
        .table-custom td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .table-custom th {
            background-color: #007bff;
            color: #fff;
        }

        .table-custom tr:hover {
            background-color: #f1f1f1;
        }

        .btn-edit {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-delete {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-edit:hover,
        .btn-delete:hover {
            opacity: 0.8;
        }

        .mid-nav {
            margin-bottom: 20px;
        }

        .mid-nav .btn {
            margin-right: 10px;
        }
        
    </style>
</head>

<body>
    <!-- النافبار -->
    <nav class="navbar navbar-dark bg-dark">
        <?php include "../admin_components/navbar.php"; ?>
    </nav>

    <!-- السايدبار -->
    <div class="sidebar">
        <?php include "../admin_components/admin_sidebar.php"; ?>
    </div>

    <!-- المحتوى الرئيسي -->
    <div class="main-content">
        <h1>Users Management</h1>

        <!-- mid-nav للتصفية حسب الدور -->
        <div class="mid-nav mb-4">
            <a href="users.php?role=all" class="btn btn-primary <?= $role_filter === 'all' ? 'active' : '' ?>">All Users</a>
            <a href="users.php?role=admin" class="btn btn-primary <?= $role_filter === 'admin' ? 'active' : '' ?>">Admins</a>
            <a href="users.php?role=customer" class="btn btn-primary <?= $role_filter === 'customer' ? 'active' : '' ?>">Customers</a>
        </div>

        <!-- جدول عرض المستخدمين -->
        <table class="table-custom">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= $user['name'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td><?= $user['phone'] ?></td>
                        <td><?= $user['address'] ?></td>
                        <td><?= $user['role'] ?></td>
                        <td>
                            <button class="btn-edit" data-bs-toggle="modal" data-bs-target="#editUserModal" 
                                    data-id="<?= $user['id'] ?>" 
                                    data-name="<?= $user['name'] ?>" 
                                    data-email="<?= $user['email'] ?>" 
                                    data-phone="<?= $user['phone'] ?>" 
                                    data-address="<?= $user['address'] ?>" 
                                    data-role="<?= $user['role'] ?>">Edit</button>
                            <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- نافذة منبثقة (Modal) لتعديل المستخدم -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm" method="POST" action="update_user.php">
                        <input type="hidden" id="editUserId" name="id">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPhone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="editPhone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="editAddress" class="form-label">Address</label>
                            <textarea class="form-control" id="editAddress" name="address" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editRole" class="form-label">Role</label>
                            <select class="form-control" id="editRole" name="role" required>
                                <option value="customer">Customer</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // JavaScript لملء بيانات المستخدم في النافذة المنبثقة
        document.addEventListener('DOMContentLoaded', function () {
            var editUserModal = document.getElementById('editUserModal');
            editUserModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget; // الزر الذي تم النقر عليه
                var id = button.getAttribute('data-id');
                var name = button.getAttribute('data-name');
                var email = button.getAttribute('data-email');
                var phone = button.getAttribute('data-phone');
                var address = button.getAttribute('data-address');
                var role = button.getAttribute('data-role');

                // تعبئة الحقول في النافذة المنبثقة
                document.getElementById('editUserId').value = id;
                document.getElementById('editName').value = name;
                document.getElementById('editEmail').value = email;
                document.getElementById('editPhone').value = phone;
                document.getElementById('editAddress').value = address;
                document.getElementById('editRole').value = role;
            });
        });
    </script>
</body>

</html>