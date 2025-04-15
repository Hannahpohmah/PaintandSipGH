<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('../controllers/payments_controller.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['order_id'] ?? null;
    $amount = $_POST['amount'] ?? null;
    $email = $_POST['email'] ?? null;
    $paymentMethod = "Manual Confirmation"; // Default payment method for non-verified transactions

    if (!$orderId || !$amount || !$email) {
        echo json_encode(['status' => false, 'message' => 'Missing order details']);
        exit();
    }

    // Initialize Payment Controller
    $paymentController = new PaymentController();

    // Manually record the payment as confirmed
    $paymentResult = $paymentController->recordPayment($orderId, null, "Success", $paymentMethod);


    if ($paymentResult) {
        // Display success popup and redirect using JavaScript
        echo "
        <html>
            <head>
                <style>
                    body {
                        font-family: 'Poppins', sans-serif;
                        background-color: #1a1a1d;
                        color: #f4e9df;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                        margin: 0;
                    }
                    .popup {
                        background-color: #24242c;
                        border-radius: 10px;
                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
                        padding: 20px;
                        text-align: center;
                        color: #f4e9df;
                        max-width: 400px;
                        width: 90%;
                    }
                    .popup h1 {
                        color: #28a745;
                        margin-bottom: 15px;
                    }
                    .popup p {
                        margin-bottom: 20px;
                    }
                    .popup button {
                        padding: 10px 20px;
                        font-size: 1rem;
                        color: #1a1a1d;
                        background-color: #28a745;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                        transition: background-color 0.3s ease;
                    }
                    .popup button:hover {
                        background-color: #218838;
                    }
                </style>
            </head>
            <body>
                <div class='popup'>
                    <h1>Payment Successful!</h1>
                    <p>Thank you for your payment. Click the button below to view your tickets.</p>
                    <button onclick='redirectToTickets()'>View Tickets Now</button>
                </div>
                <script>
                    function redirectToTickets() {
                        window.location.href = '../view/success.php?order_id=" . urlencode($orderId) . "';
                    }
                </script>
            </body>
        </html>
        ";
        exit();
    } else {
        echo json_encode(['status' => false, 'message' => 'Failed to record payment in the database']);
    }
} else {
    echo json_encode(['status' => false, 'message' => 'Invalid request method']);
}
