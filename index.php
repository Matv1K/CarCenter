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

$sql = "SELECT * FROM cars ORDER BY price ASC LIMIT 8";
$result = mysqli_query($conn, $sql);

$sql_events = "SELECT * FROM events";
$result_events = mysqli_query($conn, $sql_events);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['add_to_cart'])) {
  $car_id = $_POST['car_id'];
  $user_id = $_SESSION['user_id'];
  $sql = "INSERT INTO cart_items (user_id, car_id) VALUES ('$user_id', '$car_id')";
  if (mysqli_query($conn, $sql)) {
    header('Location: index.php');
    exit;
  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="style.css" />
  <title>PHP Project</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">

</head>

<body>
  <div class="navbar">
    <div>
      <a href="index.php" class="<?php echo isActivePage('index.php'); ?>">Home</a>
      <a href="pages/events.php" class="<?php echo isActivePage('events.php'); ?>">Events</a>
      <a href="pages/positions.php" class="<?php echo isActivePage('positions.php'); ?>">Products</a>
      <a href="pages/about.php" class="<?php echo isActivePage('about.php'); ?>">About</a>
    </div>

    <h4 class="logo">CarCenter</h4>

    <div class="navbar__links">
      <?php if (isset($_SESSION['user_id'])) : ?>
        <div class="cart-container">
          <a href="pages/cart.php" class="cart-btn" onclick="goToCart()">
            <img src="images/shopping-cart.png" alt="Cart" class="cart-icon">
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
          <a class="sign-out" href="logout.php">Sign Out</a>
        </div>
      <?php else : ?>
        <div class="login-container">
          <a href="pages/login.php">Sign In</a>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <main>
    <h1 class="heading-primary">Our best products</h1>

    <div class="card-container">
      <?php
      while ($row = mysqli_fetch_assoc($result)) {
      ?>
        <div class="card">
          <a href="pages/car_details.php?id=<?php echo $row['id']; ?>">
            <h3><?php echo $row['name']; ?></h3>
            <p><?php echo $row['description']; ?></p>
            <div class="car-image">
              <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
            </div>
            <div class="card__actions">
              <p class="price">$<?php echo $row['price']; ?></p>
              <form method="post">
                <input type="hidden" name="car_id" value="<?php echo $row['id']; ?>">
                <button type="submit" class="add-to-cart-btn" name="add_to_cart">Add to Cart</button>
              </form>
            </div>
          </a>
        </div>
      <?php } ?>
    </div>

    <div class="see-more__block">
      <h3 class="heading-tertiary">
        <a class="see-more" href="pages/positions.php">See more</a>
      </h3>
    </div>

    <h1 class="heading-primary">Upcoming events!</h1>

    <div class="events-slider">
      <?php
      while ($row_events = mysqli_fetch_assoc($result_events)) {
        $eventID = $row_events['id'];
        $eventURL = "pages/event_details.php?id=$eventID"; // Construct the URL with event ID

      ?>
        <a href="<?php echo $eventURL; ?>" class="event-card">
          <img class="event-img" src="<?php echo $row_events['image']; ?>" />
          <div class="event-card-details">
            <h3><?php echo $row_events['name']; ?></h3>
            <p><?php echo $row_events['description']; ?></p>
            <p>Date: <?php echo $row_events['date']; ?></p>
          </div>
        </a>
      <?php
      }
      ?>
      <button class="slider-btn prev" onclick="slideLeft()">&#10094;</button>
      <button class="slider-btn next" onclick="slideRight()">&#10095;</button>
    </div>


  </main>

  <footer class="footer">
    <a href="pages/about.php">About</a>
    <p>
      @All rights reserved. Developed by Matsvei Balakhonau and Alexander
      Sapehin.
    </p>
    <p>Warsaw, Vistula University</p>
  </footer>

  <script>
    function addToCart(event) {
      e.preventDefault();
      const productId = event.target.dataset.productId;

      const xhr = new XMLHttpRequest();
      xhr.open("POST", "add_to_cart.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
              alert("Item added to cart!");
            } else {
              alert("Failed to add item to cart: " + response.message);
            }
          } else {
            alert("Failed to add item to cart. Please try again later.");
          }
        }
      };
      xhr.send("product_id=" + productId);
    }

    document.querySelectorAll(".add-to-cart-btn").forEach(button => {
      button.addEventListener("click", addToCart);
    });

    function toggleLogin() {
      const loginBtn = document.querySelector(".login-container button");
      if (loginBtn.textContent === "Login") {
        loginBtn.textContent = "Logout";
      } else {
        loginBtn.textContent = "Login";
      }
    }

    let slideIndex = 0;
    const slides = document.querySelectorAll('.event-card');
    const totalSlides = slides.length;
    const slidesPerPage = 3;
    let currentSlide = 0;

    function showSlides() {
      for (let i = 0; i < totalSlides; i++) {
        slides[i].style.display = 'none';
      }
      for (let i = currentSlide; i < currentSlide + slidesPerPage; i++) {
        slides[i % totalSlides].style.display = 'inline-block';
      }
    }

    function slideLeft() {
      currentSlide--;
      if (currentSlide < 0) {
        currentSlide = totalSlides - 1;
      }
      showSlides();
    }

    function slideRight() {
      currentSlide++;
      if (currentSlide >= totalSlides) {
        currentSlide = 0;
      }
      showSlides();
    }

    showSlides();
  </script>
</body>

</html>