<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../controllers/user_controller.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $contact_number = $_POST['contact_number'];

    // Validate inputs
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($contact_number)) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: ../views/index.php");
        exit();
    }

    // Create an instance of UserController
    $userController = new UserController();

    // Attempt signup
    $response = $userController->signup($first_name, $last_name, $email, $password, $contact_number);

    // Check the response and redirect with appropriate messages
    if ($response['status']) {
        $_SESSION['success'] = "Signup successful! Please log in to continue.";
        $_SESSION['show_login_modal'] = true; // Set a flag to trigger the login modal
        header("Location: http://localhost/PaintSipGH/");
        exit();
    } else {
        $_SESSION['error'] = $response['message'];
        header("Location: ../views/index.php");
        exit();
    }
} else {
    // Redirect if accessed without POST request
    $_SESSION['error'] = "Invalid request method.";
    header("Location: ../views/index.php");
    exit();
}
?>
