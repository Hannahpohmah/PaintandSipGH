<?php
require_once('../settings/db_class.php'); // Assuming you have a db connection class

class Cart extends db_connection {
    private $db_connection; // Declare the property explicitly

    // Constructor to ensure the connection is established
    public function __construct() {
        $this->db_connection = $this->db_conn();
    }


    // Add an event to the cart
    public function addToCart($userId, $eventId, $quantity) {
        $sql = "SELECT * FROM carts WHERE user_id = ? AND event_id = ?";
        $stmt = $this->db_connection->prepare($sql);
        $stmt->bind_param("ii", $userId, $eventId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Event already in cart, update the quantity
            $sql = "UPDATE carts SET quantity = quantity + ? WHERE user_id = ? AND event_id = ?";
            $stmt = $this->db_connection->prepare($sql);
            $stmt->bind_param("iii", $quantity, $userId, $eventId);
            return $stmt->execute();
        } else {
            // Event not in cart, add it
            $sql = "INSERT INTO carts (user_id, event_id, quantity, added_at) VALUES (?, ?, ?, NOW())";
            $stmt = $this->db_connection->prepare($sql);
            $stmt->bind_param("iii", $userId, $eventId, $quantity);
            return $stmt->execute();
        }
    }

    // Update cart item quantity
    public function updateCart($cartId, $newQuantity) {
        $sql = "UPDATE carts SET quantity = ? WHERE cart_id = ?";
        $stmt = $this->db_connection->prepare($sql);
        $stmt->bind_param("ii", $newQuantity, $cartId);
        return $stmt->execute();
    }

    // Remove an item from the cart
    public function removeFromCart($cartId) {
        $sql = "DELETE FROM carts WHERE cart_id = ?";
        $stmt = $this->db_connection->prepare($sql);
        $stmt->bind_param("i", $cartId);
        return $stmt->execute();
    }

    // View the user's cart
    public function viewCart($userId) {
        $sql = "SELECT c.cart_id, c.quantity, e.event_id, e.event_name, e.event_date, e.location, e.ticket_price, e.image_path 
                FROM carts c 
                JOIN events e ON c.event_id = e.event_id
                WHERE c.user_id = ?";
        $stmt = $this->db_conn()->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    

   // Clear the cart for a user
   public function clearCart($userId) {
    $sql = "DELETE FROM carts WHERE user_id = ?";
    $stmt = $this->db_connection->prepare($sql);
    $stmt->bind_param("i", $userId);
    return $stmt->execute();
}

}
?>
