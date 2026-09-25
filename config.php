<?php
// config.php - Database connection

$host = "localhost";
$db_name = "boragay";
$username = "root";      // ilisi kung lahi ang imong MySQL username
$password = "";          // ilisi kung naay password ang imong MySQL

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
