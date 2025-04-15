<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('../controllers/orders_controller.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['order_id'] ?? null;

    if (!$orderId) {
        echo json_encode(['status' => false, 'message' => 'Order ID is required']);
        exit();
    }

    // Initialize OrderController
    $orderController = new OrderController();

    // Attempt to cancel the order
    $result = $orderController->cancelOrder($orderId);

    if ($result) {
        echo json_encode(['status' => true, 'message' => 'Order cancelled successfully']);
        header("Location: ../view/cart_view.php?success= order cancelled!");
    } else {
        echo json_encode(['status' => false, 'message' => 'Failed to cancel order']);
    }
} else {
    echo json_encode(['status' => false, 'message' => 'Invalid request method']);
}
?>