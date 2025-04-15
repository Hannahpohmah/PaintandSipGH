<?php
require_once('../controllers/user_controller.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email and password are required!";
        header("Location: ../views/index.php");
        exit();
    }

    $userController = new UserController();
    $response = $userController->login($email, $password);

    if ($response['status']) {
        $_SESSION['user_id'] = $response['user']['user_id'];
        $_SESSION['user_type'] = $response['user']['user_type'];
        $_SESSION['first_name'] = $response['user']['first_name']; 
    
        // Store the business name explicitly if the user is a Partner
        if ($response['user']['user_type'] === 'Partner') {
            $_SESSION['business_name'] = $response['user']['business_name'];
        }
    
        // Store the user name dynamically based on user type
        $_SESSION['user_name'] = $response['user']['user_type'] === 'Customer' 
            ? $response['user']['first_name'] . " " . $response['user']['last_name'] 
            : $response['user']['business_name'];
        

        // Redirect based on user type
        if ($response['user']['user_type'] === 'Customer') {
            header("Location: http://localhost/PaintSipGH/"); // Redirect Customer
            exit();
        } else if ($response['user']['user_type'] === 'Partner')  {
            header("Location: http://localhost/PaintSipGH/"); // Redirect Business/Other
            exit();
        }
    } else {
        $_SESSION['error'] = $response['message'];
        header("Location: ../../index.php"); // Redirect back to login page
        exit();
    }
}
?>
