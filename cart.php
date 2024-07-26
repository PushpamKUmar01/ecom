<?php
session_start();
include 'config.php';

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if (count($cart) > 0) {
    $ids = implode(',', $cart);
    $products = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
} else {
    $products = [];
}

$total_price = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Cart</title>
    <link rel="stylesheet" type="text/css" href="css/cart.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <nav>
        <button class="toggle-button" id="toggle-button">&#9776;</button>
        <ul id="nav-ul">
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

    <div class="cart">
        <h1>My Cart</h1>
        <?php if (count($cart) > 0): ?>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>
                <?php while($row = $products->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td>$<?php echo $row['price']; ?></td>
                        <td>1</td>
                    </tr>
                    <?php $total_price += $row['price']; ?>
                <?php endwhile; ?>
            </table>
            <h2>Total Price: $<?php echo $total_price; ?></h2>
            <button onclick="location.href='checkout.php'">Proceed to Checkout</button>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>

    <script src="js/script.js"></script>
</body>
</html>