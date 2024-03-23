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

if (isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ./login.php');
        exit;
    }

    $car_id = $_POST['car_id'];
    $user_id = $_SESSION['user_id'];
    $sql = "INSERT INTO cart_items (user_id, car_id) VALUES ('$user_id', '$car_id')";
    if (mysqli_query($conn, $sql)) {
        header('Location: cart.php');
        exit;
    } else {
        echo "Error adding item to cart: " . mysqli_error($conn);
    }
}

if (isset($_GET['id'])) {
    $car_id = $_GET['id'];
} else {
    header("Location: index.php");
    exit();
}

$sql = "SELECT * FROM cars WHERE id = $car_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit();
}

$car_details = mysqli_fetch_assoc($result);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../style.css" />
    <title><?php echo $car_details['name']; ?> Details</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">

</head>

<body>
    <div class="wrapper">
        <div class="navbar">
            <div>
                <a href="../index.php">Home</a>
                <a href="./events.php">Events</a>
                <a href="./positions.php">Products</a>
                <a href="./about.php">About</a>
            </div>

            <h4 class="logo">CarCenter</h4>

            <div class="navbar__links">
                <?php if (isset($_SESSION['user_id'])) : ?>
                    <div class="cart-container">
                        <a href="./cart.php" class="cart-btn" onclick="goToCart()">
                            <img src="../images/shopping-cart.png" alt="Cart" class="cart-icon">
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
            <div class="car-details-container">
                <div class="car-details-top">
                    <div class="car-image">
                        <img src="../<?php echo $car_details['image']; ?>" alt="<?php echo $car_details['name']; ?>" style="max-width: 100%; height: auto; width: 400px; height: 300px;">
                    </div>
                    <form class="car_details-form" method="post">
                        <input type="hidden" name="car_id" value="<?php echo $car_details['id']; ?>">
                        <button type="submit" class="add-to-cart-btn" name="add_to_cart">Add to Cart</button>
                    </form>
                </div>
                <h1 class="car-name heading-tertiary"><?php echo $car_details['name']; ?>
                </h1>
                <div class="car-description"><?php echo $car_details['long_description']; ?></div>
                <div class="technical-specs">
                    <h2 class="margin-bottom-medium">Technical Specifications:</h2>
                    <ul>
                        <li class="specs-list-item">Engine: <?php echo $car_details['car_engine']; ?></li>
                        <li class="specs-list-item">Transmission: <?php echo $car_details['transmission']; ?></li>
                        <li class="specs-list-item">Power: <?php echo $car_details['power']; ?></li>
                        <li class="specs-list-item">Drive Type: <?php echo $car_details['drive_type']; ?></li>
                        <li class="specs-list-item">Fuel Efficiency (Combined): <?php echo $car_details['efficiency']; ?></li>
                    </ul>
                </div>
            </div>
        </main>

        <footer class="footer">
            <a href="./about.php">About</a>
            <p>All rights reserved. Developed by Matsvei Balakhonau and Alexander Sapehin.</p>
            <p>Warsaw, Vistula University</p>
        </footer>
    </div>

</body>

</html>