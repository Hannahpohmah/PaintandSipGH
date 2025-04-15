<?php
require_once('../settings/db_class.php');

class Payment extends db_connection {
    public function recordPayment($orderId, $reference, $status, $method) {
        // Insert the payment record
        $sql = "INSERT INTO payments (order_id, payment_method, payment_status, paid_at) VALUES (?, ?, ?, NOW())";
        $stmt = $this->db_conn()->prepare($sql);
    
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->db_conn()->error);
        }
    
        $stmt->bind_param("iss", $orderId, $method, $status);
    
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
    
        // Update the order status to 'Completed' after the payment is recorded
        $updateStatusSql = "UPDATE orders SET status = 'Completed' WHERE order_id = ?";
        $stmt = $this->db_conn()->prepare($updateStatusSql);
        $stmt->bind_param("i", $orderId);
    
        if (!$stmt->execute()) {
            throw new Exception("Failed to update order status: " . $stmt->error);
        }
    
        return true;
    }
    
    

    // Update payment status
    public function updatePaymentStatus($reference, $status) {
        $sql = "UPDATE payments SET payment_status = ? WHERE payment_id = ?";
        $stmt = $this->db_conn()->prepare($sql);
        $stmt->bind_param("si", $status, $reference);

        return $stmt->execute();
    }

}
?>
