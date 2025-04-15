<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once('../controllers/merchandise_controller.php');

$response = []; // Array to hold the response

try {
    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method.');
    }

    // Ensure that the user is logged in and is a partner
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Partner') {
        throw new Exception('Unauthorized access. Only partners can add merchandise.');
    }

    // Ensure that the file upload is set
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Image file is required and must be valid.');
    }

    // Initialize the MerchandiseController
    $controller = new MerchandiseController();

    // Call the addMerchandise method and pass form data and the uploaded file
    $result = $controller->addMerchandise($_POST, $_FILES['image']);

    // Check if the operation was successful
    if ($result['success']) {
        $response['success'] = true;
        $response['message'] = $result['message'];
    } else {
        throw new Exception($result['message']); // Use the error message from the controller
    }
} catch (Exception $e) {
    // Catch any errors and store them in the response
    $response['success'] = false;
    $response['message'] = $e->getMessage();
}

// Output the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
exit();
?>
