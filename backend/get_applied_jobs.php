<?php
session_start();
include 'db.php'; // Подключаем базу данных

// Проверяем, есть ли пользователь в сессии
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Пользователь не авторизован']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Получаем список вакансий, на которые пользователь откликнулся
$sql = "SELECT jobs.id, jobs.name, jobs.salary, jobs.requirements, jobs.conditions
        FROM jobs
        JOIN responses ON jobs.id = responses.job_id
        WHERE responses.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Формируем массив вакансий
$applied_jobs = [];
while ($job = $result->fetch_assoc()) {
    $applied_jobs[] = $job;
}

// Отправляем результат в формате JSON
echo json_encode($applied_jobs);
?>
