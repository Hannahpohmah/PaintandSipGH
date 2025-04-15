<?php
require_once('../classes/cart_class.php');

class CartController {
    private $cartModel;

    public function __construct() {
        $this->cartModel = new Cart();
    }

    // Add an event to the cart
    public function addToCart($userId, $eventId, $quantity) {
        try {
            $result = $this->cartModel->addToCart($userId, $eventId, $quantity);
            if (!$result) {
                throw new Exception("Failed to add event to cart.");
            }
            return ['success' => true, 'message' => 'Event added to your cart.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Update the cart item
    public function updateCart($cartId, $newQuantity) {
        try {
            $result = $this->cartModel->updateCart($cartId, $newQuantity);
            if (!$result) {
                throw new Exception("Failed to update cart.");
            }
            return ['success' => true, 'message' => 'Cart updated successfully.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Remove an item from the cart
    public function removeFromCart($cartId) {
        try {
            $result = $this->cartModel->removeFromCart($cartId);
            if (!$result) {
                throw new Exception("Failed to remove item from cart.");
            }
            return ['success' => true, 'message' => 'Event removed from cart.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // View the user's cart
    public function viewCart($userId) {
        try {
            $cartItems = $this->cartModel->viewCart($userId);
            if (empty($cartItems)) {
                throw new Exception("Your cart is empty.");
            }
            return ['success' => true, 'cartItems' => $cartItems];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function clearCart($userId)
    {
        $response = $this->cartModel->clearCart($userId);
        return $response;
    }
}
?>
