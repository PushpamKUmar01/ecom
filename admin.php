<?php
session_start();
require 'config.php';

$mysqli = new mysqli('localhost', 'root', '', 'ecommerce');

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $rating = $_POST['rating'];
    $image = $_FILES['image']['name'];

    $target = 'images/' . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    $stmt = $mysqli->prepare("INSERT INTO products (name, description, price, category, rating, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('ssdsds', $name, $description, $price, $category, $rating, $image);

    if ($stmt->execute()) {
        header('Location: admin.php');
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

if (isset($_POST['edit_product'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $rating = $_POST['rating'];
    $image = $_FILES['image']['name'];

    if ($image) {
        $target = 'images/' . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $stmt = $mysqli->prepare("UPDATE products SET name = ?, description = ?, price = ?, category = ?, rating = ?, image = ? WHERE id = ?");
        $stmt->bind_param('ssdsdsi', $name, $description, $price, $category, $rating, $image, $id);
    } else {
        $stmt = $mysqli->prepare("UPDATE products SET name = ?, description = ?, price = ?, category = ?, rating = ? WHERE id = ?");
        $stmt->bind_param('ssdsdi', $name, $description, $price, $category, $rating, $id);
    }

    if ($stmt->execute()) {
        header('Location: admin.php');
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

if (isset($_POST['delete_product'])) {
    $id = $_POST['delete_id'];
    $stmt = $mysqli->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        header('Location: admin.php');
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Page</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/admin.css">
</head>
<body>
    <h1>Admin - Manage Products</h1>

    <h2>Add New Product</h2>
    <form action="admin.php" method="post" enctype="multipart/form-data">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" required>

        <label for="category">Category:</label>
        <input type="text" id="category" name="category" required>

        <label for="rating">Rating:</label>
        <input type="number" id="rating" name="rating" step="0.1" min="0" max="5" required>

        <label for="image">Image:</label>
        <input type="file" id="image" name="image" required>

        <button type="submit" name="add_product">Add Product</button>
    </form>

    <!-- Existing Products Table -->
    <h2>Existing Products</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Category</th>
                <th>Rating</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $products = $mysqli->query("SELECT * FROM products");
            while ($row = $products->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['description'] . "</td>";
                echo "<td>" . $row['price'] . "</td>";
                echo "<td>" . $row['category'] . "</td>";
                echo "<td>" . $row['rating'] . "</td>";
                echo "<td><img src='images/" . $row['image'] . "' alt='" . $row['name'] . "'></td>";
                echo "<td>
                        <button class='edit-product' data-id='" . $row['id'] . "'>Edit</button>
                        <form action='admin.php' method='post' style='display:inline-block;'>
                            <input type='hidden' name='delete_id' value='" . $row['id'] . "'>
                            <button type='submit' name='delete_product'>Delete</button>
                        </form>
                    </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Modal for Editing Product -->
    <div id="editModal" style="display: none;">
        <h2>Edit Product</h2>
        <form id="editForm" action="admin.php" method="post" enctype="multipart/form-data">
            <input type="hidden" id="edit_id" name="id">
            <label for="edit_name">Product Name:</label>
            <input type="text" id="edit_name" name="name" required>

            <label for="edit_description">Description:</label>
            <textarea id="edit_description" name="description" required></textarea>

            <label for="edit_price">Price:</label>
            <input type="number" id="edit_price" name="price" step="0.01" required>

            <label for="edit_category">Category:</label>
            <input type="text" id="edit_category" name="category" required>

            <label for="edit_rating">Rating:</label>
            <input type="number" id="edit_rating" name="rating" step="0.1" min="0" max="5" required>

            <label for="edit_image">Image:</label>
            <input type="file" id="edit_image" name="image">

            <button type="submit" name="edit_product">Save Changes</button>
        </form>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.edit-product');
        const editModal = document.getElementById('editModal');
        const editForm = document.getElementById('editForm');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');

                fetch(get_product.php?id=${id})
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('edit_id').value = data.id;
                        document.getElementById('edit_name').value = data.name;
                        document.getElementById('edit_description').value = data.description;
                        document.getElementById('edit_price').value = data.price;
                        document.getElementById('edit_category').value = data.category;
                        document.getElementById('edit_rating').value = data.rating;

                        editModal.style.display = 'block';
                    });
            });
        });

        editForm.addEventListener('submit', function() {
            editModal.style.display = 'none';
        });
    });
    </script>
</body>
</html>