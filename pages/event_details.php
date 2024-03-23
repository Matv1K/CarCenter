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

if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    $sql = "SELECT * FROM events WHERE id = $event_id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $event_details = mysqli_fetch_assoc($result);
    } else {
        echo "Event not found";
        exit();
    }
} else {
    echo "Event ID not specified";
    exit();
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $event_details['name']; ?> Details</title>
    <link rel="stylesheet" type="text/css" href="../style.css" />
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

        <div class="event-details">
            <div class="event-image">
                <img src="../<?php echo $event_details['image']; ?>" alt="<?php echo $event_details['name']; ?>" style="max-width: 100%; height: auto; width: 400px; height: 300px;">
            </div>
            <h2><?php echo $event_details['name']; ?></h2>
            <p>Date: <?php echo $event_details['date']; ?></p>
            <p><?php echo $event_details['long_description']; ?></p>
            <p>Members: <?php echo $event_details['members_amount']; ?></p>
        </div>

        <footer class="footer">
            <a href="./about.php">About</a>
            <p>All rights reserved. Developed by Matsvei Balakhonau and Alexander Sapehin.</p>
            <p>Warsaw, Vistula University</p>
        </footer>
    </div>
</body>

</html>