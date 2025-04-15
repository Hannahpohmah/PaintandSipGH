<?php
require_once('../classes/orders_class.php');

class OrderController {
    private $orderModel;

    public function __construct() {
        $this->orderModel = new Order();
    }

    // Create a new order
    public function createOrder($userId, $totalAmount) {
        return $this->orderModel->createOrder($userId, $totalAmount);
    }

    // Add order details
    public function addOrderDetail($orderId, $eventId, $quantity, $price) {
        return $this->orderModel->addOrderDetail($orderId, $eventId, $quantity, $price);
    }

  // Call getOrderDetails from order_class.php
  public function getOrderDetails($orderId)
  {
      try {
          return $this->orderModel->getOrderDetails($orderId);
      } catch (Exception $e) {
          return false; // Handle or log exceptions as needed
      }
  }

  // Call getOrderSummary from order_class.php
  public function getOrderSummary($orderId)
  {
      try {
          return $this->orderModel->getOrderSummary($orderId);
      } catch (Exception $e) {
          return false; // Handle or log exceptions as needed
      }
  }

  public function createPayment($orderId, $method = 'Card') {
    return $this->orderModel->createPayment($orderId, $method);
}


public function updatePaymentStatus($orderId, $status) {
    return $this->orderModel->updatePaymentStatus($orderId, $status);
}

public function updateOrderStatus($orderId, $status) {
    return $this->orderModel->updateOrderStatus($orderId, $status);
}


public function getAllTicketsByUser($userId) {
    $tickets = $this->orderModel->getAllTicketsByUser($userId);
    return $tickets ? $tickets : []; // Return an empty array if no tickets found
}

public function getPendingOrders($userId) {
    return $this->orderModel->getPendingOrders($userId);
}


public function cancelOrder($order_id) {
    $order = new Order();
    return $order->cancelOrder($order_id);
}

}
?>
