<?php
// This script generates a bcrypt hash for a given password
$password = 'CHANGE_ME'; // Change this to your desired password
$hash = password_hash($password, PASSWORD_BCRYPT);
echo "Password: " . $password . "\n";
echo "Hash: " . $hash . "\n";
?>
