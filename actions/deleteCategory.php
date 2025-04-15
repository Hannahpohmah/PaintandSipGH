<?php
require_once '../classes/category_class.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $theme_id = $_POST['theme_id'] ?? null;

    if (!empty($theme_id)) {
        $category = new Category();
        $result = $category->deleteCategory($theme_id);

        if ($result) {
            header("Location: ../view/addCategoryView.php?success=Theme deleted successfully!");
            exit();
        } else {
            header("Location: ../view/addCategoryView.php?error=Failed to delete theme.");
            exit();
        }
    } else {
        header("Location: ../view/addCategoryView.php?error=Theme ID is required.");
        exit();
    }
} else {
    header("Location: ../view/addCategoryView.php?error=Invalid request.");
    exit();
}
