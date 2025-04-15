<?php
require_once('../classes/payments_class.php');

class PaymentController {
    private $paymentModel;

    public function __construct() {
        $this->paymentModel = new Payment();
    }

    public function recordPayment($orderId, $reference, $status, $method) {
        return $this->paymentModel->recordPayment($orderId, $reference, $status, $method);
    }

    public function updatePaymentStatus($reference, $status) {
        return $this->paymentModel->updatePaymentStatus($reference, $status);
    }


}
?>
