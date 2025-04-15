<?php
require_once('../settings/db_class.php');

class Order extends db_connection {
    // Create a new order
    public function createOrder($userId, $totalAmount) {
        $sql = "INSERT INTO orders (user_id, total_amount, order_date, status) VALUES (?, ?, NOW(), 'Pending')";
        $stmt = $this->db_conn()->prepare($sql);
        $stmt->bind_param("id", $userId, $totalAmount);

        if ($stmt->execute()) {
            return $stmt->insert_id; // Return the new order ID
        }
        return false;
    }

    // Add details to the order
    public function addOrderDetail($orderId, $eventId, $quantity, $price) {
        $sql = "INSERT INTO order_details (order_id, event_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $this->db_conn()->prepare($sql);
        $stmt->bind_param("iiid", $orderId, $eventId, $quantity, $price);

        return $stmt->execute();
    }

// Get details of an order including user details
public function getOrderDetails($orderId) {
    try {
        $sql = "SELECT 
                    od.order_detail_id, 
                    od.order_id, 
                    od.event_id, 
                    od.quantity, 
                    od.price, 
                    e.event_name,
                    e.event_date,
                    e.image_path,
                    u.email,
                    u.first_name,
                    u.last_name
                FROM order_details od
                JOIN events e ON od.event_id = e.event_id
                JOIN orders o ON od.order_id = o.order_id
                JOIN users u ON o.user_id = u.user_id
                WHERE od.order_id = ?";
        $stmt = $this->db_conn()->prepare($sql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
        return false;
    }
}

public function getAllTicketsByUser($userId) {
    try {
        $sql = "SELECT 
                    od.order_id, 
                    e.event_name, 
                    e.event_date, 
                    od.quantity, 
                    od.price, 
                    e.image_path, 
                    p.paid_at
                FROM order_details od
                JOIN events e ON od.event_id = e.event_id
                JOIN orders o ON od.order_id = o.order_id
                JOIN payments p ON o.order_id = p.order_id
                WHERE o.user_id = ? AND p.payment_status = 'Success'";
        $stmt = $this->db_conn()->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
        return []; // Return an empty array on failure
    }
}

public function cancelOrder($order_id) {
    $sql = "UPDATE orders SET status = 'canceled' WHERE order_id = '$order_id'";
    return $this->db_query($sql);
}

public function getPendingOrders($userId) {
    // Adjust the query to join orders, order_details, events, and users
    $sql = "SELECT o.order_id, o.user_id, o.total_amount, o.order_date, o.status,
                   od.event_id, e.event_name, e.event_date, e.image_path, od.quantity,
                   u.first_name, u.last_name, u.email
            FROM orders o
            JOIN order_details od ON o.order_id = od.order_id
            JOIN events e ON od.event_id = e.event_id
            JOIN users u ON o.user_id = u.user_id
            WHERE o.user_id = ? AND o.status = 'Pending'";

    $stmt = $this->db_conn()->prepare($sql);
    $stmt->bind_param("i", $userId);

    if (!$stmt->execute()) {
        throw new Exception("Error fetching pending orders with event details: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $orders = [];

    while ($order = $result->fetch_assoc()) {
        $orders[] = $order; // Store order along with event details, user info, and quantity
    }

    return $orders;
}




    // Get summary of an order
    public function getOrderSummary($orderId) {
        try {
            // Join orders and users to include email
            $sql = "SELECT o.*, u.email 
                    FROM orders o 
                    JOIN users u ON o.user_id = u.user_id 
                    WHERE o.order_id = ?";
            $stmt = $this->db_conn()->prepare($sql);
            $stmt->bind_param("i", $orderId);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (Exception $e) {
            return false;
        }
    }
    

    public function updatePaymentStatus($orderId, $status) {
        $sql = "UPDATE payments SET payment_status = ? WHERE order_id = ?";
        $stmt = $this->db_conn()->prepare($sql);
        $stmt->bind_param("si", $status, $orderId);
        return $stmt->execute();
    }

    public function updateOrderStatus($orderId, $status) {
        $sql = "UPDATE orders SET status = ? WHERE order_id = ?";
        $stmt = $this->db_conn()->prepare($sql);
        $stmt->bind_param("si", $status, $orderId);
        return $stmt->execute();
    }

    public function createPayment($orderId, $method = 'Card') {
        $sql = "INSERT INTO payments (order_id, payment_method, payment_status) VALUES (?, ?, 'Pending')";
        $stmt = $this->db_conn()->prepare($sql);
        $stmt->bind_param("is", $orderId, $method);
        return $stmt->execute();
    }
    

}
?>
