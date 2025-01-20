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
        $sql = "SELECT * FROM users WHERE email = ?";
    } elseif (preg_match('/^\+?[0-9]{10,15}$/', $identifier)) {
        $sql = "SELECT * FROM users WHERE phone_number = ?";
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
            // Мы можем комбинировать имя, фамилию и отчество для полного имени
            $fullName = $user['first_name'] . ' ' . $user['last_name'];
            $response = [
                'status' => 'success',
                'message' => 'Вы успешно вошли в систему!',
                'user_name' => $user['first_name'] . ' ' . $user['last_name'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'middle_name' => $user['middle_name'],
                'birth_date' => $user['birth_date'],
                'phone_number' => $user['phone_number'],
                'email' => $user['email']
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
    echo json_encode($response);
    exit;
}
?>
