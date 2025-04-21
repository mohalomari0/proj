<?php
session_start();
include '../../db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM categories WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $category = mysqli_fetch_assoc($result);
} else {
    echo "No ID provided";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id'], $_POST['category_name'])) {
        $id = $_POST['id'];
        $category_name = $_POST['category_name'];

        $query = "UPDATE categories SET name = '$category_name' WHERE id = $id";
        if (mysqli_query($conn, $query)) {
            $_SESSION['message'] = "Category updated successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error updating category: " . mysqli_error($conn);
            $_SESSION['message_type'] = "danger";
        }
        header("Location: categories.php");
        exit();
    } else {
        echo "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h1 class="text-center">Edit Category</h1>
        <form method="POST" action="edit_category.php">
            <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
            <div class="form-group">
                <label for="category_name">Category Name</label>
                <input type="text" class="form-control" id="category_name" name="category_name" value="<?php echo $category['name']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
</html>