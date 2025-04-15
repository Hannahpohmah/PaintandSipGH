<?php
require_once('../controllers/product_controller.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and collect form data
    $eventId = $_POST['event_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $date = $_POST['date'];
    $price = $_POST['price'];
    $totalSpots = $_POST['total_spots'];
    $themeId = $_POST['theme_id'];

    // Handle the image if provided
    $image = isset($_FILES['image']) ? $_FILES['image'] : null;

    $productController = new ProductController();
    $result = $productController->updateEvent($eventId, [
        'name' => $name,
        'description' => $description,
        'location' => $location,
        'date' => $date,
        'price' => $price,
        'total_spots' => $totalSpots,
        'theme_id' => $themeId,
        'image' => $image
    ]);

    // Redirect or show a message based on success or failure
    if ($result['success']) {
        // Redirect to the same page and pass the success message
        header("Location: http://localhost/PaintSipGH/view/addProductView.php"); 
        exit();
    } else {
        // If error, show the error message
        echo 'Error: ' . $result['message'];
    }
}
?>
