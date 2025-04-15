<?php
session_start();
require_once('../controllers/cart_controller.php');

// Check if the necessary data is passed (cart_id)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_id'])) {
    $cartController = new CartController();
    $cartId = $_POST['cart_id'];

    // Remove the item from the cart
    $response = $cartController->removeFromCart($cartId);

    if ($response['success']) {
        // Redirect to the cart page with a success message
        header('Location: ../view/cart_view.php?message=' . urlencode($response['message']));
        exit();
    } else {
        // If there was an error, redirect with an error message
        header('Location: ../view/cart_view.php?error=' . urlencode($response['message']));
        exit();
    }
} else {
    // Redirect back to the cart view if the necessary data is not provided
    header('Location: cart_view.php');
    exit();
}
?>
