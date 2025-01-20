<?php
// Подключаем файл с настройками базы данных
require 'db.php'; 

// Стартуем сессию, чтобы получить доступ к user_id
session_start();

// Проверка, что пользователь авторизован
if (!isset($_SESSION['user_id'])) {
    $response = [
        'success' => false,
        'message' => 'Пользователь не авторизован.'
    ];
    echo json_encode($response);
    exit;
}

// Получаем ID пользователя из сессии
$userId = $_SESSION['user_id']; 

// Получаем данные из формы
$sex = $_POST['sex'] ?? null;
$education = $_POST['education'] ?? null;
$dream = $_POST['dream'] ?? null;
$skills = $_POST['skills'] ?? null;
$health = $_POST['health'] ?? null;

// Переменные для ответа
$response = array('success' => false, 'message' => '');

// SQL-запрос для сохранения резюме
$sql = "INSERT INTO resume (user_id, sex, education, dream, skills, health_features)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

// Привязываем параметры (6 параметров)
$stmt->bind_param("isssss", $userId, $sex, $education, $dream, $skills, $health);

// Выполняем запрос
if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Резюме успешно сохранено.';
} else {
    $response['message'] = 'Ошибка при сохранении резюме.';
}

// Закрываем соединение
$stmt->close();
$conn->close();

// Выводим результат
echo json_encode($response);
?>
