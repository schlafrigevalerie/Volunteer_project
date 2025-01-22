<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Подключаем файл с настройками базы данных
include('db.php');

// Проверяем, что запрос является POST и данные получены
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['answer'])) {
    $id = (int) $_POST['id'];
    $answer = trim($_POST['answer']);

    // Обновляем ответ в базе данных
    $query = "UPDATE questions SET answer = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $answer, $id);
    
    // Выполняем запрос
    if ($stmt->execute()) {
        echo "Ответ успешно обновлён!";
    } else {
        echo "Ошибка при обновлении ответа.";
    }
} else {
    echo "Некорректные данные.";
}

// Закрываем подключение
$conn->close();
?>
