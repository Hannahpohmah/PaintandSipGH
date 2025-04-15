<?php
// Enable detailed error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once('../controllers/cart_controller.php');
require_once('../controllers/orders_controller.php');

try {
    // Step 1: Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        throw new Exception("Error: You are not logged in. Please log in to proceed.");
    }
    $userId = $_SESSION['user_id']; // Get user ID from session

    // Step 2: Validate total amount and cart items
    $totalAmount = $_GET['total'] ?? 0;
    $cartItems = json_decode(urldecode($_GET['items'] ?? '[]'), true);

    if (empty($cartItems)) {
        throw new Exception("Your cart is empty. Please add items to your cart before proceeding.");
    }

    if ($totalAmount <= 0) {
        throw new Exception("Total amount must be greater than zero.");
    }

    // Step 3: Initialize controllers
    $orderController = new OrderController();
    $cartController = new CartController();

    // Step 4: Create the order
    echo "<p>Attempting to create order for User ID: $userId with total amount: $totalAmount...</p>";
    $orderId = $orderController->createOrder($userId, $totalAmount);

    if (!$orderId) {
        throw new Exception("Failed to create the order. Please check the database connection or input data.");
    }
    echo "<p>Order created successfully with Order ID: $orderId.</p>";

    // Step 5: Add each cart item to order details
    echo "<p>Adding items to order details...</p>";
    foreach ($cartItems as $item) {
        // Validate cart item details
        if (!isset($item['event_id']) || !isset($item['quantity']) || !isset($item['ticket_price'])) {
            throw new Exception("Cart item data is incomplete. Event ID, Quantity, and Ticket Price are required.");
        }

        echo "<p>Processing item: Event ID {$item['event_id']}, Quantity: {$item['quantity']}, Price: {$item['ticket_price']}...</p>";
        $orderDetailsResult = $orderController->addOrderDetail(
            $orderId,
            $item['event_id'],
            $item['quantity'],
            $item['ticket_price']
        );

        if (!$orderDetailsResult) {
            throw new Exception("Failed to add item (Event ID: {$item['event_id']}) to order details. Please check database constraints.");
        }
        echo "<p>Item added successfully: Event ID {$item['event_id']}.</p>";
    }

    // Step 6: Clear the user's cart
    echo "<p>Clearing the user's cart...</p>";
    $clearCartResult = $cartController->clearCart($userId);
    if (!$clearCartResult) {
        throw new Exception("Failed to clear the cart. Please check database operations.");
    }
    echo "<p>Cart cleared successfully for User ID: $userId.</p>";

    // Step 7: Redirect to checkout process page
    echo "<p>Redirecting to checkout form page...</p>";
    header("Location: ../view/checkoutform.php?order_id=$orderId");
    exit();
} catch (Exception $e) {
    // Log error details for debugging
    error_log("Error during order process: " . $e->getMessage());

    // Display detailed error message to the user
    echo "<h3 style='color: red;'>An error occurred: " . htmlspecialchars($e->getMessage()) . "</h3>";

    // Additional handling for authorization errors
    if (str_contains($e->getMessage(), "Authorization")) {
        echo "<h4>Hint: Ensure that the authorization header follows the format: 'Bearer YOUR_SECRET_KEY'.</h4>";
        echo "<p>For example, use the following header: <code>Authorization: Bearer sk_123456789</code></p>";
    }

    // Optional: Provide a back button for user convenience
    echo '<p><a href="../view/cart.php" style="color: blue;">Go back to your cart</a></p>';
}
?>
