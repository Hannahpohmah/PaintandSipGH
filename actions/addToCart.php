<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('../controllers/cart_controller.php');

// Check if the necessary data is passed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['event_id'], $_POST['quantity'])) {
    // Create an instance of CartController
    $cartController = new CartController();
    
    // Call the method to add the event to the cart
    $response = $cartController->addToCart($_POST['user_id'], $_POST['event_id'], $_POST['quantity']);
    
    // If the addition is successful, redirect to cart_view.php, otherwise show an error
    if ($response['success']) {
        header('Location: ../view/cart_view.php?message=' . urlencode($response['message']));
    } else {
        // Handle error (e.g., return to the same page with an error message)
        header('Location: upcoming_events.php?error=' . urlencode($response['message']));
    }
    exit();
} else {
    // Redirect to the events page if the necessary data is not provided
    header('Location: ../view/upcoming_events.php');
    exit();
}
?>
