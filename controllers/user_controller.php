<?php
require_once('../classes/user_class.php');

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User(); // Initialize the User model
    }

    // Handles signup
    public function signup($first_name, $last_name, $email, $password, $contact_number)
    {
        // Check if email already exists
        if ($this->userModel->checkEmailExists($email)) {
            return ['status' => false, 'message' => 'Email already registered'];
        }

        // Validate password
        $passwordValidation = $this->validatePassword($password);
        if (!$passwordValidation['status']) {
            return ['status' => false, 'message' => $passwordValidation['message']];
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Register the user
        if ($this->userModel->registerUser($first_name, $last_name, $email, $hashed_password, $contact_number)) {
            return ['status' => true, 'message' => 'Registration successful'];
        } else {
            return ['status' => false, 'message' => 'Registration failed. Please try again.'];
        }
    }

    // Handles login
    public function login($email, $password)
    {
        // Fetch the user by email
        $user = $this->userModel->getUserByEmail($email);

        if (!$user) {
            return ['status' => false, 'message' => 'Invalid credentials'];
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            return ['status' => false, 'message' => 'Invalid credentials'];
        }

        // Return user details for session setup
        return ['status' => true, 'user' => $user];
    }

    // Validates the password format
    private function validatePassword($password)
    {
        // Define password requirements
        $minLength = 8;
        $hasUppercase = preg_match('/[A-Z]/', $password);
        $hasLowercase = preg_match('/[a-z]/', $password);
        $hasNumber = preg_match('/[0-9]/', $password);
        $hasSpecialChar = preg_match('/[\W_]/', $password); // Matches non-word characters

        if (strlen($password) < $minLength) {
            return ['status' => false, 'message' => 'Password must be at least 8 characters long'];
        }
        if (!$hasUppercase) {
            return ['status' => false, 'message' => 'Password must contain at least one uppercase letter'];
        }
        if (!$hasLowercase) {
            return ['status' => false, 'message' => 'Password must contain at least one lowercase letter'];
        }
        if (!$hasNumber) {
            return ['status' => false, 'message' => 'Password must contain at least one number'];
        }
        if (!$hasSpecialChar) {
            return ['status' => false, 'message' => 'Password must contain at least one special character'];
        }

        return ['status' => true, 'message' => 'Password is valid'];
    }
}
?>

