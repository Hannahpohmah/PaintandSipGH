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

        .navbar {
            background-color: #1a1a1d;
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
        <div class="order-summary">
            <h1>Thank You for Your Order!</h1>
            <p>Order ID: <strong><?php echo htmlspecialchars($orderSummary['order_id']); ?></strong></p>
            <p>Total Amount: <strong>$<?php echo number_format($orderSummary['total_amount'], 2); ?></strong></p>
            <p>Order Date: <strong><?php echo htmlspecialchars($orderSummary['order_date']); ?></strong></p>
        </div>

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
        <button class="proceed-payment-btn" onclick="proceedToPayment()">Proceed to Payment</button>
    </div>

    <footer class="footer">
        <p>&copy; 2024 PaintSipGH. All Rights Reserved.</p>
    </footer>

    <script>
        function proceedToPayment() {
            alert("Our payment process is still upcoming. You will hear from us soon.");
        }
    </script>
</body>
</html>
