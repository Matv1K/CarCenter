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

$sql = "SELECT * FROM events";

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $sql .= " WHERE name LIKE '%$search%' OR description LIKE '%$search%'";
}

$result = mysqli_query($conn, $sql);

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Events</title>
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
                        <a href="./cart.php" class="cart-btn" onclick="goToCart()">
                            <img src="../images/shopping-cart.png" alt="Cart" class="cart-icon">
                            <span class="cart-count">
                                <?php
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
            <h1 class="heading-primary">Upcoming Events</h1>

            <form class="positions-form" action="events.php" method="GET">
                <input class="positions-input" type="text" name="search" id="searchInput" placeholder="Search products...">
                <button class="positions-btn" type="submit">Search</button>
            </form>

            <div class="event-container">
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $eventID = $row['id'];
                        $eventURL = "../pages/event_details.php?id=$eventID";
                ?>
                        <a href="<?php echo $eventURL; ?>" class="event-info">
                            <h3 class="margin-bottom-medium"><?php echo isset($row['name']) ? $row['name'] : 'Event Name Not Available'; ?></h3>
                            <div>
                                <img src="../<?php echo $row['image']; ?>" alt="<?php echo $row['image']; ?>" style="max-width: 100%; height: auto; width: 400px; height: 300px;">
                            </div>
                            <p class="event-date"><strong>Date:</strong> <?php echo isset($row['date']) ? $row['date'] : 'Date Not Available'; ?></p>
                            <p>
                                <?php echo isset($row['description']) ? $row['description'] : 'Description Not Available'; ?></p>
                        </a>
                <?php
                    }
                } else {
                    echo "<h3 class='heading-tertiary'>No events available.</h3>";
                }
                ?>
            </div>
        </main>

        <footer class="footer">
            <a href="./about.php">About</a>
            <p>All rights reserved. Developed by Matsvei Balakhonau and Alexander Sapehin.</p>
            <p>Warsaw, Vistula University</p>
        </footer>
    </div>
    </div>


    <script>
        document.getElementById("searchInput").addEventListener("submit", function(event) {
            event.preventDefault();
            const searchQuery = this.value.toLowerCase().trim();

            window.location.href = "events.php?search=" + encodeURIComponent(searchQuery);
        });
    </script>
</body>

</html>