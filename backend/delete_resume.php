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

// Запрос на удаление резюме
$sql = "DELETE FROM resume WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $response = [
        'success' => true,
        'message' => 'Резюме успешно удалено.'
    ];
} else {
    $response = [
        'success' => false,
        'message' => 'Ошибка при удалении резюме.'
    ];
}

$stmt->close();
$conn->close();

// Выводим результат
echo json_encode($response);
?>
