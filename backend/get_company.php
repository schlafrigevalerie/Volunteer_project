<?php
require 'db.php'; 

session_start();

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Пользователь не авторизован']);
    exit;
}

$userId = $_SESSION['user_id']; 

// Запрос на получение информации о компании
$sql = "SELECT * FROM company WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$company = $result->fetch_assoc();

if ($company) {
    echo json_encode(['success' => true, 'company' => $company]);
} else {
    echo json_encode(['success' => false, 'message' => 'Компания не найдена']);
}

$stmt->close();
$conn->close();
?>
