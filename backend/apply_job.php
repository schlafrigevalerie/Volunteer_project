<?php
// Включаем отображение ошибок для отладки
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include 'db.php'; // Подключаем базу данных

// Проверяем, есть ли пользователь в сессии
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Пользователь не авторизован']);
    exit;
}

$user_id = $_SESSION['user_id']; 

// Получаем данные из POST-запроса
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['job_id']) || !isset($data['resume_id'])) {
    echo json_encode(['error' => 'Недостаточно данных']);
    exit;
}

$job_id = $data['job_id'];
$resume_id = $data['resume_id'];

// Добавляем отклик в базу данных
$sql = "INSERT INTO responses (user_id, job_id, resume_id) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $user_id, $job_id, $resume_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Не удалось добавить отклик']);
}
?>
