<?php
// getEventDetails.php

require_once('../controllers/product_controller.php');

// Check if event_id is passed via GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['event_id'])) {
        $productController = new ProductController();
        $eventDetails = $productController->getEventDetails($data['event_id']);

        if ($eventDetails) {
            echo json_encode(['success' => true, 'data' => $eventDetails]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Event not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Event ID is required']);
    }
}
?>
