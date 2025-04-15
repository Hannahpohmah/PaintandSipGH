<?php
require_once('../controllers/orders_controller.php');

if (!isset($_GET['reference']) || !isset($_GET['order_id'])) {
    die("Missing reference or order ID.");
}

$reference = $_GET['reference'];
$orderId = $_GET['order_id'];

$secretKey = "sk_test_YOUR_SECRET_KEY"; // Replace with your secret key

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . $reference,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array("Authorization: Bearer $secretKey"),
));

$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

$data = json_decode($response, true);
$orderController = new OrderController();

if ($httpCode === 200 && $data['data']['status'] === 'success') {
    $orderController->updatePaymentStatus($orderId, 'Success');
    $orderController->updateOrderStatus($orderId, 'Completed');
    header("Location: success.php?order_id=" . $orderId);
    exit();
} else {
    $orderController->updatePaymentStatus($orderId, 'Failed');
    echo "Payment verification failed. Please try again.";
}
?>