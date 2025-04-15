<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once('../controllers/cart_controller.php');  // Already included for CartController
require_once('../controllers/orders_controller.php');  // Add this to include OrderController

// Get the cart items for the logged-in user
$cartController = new CartController();
$userId = $_SESSION['user_id'];
$response = $cartController->viewCart($userId);

// Create an instance of OrderController to fetch pending orders
$orderController = new OrderController();
$pendingOrders = $orderController->getPendingOrders($userId); // Fetch pending orders

if ($response['success']) {
    $cartItems = $response['cartItems'];
} else {
    $cartItems = [];
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - PaintSipGH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #1a1a1d;
            color: #f4e9df;
        }

        .navbar {
            background: #1a1a1a;
            padding: 1rem 2rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .navbar-brand {
            color: #f5a623;
            font-size: 2rem;
            font-weight: bold;
        }

        .navbar-nav .nav-link {
            color: #f4e9df;
            margin-right: 1.5rem;
            font-weight: 500;
        }

        .navbar-nav .nav-link:hover {
            color: #d4a373;
        }

        .cart-header {
            text-align: center;
            color: #f5a623;
            font-size: 2rem;
            margin-top: 30px;
        }

        .cart-item {
            background-color: #24242c;
            border-radius: 10px;
            margin: 10px 0;
            padding: 15px;
        }

        .cart-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        .cart-item-details {
            flex: 1;
            margin-left: 20px;
        }

        .cart-item-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-item-footer button {
            background-color: #d4a373;
            border: none;
            padding: 5px 15px;
            border-radius: 20px;
            cursor: pointer;
            color: #1a1a1d;
            transition: background-color 0.3s ease;
        }

        .cart-item-footer button:hover {
            background-color: #f5a623;
        }

        .total-price {
            text-align: right;
            margin-top: 20px;
            font-size: 1.5rem;
            color: #d4a373;
        }

        .checkout-btn {
            background-color: #f5a623;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: bold;
            color: #1a1a1d;
            width: 100%;
        }

        .checkout-btn:hover {
            background-color: #d4a373;
        }
        

                /* Gap Space Between Sections */
       .gap-space {
            height: 100px;
            background: transparent; /* Keeps the background consistent */
        }

    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">PaintSipGH</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="UpcomingEvents.php">Back To Events</a></li>
                    <li class="nav-item"><a class="nav-link" href="http://localhost/PaintSipGH/">Home</a></li>
                </ul>
            
                <div class="dropdown ms-auto">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="actions/logout_process.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>



        <!-- Adding Gap Space -->
<div class="gap-space"></div>


    <!-- Cart Header -->
    <div class="cart-header">
        <h1>Your Cart</h1>
    </div>

    <!-- Cart Items -->
    <!-- Cart Items -->
<div class="container">
    <?php if (empty($cartItems)): ?>
        <div class="alert alert-warning text-center" role="alert">
            Your cart is empty! Please go back and add items to proceed.
        </div>
        <div class="text-center mt-3">
            <a href="http://localhost/PaintSipGH/view/UpcomingEvents.php" class="btn btn-primary">Browse Events</a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($cartItems as $item): ?>
                <div class="col-12 cart-item">
                    <div class="d-flex align-items-center">
                        <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="Event Image">
                        <div class="cart-item-details">
                            <h5><?php echo htmlspecialchars($item['event_name']); ?></h5>
                            <p><?php echo htmlspecialchars($item['location']); ?> | <?php echo htmlspecialchars($item['event_date']); ?></p>
                            <p>Price: $<?php echo number_format($item['ticket_price'], 2); ?></p>
                            <form action="../actions/update_cart.php" method="POST" class="d-flex align-items-center">
                                <label for="quantity_<?php echo $item['cart_id']; ?>" class="me-2">Quantity:</label>
                                <input type="number" name="quantity" id="quantity_<?php echo $item['cart_id']; ?>" value="<?php echo $item['quantity']; ?>" min="1" class="form-control w-25">
                                <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                <button type="submit" class="btn btn-sm btn-primary ms-2">Update</button>
                            </form>
                        </div>
                        <div class="cart-item-footer">
                            <!-- Cancel Order Form -->
                            <form action="../actions/cancel_order.php" method="POST" class="d-flex align-items-center">
                                <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm ms-2">Cancel Order</button>
                            </form>
                        </div>
                        <script>
                            // Function to show confirmation before removing item from cart
                            function confirmRemove() {
                                return confirm("Are you sure you want to remove this item from your cart?");
                            }
                        </script>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Total Price -->
        <div class="total-price">
            <p>Total Price: $<?php
                $totalPrice = 0;
                foreach ($cartItems as $item) {
                    $totalPrice += $item['ticket_price'] * $item['quantity'];
                }
                echo number_format($totalPrice, 2);
            ?></p>
        </div>

        <!-- Checkout Button -->
        <a href="../actions/create_order.php?items=<?php echo urlencode(json_encode($cartItems)); ?>&total=<?php echo urlencode($totalPrice); ?>" class="checkout-btn">Proceed to Checkout</a>
    <?php endif; ?>
</div>


<!-- Pending Orders Section -->
<!-- Pending Orders Section -->
<div class="container">
    <h2 class="cart-header">Your Pending Orders</h2>
    <?php if (empty($pendingOrders)): ?>
        <div class="alert alert-info text-center" role="alert">
            You have no pending orders.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($pendingOrders as $order): ?>
                <div class="col-12 cart-item">
                    <div class="d-flex align-items-center justify-content-between">
                        <!-- Left side: Event image and small details -->
                        <div class="d-flex align-items-center" style="flex: 1;">
                            <img src="<?php echo htmlspecialchars($order['image_path']); ?>" alt="Event Image" 
                                 style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                            <div class="ms-3">
                                <h5>Order #<?php echo htmlspecialchars($order['order_id']); ?></h5>
                                <p><strong><?php echo htmlspecialchars($order['event_name']); ?></strong></p>
                                <p>Date: <?php echo htmlspecialchars($order['event_date']); ?></p>
                            </div>
                        </div>

                        <!-- Right side: Order details -->
                        <div class="cart-item-details" style="flex: 2; padding-left: 20px;">
                            <p>Status: <?php echo htmlspecialchars($order['status']); ?></p>
                            <p>Order Date: <?php echo htmlspecialchars($order['order_date']); ?></p>
                            <p>Total: $<?php echo number_format($order['total_amount'], 2); ?></p>
                            <p>Quantity: <?php echo htmlspecialchars($order['quantity']); ?></p> <!-- Display Quantity -->
                        </div>

                        <!-- Action Buttons on the Far Right -->
                        <div class="d-flex flex-column align-items-end" style="flex: 1;">
                            <!-- Cancel Order Form -->
                            <form action="../actions/cancel_order.php" method="POST" class="d-flex align-items-center">
                                <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm ms-2">Cancel Order</button>
                            </form>

                            <!-- Make Payment Button with dynamic order ID -->
                            <button class="proceed-payment-btn" data-bs-toggle="modal" data-bs-target="#paymentModal" 
                                    data-order-id="<?php echo $order['order_id']; ?>"
                                    data-order-total="<?php echo $order['total_amount']; ?>"
                                    data-order-email="<?php echo $order['email']; ?>"
                                    data-order-first-name="<?php echo $order['first_name']; ?>"
                                    data-order-last-name="<?php echo $order['last_name']; ?>"
                                    >Proceed to Payment</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>



<!-- Payment Form Modal -->
<!-- Payment Form Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="paymentModalLabel">Contact Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form Submission -->
                <form id="paymentForm" action="../actions/process.php" method="POST">
                    <!-- Hidden Fields -->
                    <input type="hidden" name="order_id" id="order-id">
                    <input type="hidden" name="amount" id="amount">
                    <input type="hidden" name="reference" id="reference">

                    <!-- Email -->
                    <div class="form-group mb-3">
                        <label for="email-address" class="form-label">Email Address</label>
                        <input type="email" class="form-control" name="email" id="email-address" required>
                    </div>

                    <!-- First Name -->
                    <div class="form-group mb-3">
                        <label for="first-name" class="form-label">First Name</label>
                        <input type="text" class="form-control" name="first_name" id="first-name" required>
                    </div>

                    <!-- Last Name -->
                    <div class="form-group mb-3">
                        <label for="last-name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="last_name" id="last-name" required>
                    </div>

                    <!-- Pay Button -->
                    <div class="form-submit">
                        <button type="button" id="pay-now-btn" class="proceed-payment-btn">Pay</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
    // JavaScript to prepopulate the payment form when the modal is triggered
    document.addEventListener("DOMContentLoaded", function () {
        const paymentModal = document.getElementById("paymentModal");
        paymentModal.addEventListener("show.bs.modal", function (event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const orderId = button.getAttribute("data-order-id");
            const orderTotal = button.getAttribute("data-order-total");
            const orderEmail = button.getAttribute("data-order-email");
            const orderFirstName = button.getAttribute("data-order-first-name");
            const orderLastName = button.getAttribute("data-order-last-name");

            // Update the form fields with the data from the button
            document.getElementById("order-id").value = orderId;
            document.getElementById("amount").value = orderTotal;
            document.getElementById("email-address").value = orderEmail;
            document.getElementById("first-name").value = orderFirstName;
            document.getElementById("last-name").value = orderLastName;
        });
    });

    // JavaScript for payment processing
    document.getElementById("pay-now-btn").addEventListener("click", function () {
        const paymentForm = document.getElementById("paymentForm");

        // Use Paystack to handle the payment
        const handler = PaystackPop.setup({
            key: "pk_test_d8cdc927e8f3ffc614436c7f99bba6a0babe8d08", // Replace with your public key
            email: document.getElementById("email-address").value,
            amount: document.getElementById("amount").value * 100, // Amount in the lowest currency unit
            currency: "GHS",
            ref: "PS_" + Math.random().toString(36).substr(2, 9), // Unique reference
            callback: function (response) {
                // Set the reference value in the form and submit
                document.getElementById("reference").value = response.reference;
                paymentForm.submit();
            },
            onClose: function () {
                alert("Transaction was not completed, window closed.");
            },
        });
        handler.openIframe();
    });
</script>


    <!-- Footer -->
    <footer class="footer py-4">
        <div class="container text-center">
            <p>&copy; 2024 PaintSipGH. All Rights Reserved.</p>
            <div class="footer-links">
                <a href="#">Privacy Policy</a> |
                <a href="#">Terms of Service</a> |
                <a href="#">Contact Us</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script src="pay.js"></script>
<!-- 
    <script>
        function checkout() {
            alert("Proceeding to checkout...");
        }
    </script> -->
</body>
</html>