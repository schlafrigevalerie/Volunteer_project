<?php
// Включаем отображение ошибок для отладки
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Подключаем файл с настройками подключения
require_once 'db.php';

// Запускаем сессию, чтобы получить user_id из сессии
session_start();

// Проверяем, что user_id есть в сессии
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Пользователь не авторизован']);
    exit;
}

$user_id = $_SESSION['user_id'];  // Получаем user_id из сессии
$job_id = $_POST['job_id']; // Или $_GET['job_id'] в зависимости от способа передачи
$company_id = $_POST['company_id'];  // Или передается в запросе

// Логируем полученные данные
error_log("Полученные данные: user_id = $user_id, job_id = $job_id, company_id = $company_id");

// Проверяем, существует ли резюме пользователя
$query = "SELECT id FROM resume WHERE user_id = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    // Логируем ошибку, если запрос не подготовился
    error_log("Ошибка в подготовке запроса для получения резюме: " . $conn->error);
    echo json_encode(['success' => false, 'message' => 'Ошибка в подготовке запроса для получения резюме']);
    exit;
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($resume_id);

// Если резюме не найдено
if (!$stmt->fetch()) {
    error_log("Резюме не найдено для user_id = $user_id");
    echo json_encode(['success' => false, 'message' => 'Резюме не найдено']);
    $stmt->close();
    $conn->close();
    exit;
}

$stmt->close();

// Логируем найденное резюме
error_log("Найдено резюме с id = $resume_id для user_id = $user_id");

// Вставляем запись в таблицу responses
$query = "INSERT INTO responses (user_id, job_id, company_id, resume_id, created_at) VALUES (?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($query);

if (!$stmt) {
    // Логируем ошибку, если запрос не подготовился
    error_log("Ошибка в подготовке запроса для вставки отклика: " . $conn->error);
    echo json_encode(['success' => false, 'message' => 'Ошибка в подготовке запроса для вставки отклика']);
    exit;
}

$stmt->bind_param("iiiii", $user_id, $job_id, $company_id, $resume_id);
$stmt->execute();

// Проверяем успешность выполнения
if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    error_log("Не удалось вставить отклик для user_id = $user_id, job_id = $job_id");
    echo json_encode(['success' => false, 'message' => 'Не удалось вставить отклик']);
}

$stmt->close();
$conn->close();
?>
