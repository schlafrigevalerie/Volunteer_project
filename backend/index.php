<?php
// Подключение к базе данных
require 'db.php';

// Обработка POST-запроса
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение данных из POST-запроса
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $middleName = $_POST['middleName'];
    $birthDate = $_POST['birthDate'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $role = $_POST['role']; // Получаем роль из формы

    // Проверка на совпадение паролей
    if ($password !== $confirmPassword) {
        $response = [
            'status' => 'error',
            'message' => 'Пароли не совпадают.'
        ];
        echo json_encode($response);
        exit;
    }

    // Проверка на уникальность email
    $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);

    if (!$stmt) {
        error_log("Ошибка при подготовке запроса для проверки email: " . $conn->error);
        $response = [
            'status' => 'error',
            'message' => 'Ошибка при подготовке запроса для проверки email.'
        ];
        echo json_encode($response);
        exit;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Если email уже существует
        $response = [
            'status' => 'error',
            'message' => 'Пользователь с таким email уже существует.'
        ];
    } else {
        // Хеширование пароля
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Определение role_id на основе выбранной роли
        $roleId = ($role === 'job_seeker') ? 1 : (($role === 'admin') ? 3 : 2);


        // SQL-запрос для вставки данных в таблицу users
        $sql = "INSERT INTO users (first_name, last_name, middle_name, birth_date, phone_number, email, password, role_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        // Подготовка и выполнение запроса
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssssssi", $firstName, $lastName, $middleName, $birthDate, $phone, $email, $hashedPassword, $roleId);
            if ($stmt->execute()) {
                // Успешная регистрация
                $response = [
                    'status' => 'success',
                    'message' => 'Пользователь успешно зарегистрирован!'
                ];
                // Перенаправление на страницу авторизации
                header('Location: login.html');
                exit;
            } else {
                error_log("Ошибка при выполнении запроса на регистрацию: " . $stmt->error);
                $response = [
                    'status' => 'error',
                    'message' => 'Ошибка при регистрации пользователя: ' . $stmt->error
                ];
            }
            $stmt->close();
        } else {
            error_log("Ошибка при подготовке запроса на регистрацию: " . $conn->error);
            $response = [
                'status' => 'error',
                'message' => 'Ошибка при подготовке запроса на регистрацию: ' . $conn->error
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
