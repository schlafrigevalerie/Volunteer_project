<?php
// Подключаем файл с настройками базы данных
require 'db.php'; 

// Стартуем сессию, чтобы получить доступ к user_id
session_start();

// Проверка, что пользователь авторизован
if (!isset($_SESSION['user_id'])) {
    $response = [
        'success' => false,
        'message' => 'Пользователь не авторизован.'
    ];
    echo json_encode($response);
    exit;
}

// Получаем ID пользователя из сессии
$userId = $_SESSION['user_id']; 

// Запрос на получение резюме пользователя
$sql = "SELECT sex, education, dream, skills, health_features, photo FROM resume WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->store_result();

// Если резюме найдено, получаем данные
if ($stmt->num_rows > 0) {
    $stmt->bind_result($sex, $education, $dream, $skills, $health_features, $photo);
    $stmt->fetch();
    
    $resume = [
        'sex' => $sex,
        'education' => $education,
        'dream' => $dream,
        'skills' => $skills,
        'health_features' => $health_features,
        'photo' => $photo
    ];
    
    $response = [
        'success' => true,
        'resume' => $resume
    ];
} else {
    // Если резюме не найдено
    $response = [
        'success' => false,
        'message' => 'Резюме не найдено.'
    ];
}

$stmt->close();
$conn->close();

// Выводим результат
echo json_encode($response);
?>
