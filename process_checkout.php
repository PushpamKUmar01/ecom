<?php
session_start();
require 'config.php';
$mysqli = new mysqli('localhost', 'root', '', 'ecommerce');

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    
    // Insert order into database
    $stmt = $mysqli->prepare("INSERT INTO orders (user_id, address, phone) VALUES (?, ?, ?)");
    $stmt->bind_param('iss', $user_id, $address, $phone);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    
    // Move cart items to order_items table
    $cart_items = $mysqli->query("SELECT product_id, quantity FROM cart WHERE user_id = $user_id");
    while ($row = $cart_items->fetch_assoc()) {
        $product_id = $row['product_id'];
        $quantity = $row['quantity'];
        $stmt = $mysqli->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param('iii', $order_id, $product_id, $quantity);
        $stmt->execute();
    }
    
    // Clear the cart
    $mysqli->query("DELETE FROM cart WHERE user_id = $user_id");
    
    // Redirect to a success page
    header('Location: order_success.php');
}
?>