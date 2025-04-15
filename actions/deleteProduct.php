<?php
require_once('../controllers/product_controller.php');

header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the data from the POST request
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['event_id'])) {
        $eventId = $data['event_id'];

        // Initialize the ProductController
        $productController = new ProductController();

        // Call the deleteEvent method
        $result = $productController->deleteEvent($eventId);

        echo json_encode($result); // Return the result (success or failure)
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Event ID is required."
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
}
