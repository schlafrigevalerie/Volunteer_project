<?php
require 'db.php';

session_start();

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Пользователь не авторизован']);
    exit;
}

$userId = $_SESSION['user_id'];

// Удаление компании
$sql = "DELETE FROM company WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true, 'message' => 'Компания успешно удалена']);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка при удалении компании']);
}

$stmt->close();
$conn->close();
?>
