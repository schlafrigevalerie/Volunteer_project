<?php
session_start();
require 'db.php'; // подключаем файл с подключением к базе данных

// Проверяем, что пользователь залогинен
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Пользователь не авторизован.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['question']) && !empty($_POST['question'])) {
        $question = trim($_POST['question']);
        $user_id = $_SESSION['user_id']; // получаем user_id из сессии

        // Подготовка SQL-запроса для вставки нового вопроса
        $stmt = $conn->prepare("INSERT INTO questions (user_id, question, answer) VALUES (?, ?, NULL)");
        
        if ($stmt === false) {
            echo json_encode(['success' => false, 'message' => 'Ошибка подготовки запроса: ' . $conn->error]);
            exit;
        }

        // Связываем параметры с подготовленным запросом
        $stmt->bind_param("is", $user_id, $question);

        // Выполняем запрос
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Не удалось сохранить вопрос.']);
        }

        // Закрываем подготовленный запрос
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Вопрос не может быть пустым.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Неверный метод запроса.']);
}
?>
