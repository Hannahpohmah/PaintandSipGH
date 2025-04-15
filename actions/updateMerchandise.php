<?php
require_once('../controllers/merchandise_controller.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and validate form data
    $merchandiseId = $_POST['merchandise_id'];
    $name = $_POST['merchandise_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity_available'];

    // Handle the image if provided
    $image = isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK ? $_FILES['image'] : null;

    // Initialize product controller
    $productController = new MerchandiseController();

    // Call the updateMerchandise method
    $result = $productController-> updateMerchandise($merchandiseId, [
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'quantity' => $quantity,
        'image' => $image
    ]);

    // Redirect or display an error based on the result
    if ($result['success']) {
        header('Location: ../view/addMerchandiseView.php?message=' . urlencode($result['message']));
        exit();
    } else {
        echo 'Error: ' . $result['message'];
    }
}
?>