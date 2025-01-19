<?php
$host = 'localhost';
$user = 'root'; 
$password = ''; 
$dbname = 'HeadHunter'; 

$conn = new mysqli($host, $user, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

