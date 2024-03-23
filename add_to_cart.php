<?php
session_start();

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    $_SESSION['cart'][] = $product_id;

    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('success' => false, 'message' => 'Product ID not provided'));
}
