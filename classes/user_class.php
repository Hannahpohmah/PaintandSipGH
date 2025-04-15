<?php
require_once('../settings/db_class.php'); // Database connection class

class User extends db_connection
{
    // Function to check if an email already exists
    public function checkEmailExists($email)
    {
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = $this->db_fetch_one($query);
        return $result; // Returns the user record if found
    }

    // Function to register a new customer
    public function registerUser($first_name, $last_name, $email, $hashed_password, $contact_number)
    {
        $query = "INSERT INTO users (first_name, last_name, email, password, user_type, contact_number) 
                  VALUES ('$first_name', '$last_name', '$email', '$hashed_password', 'Customer', $contact_number)";
        return $this->db_query($query); // Returns true if successful
    }

    // Function to get a user by email
    public function getUserByEmail($email)
    {
        $query = "SELECT * FROM users WHERE email = '$email'";
        return $this->db_fetch_one($query); // Returns the user record if found
    }


 // Function to get a user by contact_number
 public function getUserByContactNumber($contact_number)
 {
     $query = "SELECT * FROM users WHERE contact_number = '$contact_number'";
     return $this->db_fetch_one($query); // Returns the user record if found
 }
}
?>
