<?php
session_start();

function isActivePage($pageName)
{
  $currentPage = basename($_SERVER['PHP_SELF']);

  if ($currentPage === $pageName) {
    return 'active';
  } else {
    return '';
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" type="text/css" href="../style.css" />
  <title>PHP Project</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">

</head>

<body>
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
          <a href="../pages/cart.php" class="cart-btn" onclick="goToCart()">
            <img src="../images/shopping-cart.png" alt="Cart" class="cart-icon" />
            <span class="cart-count">
              <?php
              $servername = "localhost";
              $username = "root";
              $password = "";
              $database = "database1";

              $conn = new mysqli($servername, $username, $password, $database);

              if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
              }

              $sql1 = "SELECT COUNT(*) AS total FROM cart_items";

              $result1 = $conn->query($sql1);

              if ($result1) {
                $row1 = $result1->fetch_assoc();

                if (isset($row1['total'])) {
                  $total_rows = $row1['total'];

                  echo $total_rows;
                } else {
                  echo "0";
                }
              } else {
                echo "Error: " . $conn->error;
              }

              $conn->close();
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

  <main>
    <h1 class="heading-primary">About Us</h1>
    <div class="about-info">
      <img class="about-img" src="https://cdn2.unrealengine.com/racing-master-img-1-1920x1080-e5d0a6bbc96f.jpg?resize=1&w=1920" alt="About Image" />
      <p>
        Welcome to our website! We are dedicated to providing high-quality
        products and services to our customers.
      </p>
      <p>
        Our mission is to create a seamless shopping experience for users,
        offering a wide range of products and ensuring customer satisfaction.
      </p>
      <p>
        Feel free to explore our website to learn more about what we offer and
        how we can meet your needs.
      </p>
    </div>

    <h1 class="heading-primary">Where to find us</h1>

    <div class="map">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2350.1166692096135!2d27.557702476559697!3d53.91190267245877!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46dbcfae44ffd681%3A0x3cf1778e4d9c972e!2z0JzQuNGB0YEg0JvQuA!5e0!3m2!1sru!2sby!4v1697982973247!5m2!1sru!2sby" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <h2 class="heading-secondary">Meet Our Team</h2>
    <div class="team-container">
      <div class="team-member">
        <img class="team-member-img" src="https://via.placeholder.com/150" alt="Team Member 1" />
        <h3>Matsvei Balakhonau</h3>
        <p>Web Developer</p>
        <p>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do
          eiusmod tempor incididunt ut labore et dolore magna aliqua.
        </p>
      </div>

      <div class="team-member">
        <img class="team-member-img" src="https://via.placeholder.com/150" alt="Team Member 2" />
        <h3>Alexander Sapehin</h3>
        <p>Web developer</p>
        <p>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do
          eiusmod tempor incididunt ut labore et dolore magna aliqua.
        </p>
      </div>
    </div>
  </main>

  <footer class="footer">
    <a href="./about.php">About</a>
    <p>All rights reserved. Developed by Matsvei Balakhonau and Alexander Sapehin.</p>
    <p>Warsaw, Vistula University</p>
  </footer>

  <script>
    function toggleLogin() {
      var loginBtn = document.querySelector(".login-container button");
      if (loginBtn.textContent === "Login") {
        loginBtn.textContent = "Logout";
      } else {
        loginBtn.textContent = "Login";
      }
    }
  </script>
</body>

</html>