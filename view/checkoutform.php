<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('../controllers/orders_controller.php');

// Check if order_id is set in the URL
if (!isset($_GET['order_id'])) {
    echo "<p style='color: red;'>Invalid order ID.</p>";
    exit();
}

$orderId = $_GET['order_id'];
$orderController = new OrderController();

// Fetch order summary and details
$orderDetails = $orderController->getOrderDetails($orderId);
$orderSummary = $orderController->getOrderSummary($orderId);

// Handle missing data
if (!$orderSummary || empty($orderDetails)) {
    echo "<p style='color: red;'>Unable to fetch order details.</p>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #1a1a1d;
            color: #f4e9df;
            margin: 0;
            padding: 0;
        }

        .container {
            margin-top: 50px;
        }

        .order-summary {
            background-color: #24242c;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        .order-summary h1 {
            color: #f5a623;
        }

        .order-details {
            margin-top: 20px;
        }

        .order-details .item {
            background-color: #1a1a1d;
            border: 1px solid #d4a373;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .order-details .item h5 {
            color: #f5a623;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            padding: 10px 0;
            background-color: #1a1a1a;
        }

        .proceed-payment-btn {
            display: block;
            width: 100%;
            max-width: 300px;
            margin: 20px auto;
            padding: 10px 20px;
            font-size: 1.1rem;
            font-weight: bold;
            text-align: center;
            background-color: #f5a623;
            color: #1a1a1d;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .proceed-payment-btn:hover {
            background-color: #d4a373;
        }

        .modal-content {
            background-color: #24242c;
            color: #f4e9df;
            border-radius: 10px;
            padding: 20px;
        }

        .modal-header h5 {
            color: #f5a623;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            background-color: #1a1a1d;
            border: 1px solid #d4a373;
            color: #f4e9df;
            border-radius: 5px;
        }

        .form-submit button {
            display: block;
            width: 100%;
            max-width: 300px;
            margin: 20px auto;
            padding: 10px 20px;
            font-size: 1.1rem;
            font-weight: bold;
            text-align: center;
            background-color: #f5a623;
            color: #1a1a1d;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-submit button:hover {
            background-color: #d4a373;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="#">PaintSipGH</a>
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="http://localhost/PaintSipGH/">Home</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <!-- Order Summary -->
        <div class="order-summary">
            <h1>Thank You for Your Order!</h1>
            <p>Order ID: <strong><?php echo htmlspecialchars($orderSummary['order_id']); ?></strong></p>
            <p>Total Amount: <strong>$<?php echo number_format($orderSummary['total_amount'], 2); ?></strong></p>
            <p>Order Date: <strong><?php echo htmlspecialchars($orderSummary['order_date']); ?></strong></p>
        </div>

        <!-- Order Details -->
        <div class="order-details">
            <h2>Order Details</h2>
            <?php foreach ($orderDetails as $detail): ?>
                <div class="item">
                    <h5><?php echo htmlspecialchars($detail['event_name']); ?></h5>
                    <p>Quantity: <?php echo htmlspecialchars($detail['quantity']); ?></p>
                    <p>Price per Ticket: $<?php echo number_format($detail['price'], 2); ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Proceed to Payment Button -->
        <button class="proceed-payment-btn" data-bs-toggle="modal" data-bs-target="#paymentModal">Proceed to Payment</button>
        <a class="proceed-payment-btn" href="http://localhost/PaintSipGH/view/cart_view.php " class="checkout-btn">Back to cart</a>
    </div>

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
                    <input type="hidden" name="order_id" id="order-id" value="<?php echo htmlspecialchars($orderSummary['order_id']); ?>">
                    <input type="hidden" name="amount" id="amount" value="<?php echo htmlspecialchars($orderSummary['total_amount']); ?>">
                    <input type="hidden" name="reference" id="reference">

                    <!-- Email -->
                    <div class="form-group mb-3">
                        <label for="email-address" class="form-label">Email Address</label>
                        <input type="email" class="form-control" name="email" id="email-address" value="<?php echo htmlspecialchars($orderSummary['email'] ?? ''); ?>" required>
                    </div>

                    <!-- First Name -->
                    <div class="form-group mb-3">
                        <label for="first-name" class="form-label">First Name</label>
                        <input type="text" class="form-control" name="first_name" id="first-name" value="<?php echo htmlspecialchars($orderDetails[0]['first_name'] ?? ''); ?>" required>
                    </div>

                    <!-- Last Name -->
                    <div class="form-group mb-3">
                        <label for="last-name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="last_name" id="last-name" value="<?php echo htmlspecialchars($orderDetails[0]['last_name'] ?? ''); ?>" required>
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
    // Attach event listener to the Pay button
    document.getElementById('pay-now-btn').addEventListener('click', function () {
        var paymentForm = document.getElementById('paymentForm'); // Form element

        // Initialize Paystack handler
        var handler = PaystackPop.setup({
            key: 'pk_test_af4b2b0ff8db40e1e61fd701534af43e14e19f18', // Replace with your Paystack public key
            email: document.getElementById('email-address').value, // Email from the form
            amount: document.getElementById('amount').value * 100, // Amount in the lowest currency unit (e.g., pesewas)
            currency: 'GHS', // Currency code
            ref: "PS_" + Math.random().toString(36).substr(2, 9), // Generate a unique reference
            
            // On successful payment
            callback: function (response) {
                console.log("Transaction reference:", response.reference); // Log for debugging

                // Update the hidden input with the returned reference
                document.getElementById('reference').value = response.reference;

                // Submit the form to the backend
                paymentForm.submit();
            },

            // When payment is canceled
            onClose: function () {
                alert('Transaction was not completed, window closed.');
            }
        });

        // Open the Paystack iframe
        handler.openIframe();
    });
</script>


    <footer class="footer">
        <p>&copy; 2024 PaintSipGH. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script src="pay.js"></script>
</body>
</html>
