<?php
require_once '../classes/category_class.php';

// Initialize the Category class
$category = new Category();

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;

    // Add a new theme
    if ($action === 'add') {
        $theme_name = $_POST['theme_name'] ?? null;

        if (!empty($theme_name)) {
            $result = $category->addCategory($theme_name);
            echo json_encode([
                "success" => $result,
                "message" => $result ? "Theme added successfully!" : "Failed to add theme."
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Theme name cannot be empty."]);
        }
    }

    // Delete an existing theme
    if ($action === 'delete') {
        $theme_id = $_POST['theme_id'] ?? null;

        if (!empty($theme_id)) {
            $result = $category->deleteCategory($theme_id);
            echo json_encode([
                "success" => $result,
                "message" => $result ? "Theme deleted successfully!" : "Failed to delete theme."
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Theme ID is required for deletion."]);
        }
    }
}

// Handle GET requests
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $categories = $category->listCategories();

    if ($categories) {
        echo json_encode($categories);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to retrieve themes."]);
    }
}

// Handle unsupported request methods
else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
}
?>
