<?php
session_start();

if (isset($_POST['product_id']) && isset($_POST['action'])) {
    $product_id = intval($_POST['product_id']);
    
    if ($_POST['action'] === 'remove' && isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Redirect back to cart
header('Location: cart.php');
exit();
?>
    