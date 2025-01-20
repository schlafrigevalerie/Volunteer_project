<?php
session_start(); // Начинаем сессию для работы с данными пользователя

require 'db.php';

// Получаем данные из запроса
$data = json_decode(file_get_contents('php://input'), true);
$first_name = $data['first_name'];
$last_name = $data['last_name'];
$middle_name = $data['middle_name']; // Добавляем отчество
$birth_date = $data['birth_date'];   // Добавляем дату рождения
$email = $data['email'];
$phone_number = $data['phone_number'];

// Предполагаем, что в сессии хранится id пользователя
$user_id = $_SESSION['user_id'];

$sql = "UPDATE users SET first_name = ?, last_name = ?, middle_name = ?, birth_date = ?, email = ?, phone_number = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssi", $first_name, $last_name, $middle_name, $birth_date, $email, $phone_number, $user_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}

$stmt->close();
$conn->close();
?>
