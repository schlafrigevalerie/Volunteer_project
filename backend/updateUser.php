<?php
// Подключаем необходимые файлы
require 'db.php';

// Получаем данные из запроса
$data = json_decode(file_get_contents('php://input'), true);

// Логируем полученные данные для отладки
error_log('Полученные данные: ' . print_r($data, true));

// Получаем токен из заголовка
$headers = getallheaders();
$token = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : null;

if (!$token) {
    error_log('Токен не передан');
    echo json_encode(['status' => 'error', 'message' => 'Токен не передан']);
    exit;
}

// Проверка токена и извлечение user_id (предполагаем, что это JWT или просто идентификатор)
$user_id = verifyToken($token); // Функция для верификации токена (вам нужно её реализовать)

// Если токен невалидный
if (!$user_id) {
    error_log('Невалидный токен');
    echo json_encode(['status' => 'error', 'message' => 'Невалидный токен']);
    exit;
}

// Массив для полей и параметров запроса
$fields = [];
$params = [];

// Формируем поля и параметры для обновления
if (isset($data['first_name'])) {
    $fields[] = "first_name = ?";
    $params[] = $data['first_name'];
}

if (isset($data['last_name'])) {
    $fields[] = "last_name = ?";
    $params[] = $data['last_name'];
}

if (isset($data['middle_name'])) {
    $fields[] = "middle_name = ?";
    $params[] = $data['middle_name'];
}

if (isset($data['birth_date'])) {
    $fields[] = "birth_date = ?";
    $params[] = $data['birth_date'];
}

if (isset($data['email'])) {
    $fields[] = "email = ?";
    $params[] = $data['email'];
}

if (isset($data['phone_number'])) {
    $fields[] = "phone_number = ?";
    $params[] = $data['phone_number'];
}

// Проверка, что есть данные для обновления
if (count($fields) == 0) {
    error_log('Нет данных для обновления');
    echo json_encode(['status' => 'error', 'message' => 'Нет данных для обновления']);
    exit;
}

// Строим SQL запрос
$sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE id = ?";
$params[] = $user_id; // Добавляем ID пользователя в параметры запроса

// Готовим и выполняем запрос
$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat("s", count($params) - 1) . "i", ...$params);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    error_log('Ошибка при выполнении запроса: ' . $stmt->error);
    echo json_encode(['status' => 'error', 'message' => 'Ошибка при обновлении данных']);
}

$stmt->close();
$conn->close();
?>
