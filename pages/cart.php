<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "database1";
$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT cars.*, COUNT(cart_items.car_id) AS quantity FROM cars LEFT JOIN cart_items ON cars.id = cart_items.car_id WHERE cart_items.user_id = $user_id GROUP BY cars.id";
$result = mysqli_query($conn, $sql);

$total_price = 0;

$cart_contents = array();
while ($row = mysqli_fetch_assoc($result)) {
  $cart_contents[] = $row;
  $total_price += ($row['price'] * $row['quantity']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shopping Cart</title>
  <link rel="stylesheet" href="../style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">

</head>

<body>
  <div class="wrapper">
    <div class="navbar">
      <div>
        <a href="../index.php">Home</a>
        <a href="../pages/events.php">Events</a>
        <a href="../pages/positions.php">Products</a>
        <a href="../pages/about.php">About</a>
      </div>

      <h4 class="logo">CarCenter</h4>

      <div class="navbar__links">
        <?php if (isset($_SESSION['user_id'])) : ?>
          <div class="cart-container">
            <a href="../pages/cart.php" class="cart-btn" onclick="goToCart()">
              <img src="../images/shopping-cart.png" alt="Cart" class="cart-icon">
              <span class="cart-count">
                <?php
                $sql1 = "SELECT COUNT(*) AS total FROM cart_items WHERE user_id = $user_id";
                $result1 = mysqli_query($conn, $sql1);
                $row1 = mysqli_fetch_assoc($result1);
                echo $row1['total'];
                ?>
              </span>
            </a>
          </div>
          <div class="login-container">
            <a class="sign-out" href="../logout.php">Sign Out</a>
          </div>
        <?php else : ?>
          <div class="login-container">
            <a href="../pages/login.php">Sign In</a>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <main>
      <?php if (empty($cart_contents)) : ?>
        <h3 class="heading-tertiary">Your cart is still empty.</h3>
      <?php else : ?>
        <table class="cart-table">
          <tbody>
            <h1 class="heading-primary">Shopping Cart</h1>
            <tr>
              <th>Image</th>
              <th>Name</th>
              <th>Quantity</th>
              <th>Price</th>
            </tr>
            <?php foreach ($cart_contents as $item) : ?>
              <tr>
                <td><img src="../<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" style="max-width: 100px;"></td>
                <td><?php echo $item['name']; ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td>$<?php echo $item['price'] * $item['quantity']; ?></td>
              </tr>
            <?php endforeach; ?>
            <tr class="cart-total">
              <td colspan="3">Total:</td>
              <td>$<?php echo $total_price; ?></td>
            </tr>
          </tbody>
        </table>
      <?php endif; ?>
    </main>

    <footer class="footer">
      <a href="../pages/about.php">About</a>
      <p>@All rights reserved. Developed by Matsvei Balakhonau and Alexander Sapehin.</p>
      <p>Warsaw, Vistula University</p>
    </footer>
  </div>
</body>

</html>