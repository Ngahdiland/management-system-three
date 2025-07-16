<?php
// Change 'mynewpassword' to your desired password
$password = 'mynewpassword';
echo 'Password: ' . $password . "<br>";
echo 'Hash: ' . password_hash($password, PASSWORD_DEFAULT);
?> 