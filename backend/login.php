<?php
// Подключение к базе данных
require 'db.php';

// Обработка POST-запроса
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение данных из POST-запроса
    $identifier = $_POST['identifier'];
    $password = $_POST['password'];

    // Проверка, является ли идентификатор email или номером телефона
    if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
        // Это email
        $sql = "SELECT * FROM users WHERE email = ?";
    } elseif (preg_match('/^\+?[0-9]{10,15}$/', $identifier)) {
        // Это номер телефона (можно настроить регулярное выражение под ваши требования)
        $sql = "SELECT * FROM users WHERE phone = ?";
    } else {
        // Неверный формат
        $response = [
            'status' => 'error',
            'message' => 'Введите корректный email или номер телефона.'
        ];
        echo json_encode($response);
        exit;
    }

    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        error_log("Ошибка при подготовке запроса для проверки идентификатора: " . $conn->error);
        $response = [
            'status' => 'error',
            'message' => 'Ошибка при подготовке запроса для проверки идентификатора.'
        ];
        echo json_encode($response);
        exit;
    }

    $stmt->bind_param("s", $identifier);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // Если пользователь не найден
        $response = [
            'status' => 'error',
            'message' => 'Неверный email или номер телефона.'
        ];
    } else {
        $user = $result->fetch_assoc();
        // Проверка пароля
        if (password_verify($password, $user['password'])) {
            // Успешная авторизация
            $response = [
                'status' => 'success',
                'message' => 'Вы успешно вошли в систему!'
            ];
        } else {
            // Неверный пароль
            $response = [
                'status' => 'error',
                'message' => 'Неверный email или номер телефона.'
            ];
        }
    }

    // Закрытие соединения
    $conn->close();

    // Возвращаем ответ в формате JSON
    header('Content-Type: application/json');
   

