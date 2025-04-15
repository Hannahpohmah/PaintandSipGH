<?php
// Start session
session_start();

// Include the category controller
require_once '../controllers/category_controller.php';

$response = []; // Array to hold the response

try {
    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method.');
    }

    // Check if the theme name is provided
    if (!isset($_POST['theme_name']) || empty(trim($_POST['theme_name']))) {
        throw new Exception('Theme name is required.');
    }

    // Sanitize and retrieve the theme name
    $theme_name = trim($_POST['theme_name']);

    // Instantiate the category controller
    $categoryController = new Category();

    // Call the addCategory method to insert the theme
    $result = $categoryController->addCategory($theme_name);

    if ($result) {
        // Success response
        $response['success'] = true;
        $response['message'] = 'Theme added successfully.';
    } else {
        // Failure response
        throw new Exception('Failed to add theme. Please try again.');
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
