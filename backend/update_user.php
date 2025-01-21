<?php
// Подключение к базе данных
require 'db.php';

// Включаем отображение ошибок
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Стартуем сессию, чтобы получить информацию о текущем пользователе
session_start();

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    $response = [
        'status' => 'error',
        'message' => 'Вы не авторизованы.'
    ];
    echo json_encode($response);
    exit;
}

$user_id = $_SESSION['user_id']; // Получаем ID текущего пользователя из сессии

// Получаем данные из POST-запроса
$first_name = isset($_POST['first_name']) ? $_POST['first_name'] : null;
$last_name = isset($_POST['last_name']) ? $_POST['last_name'] : null;
$middle_name = isset($_POST['middle_name']) ? $_POST['middle_name'] : null;
$phone_number = isset($_POST['phone_number']) ? $_POST['phone_number'] : null;
$email = isset($_POST['email']) ? $_POST['email'] : null;

// Подготовка SQL-запроса для обновления данных
$update_fields = [];
$update_values = [];

// Проверяем, какие поля были изменены, и добавляем их в массив обновлений
if ($first_name !== null) {
    $update_fields[] = "first_name = ?";
    $update_values[] = $first_name;
}

if ($last_name !== null) {
    $update_fields[] = "last_name = ?";
    $update_values[] = $last_name;
}

if ($middle_name !== null) {
    $update_fields[] = "middle_name = ?";
    $update_values[] = $middle_name;
}

if ($phone_number !== null) {
    $update_fields[] = "phone_number = ?";
    $update_values[] = $phone_number;
}

if ($email !== null) {
    $update_fields[] = "email = ?";
    $update_values[] = $email;
}

// Если нет изменений, выходим
if (empty($update_fields)) {
    $response = [
        'status' => 'error',
        'message' => 'Нет данных для обновления.'
    ];
    echo json_encode($response);
    exit;
}

// Добавляем условие для обновления по ID пользователя
$update_values[] = $user_id;

// Создаём SQL-запрос для обновления
$sql = "UPDATE users SET " . implode(", ", $update_fields) . " WHERE id = ?";

// Подготовка запроса
$stmt = $conn->prepare($sql);

// Проверка на ошибки при подготовке запроса
if (!$stmt) {
    error_log("Ошибка при подготовке запроса: " . $conn->error);
    $response = [
        'status' => 'error',
        'message' => 'Ошибка при подготовке запроса.'
    ];
    echo json_encode($response);
    exit;
}

// Привязываем параметры
$stmt->bind_param(str_repeat("s", count($update_values) - 1) . "i", ...$update_values);

// Выполняем запрос
if ($stmt->execute()) {
    $response = [
        'status' => 'success',
        'message' => 'Данные успешно обновлены.'
    ];
} else {
    $response = [
        'status' => 'error',
        'message' => 'Ошибка при обновлении данных.'
    ];
}

// Закрываем соединение
$stmt->close();
$conn->close();

// Отправляем ответ
header('Content-Type: application/json');
echo json_encode($response);
?>
