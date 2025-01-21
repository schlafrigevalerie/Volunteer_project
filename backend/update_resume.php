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

// Получаем данные из POST-запроса
$field = $_POST['field'] ?? null;
$value = $_POST['value'] ?? null;

if ($field && $value) {
    // Обновляем конкретное поле в резюме
    $sql = "UPDATE resume SET $field = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $value, $userId);

    if ($stmt->execute()) {
        $response = [
            'success' => true,
            'message' => 'Данные успешно обновлены.'
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Ошибка при обновлении данных.'
        ];
    }
    $stmt->close();
} else {
    $response = [
        'success' => false,
        'message' => 'Недостаточно данных для обновления.'
    ];
}

$conn->close();
echo json_encode($response);
?>
