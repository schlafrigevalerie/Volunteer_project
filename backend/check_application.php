<?php
session_start();
include 'db.php'; // Подключение к базе данных

// Получаем ID текущего пользователя из сессии
$user_id = $_SESSION['user_id']; 

// Получаем ID вакансии
$job_id = $_GET['job_id'];

// Проверяем, есть ли отклик этого пользователя на указанную вакансию
$sql = "SELECT * FROM responses WHERE user_id = ? AND job_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $job_id);
$stmt->execute();
$result = $stmt->get_result();
$response = $result->fetch_assoc();

if ($response) {
    // Если отклик есть
    echo json_encode(['hasResponded' => true, 'resume_id' => $response['resume_id']]);
} else {
    // Если отклика нет, получаем ID резюме пользователя
    $sql = "SELECT id FROM resume WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $resume = $result->fetch_assoc();
    
    echo json_encode(['hasResponded' => false, 'resume_id' => $resume['id']]);
}
?>
