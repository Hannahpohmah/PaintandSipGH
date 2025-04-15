<?php
// Replace this with the plain text password you want to hash
$plain_password = 'hannah';

// Hash the password using the default algorithm (BCRYPT)
$hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

// Echo the hashed password
echo "Plain Password: " . $plain_password . "<br>";
echo "Hashed Password: " . $hashed_password;
?>
