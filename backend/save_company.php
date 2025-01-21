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
$name = $_POST['name'] ?? null;
$description = $_POST['description'] ?? null;
$industry = $_POST['industry'] ?? null;

// Переменные для ответа
$response = array('success' => false, 'message' => '');

// SQL-запрос для сохранения резюме
$sql = "INSERT INTO company (user_id, name, description, industry)
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

// Привязываем параметры (6 параметров)
$stmt->bind_param("isss", $userId, $name, $description, $industry);

// Выполняем запрос
if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Компания успешно сохранена.';
} else {
    $response['message'] = 'Ошибка при сохранении компании.';
}

// Закрываем соединение
$stmt->close();
$conn->close();

// Выводим результат
echo json_encode($response);
?>
