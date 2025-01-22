<?php
session_start();
include 'db.php'; // Подключаем базу данных

// Проверяем, есть ли пользователь в сессии
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Пользователь не авторизован']);
    exit;
}

$user_id = $_SESSION['user_id']; 

// Получаем компанию, к которой принадлежит текущий пользователь
$sql = "SELECT id FROM company WHERE user_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die('Ошибка подготовки запроса: ' . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$company = $result->fetch_assoc();

if (!$company) {
    echo json_encode(['error' => 'Компания не найдена']);
    exit;
}

$company_id = $company['id'];

// Получаем все отклики на вакансии этой компании
$sql = "
    SELECT r.*, u.first_name, u.last_name, u.phone_number, u.email, u.birth_date, res.skills, res.photo, res.sex, res.education, res.dream, res.health_features 
    FROM responses r
    JOIN users u ON r.user_id = u.id
    JOIN resume res ON u.id = res.user_id
    WHERE r.job_id IN (SELECT id FROM jobs WHERE company_id = ?)
";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die('Ошибка подготовки запроса: ' . $conn->error);
}
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $responses = [];
    while ($row = $result->fetch_assoc()) {
        $responses[] = $row;
    }
    echo json_encode($responses);
} else {
    echo json_encode(['error' => 'Нет откликов для этой компании']);
}
?>
