<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "database1";
$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM cars";
$result = mysqli_query($conn, $sql);

if (!$result) {
  die("Error fetching products: " . mysqli_error($conn));
}

function isActivePage($pageName)
{
  $currentPage = basename($_SERVER['PHP_SELF']);

  if ($currentPage === $pageName) {
    return 'active';
  } else {
    return '';
  }
}

if (isset($_POST['add_to_cart'])) {
  if (!isset($_SESSION['user_id'])) {
    header('Location: ./login.php');
    exit;
  }

  $car_id = $_POST['car_id'];
  $user_id = $_SESSION['user_id'];
  $sql = "INSERT INTO cart_items (user_id, car_id) VALUES ('$user_id', '$car_id')";
  if (mysqli_query($conn, $sql)) {
    header('Location: ../pages/positions.php');
    exit;
  } else {
    echo "Error adding item to cart: " . mysqli_error($conn);
  }
}

if (isset($_GET['search'])) {
  $search = $_GET['search'];
  $sql = "SELECT * FROM cars WHERE name LIKE '%$search%' OR description LIKE '%$search%'";
  $result = mysqli_query($conn, $sql);
  if (!$result) {
    die("Error fetching products: " . mysqli_error($conn));
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../style.css" />
  <title>Products</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">

</head>

<body>
  <div class="wrapper">
    <div class="navbar">
      <div>
        <a href="../index.php" class="<?php echo isActivePage('index.php'); ?>">Home</a>
        <a href="./events.php" class="<?php echo isActivePage('events.php'); ?>">Events</a>
        <a href="./positions.php" class="<?php echo isActivePage('positions.php'); ?>">Products</a>
        <a href="./about.php" class="<?php echo isActivePage('about.php'); ?>">About</a>
      </div>

      <h4 class="logo">CarCenter</h4>

      <div class="navbar__links">
        <?php if (isset($_SESSION['user_id'])) : ?>
          <div class="cart-container">
            <a href="./cart.php" class="cart-btn">
              <img src="../images/shopping-cart.png" alt="Cart" class="cart-icon">
              <span class="cart-count">
                <?php
                $user_id = $_SESSION['user_id'];
                $sql_cart_count = "SELECT COUNT(*) AS total FROM cart_items WHERE user_id='$user_id'";
                $result_cart_count = mysqli_query($conn, $sql_cart_count);
                $row_cart_count = mysqli_fetch_assoc($result_cart_count);
                echo $row_cart_count['total'];
                ?>
              </span>
            </a>
          </div>
          <div class="login-container">
            <a class="sign-out" href="../logout.php">Sign Out</a>
          </div>
        <?php else : ?>
          <div class="login-container">
            <a href="./login.php">Sign In</a>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <main class="main-content">
      <h1 class="heading-primary">Our Products</h1>

      <form class="positions-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
        <input class="positions-input" type="text" name="search" id="searchInput" placeholder="Search products...">
        <button class="positions-btn" type="submit">Search</button>
      </form>

      <div class="card-container">
        <?php
        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <div class="card">
              <a href="../pages/car_details.php?id=<?php echo $row['id']; ?>">
                <h3><?php echo $row['name']; ?></h3>
                <p><?php echo $row['description']; ?></p>
                <div class="car-image">
                  <img src="../<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                </div>
                <div class="card__actions">
                  <p class="price">$<?php echo $row['price']; ?></p>
                  <form method="post" class="add-to-cart-form">
                    <input type="hidden" name="car_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" class="add-to-cart-btn" name="add_to_cart">Add to Cart</button>
                  </form>
                </div>
              </a>
            </div>
        <?php
          }
        } else {
          echo "<h3 class='heading-tertiary center'>No products available.</h3>";
        }
        ?>
      </div>
    </main>

    <footer class="footer">
      <a href="./about.php">About</a>
      <p>
        @All rights reserved. Developed by Matsvei Balakhonau and Alexander
        Sapehin.
      </p>
      <p>Warsaw, Vistula University</p>
    </footer>
  </div>

  <script>
    document.querySelector(".positions-form").addEventListener("submit", function(event) {
      event.preventDefault();
      const searchQuery = this.querySelector('.positions-input').value.toLowerCase().trim();
      window.location.href = "positions.php?search=" + encodeURIComponent(searchQuery);
    });
  </script>
</body>

</html>