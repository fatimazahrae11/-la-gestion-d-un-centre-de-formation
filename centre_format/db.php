<?php
$server = 'localhost';
$username = 'root';
$password = '';
$db_name = 'centre_format';

try {
$pdo = new PDO("mysql:host=$server;dbname=$db_name", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
echo "Connection failed: " . $conn->getMessage();
}
?>