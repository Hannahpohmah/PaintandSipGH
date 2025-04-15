<?php
session_start();
require_once('../controllers/cart_controller.php');

// Check if the necessary data is passed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_id'], $_POST['quantity'])) {
    $cartController = new CartController();
    $cartId = $_POST['cart_id'];
    $quantity = $_POST['quantity'];

    // Update the cart item
    $response = $cartController->updateCart($cartId, $quantity);

    if ($response['success']) {
        header('Location: ../view/cart_view.php');
        exit();
    } else {
        // Handle error (e.g., return to the same page with an error message)
        header('Location: ../view/cart_view.php?error=' . urlencode($response['message']));
        exit();
    }
} else {
    // Redirect to the cart page if the necessary data is not provided
    header('Location: ../view/cart_view.php');
    exit();
}
?>
