<?php
session_start();
require 'config.php'; // Ensure this file contains your database connection

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$product_id = $_GET['id'];
$stmt = $mysqli->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

header('Content-Type: application/json');
echo json_encode($product);
?>