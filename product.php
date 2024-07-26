<?php
session_start();
include 'config.php';

$category = $_GET['category'];
$products = $conn->query("SELECT * FROM products WHERE category='$category'");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (!in_array($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $product_id;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products - <?php echo ucfirst($category); ?></title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="product.php?category=men">Men</a></li>
            <li><a href="product.php?category=women">Women</a></li>
            <li><a href="product.php?category=best_offers">Best Offers</a></li>
            <?php if(isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])): ?>
                <li><a href="cart.php">My Cart</a></li>
                <li><a href="logout.php">Logout</a></li>
                <li><span style="color: white; font-size: 20px; margin-left: 101px;">Welcome, <?php echo $_SESSION['name']; ?></span></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <div class="products">
        <?php while($row = $products->fetch_assoc()): ?>
            <div class="product">
                <img src="images/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                <h2><?php echo $row['name']; ?></h2>
                <p><?php echo $row['description']; ?></p>
                <p>Price: $<?php echo $row['price']; ?></p>
                <p>Rating: <?php echo $row['rating']; ?> stars</p>
                <form method="POST" action="">
                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="add_to_cart">Add to Cart</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>