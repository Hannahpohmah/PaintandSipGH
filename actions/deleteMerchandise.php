<?php
require_once('../controllers/merchandise_controller.php');

header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the data from the POST request
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['merchandise_id'])) {
        $merchandiseId = $data['merchandise_id'];

        // Initialize the MerchandiseController
        $merchandiseController = new MerchandiseController();

        // Call the deleteMerchandise method
        $result = $merchandiseController->deleteMerchandise($merchandiseId);

        echo json_encode($result); // Return the result (success or failure)
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Merchandise ID is required."
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
}
?>
