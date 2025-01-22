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
    echo json_encode(['is_applied' => false, 'message' => 'Пользователь не авторизован']);
    exit;
}

$user_id = $_SESSION['user_id'];  // Получаем user_id из сессии
$job_id = $_POST['job_id']; // Или $_GET['job_id'] в зависимости от способа передачи

// Логируем полученные данные
error_log("Полученные данные: user_id = $user_id, job_id = $job_id");

// Проверяем, есть ли отклик пользователя на эту вакансию
$query = "SELECT 1 FROM responses WHERE user_id = ? AND job_id = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    // Логируем ошибку, если запрос не подготовился
    error_log("Ошибка в подготовке запроса для проверки отклика: " . $conn->error);
    echo json_encode(['is_applied' => false, 'message' => 'Ошибка в подготовке запроса для проверки отклика']);
    exit;
}

$stmt->bind_param("ii", $user_id, $job_id);
$stmt->execute();
$stmt->store_result();

$isApplied = $stmt->num_rows > 0;

echo json_encode(['is_applied' => $isApplied]);

$stmt->close();
$conn->close();
?>
