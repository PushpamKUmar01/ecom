<?php
session_start();
include 'config.php';

$products = $conn->query("SELECT * FROM products");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Index</title>
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

     <!-----------------promotional------------------------------>
     <div class="containerA">
        <div class="slideshow-container">
          <div class="mySlides fade">
            <img src="images/add1.jpeg" style="width: 25%" />
          </div>

          <div class="mySlides fade">
            <img src="images/add2.jpeg" style="width: 25%" />
          </div>

          <div class="mySlides fade">
            <img src="images/add3.jpeg" style="width: 25%" />
          </div>

          <div class="mySlides fade">
            <img src="images/add4.jpeg" style="width: 25%" />
          </div>

          <div class="mySlides fade">
            <img src="images/add5.jpeg" style="width: 25%" />
          </div>

          <div class="mySlides fade">
            <img src="images/add6.jpeg" style="width: 25%" />
          </div>

          <div class="mySlides fade">
            <img src="images/add7.jpeg" style="width: 25%" />
          </div>

          <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
          <a class="next" onclick="plusSlides(1)">&#10095;</a>
        </div>
      </div>
    <div class="products">
        <?php while($row = $products->fetch_assoc()): ?>
            <div class="product">
                <img src="images/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                <h2><?php echo $row['name']; ?></h2>
                <p><?php echo $row['description']; ?></p>
                <p>Price: $<?php echo $row['price']; ?></p>
                <p>Rating: <?php echo $row['rating']; ?> stars</p>
                <button>Add to Cart</button>
            </div>
        <?php endwhile; ?>
    </div>
    <?php include 'footer.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>