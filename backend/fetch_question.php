<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Подключаем файл с настройками базы данных
include('db.php');

// Извлекаем вопросы с пустыми ответами
$query = "SELECT id, question FROM questions WHERE answer is NULL";
$result = $conn->query($query);

$questions = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
} else {
    // Нет вопросов с пустыми ответами
    $questions = [];
}

// Закрываем подключение
$conn->close();

// Отправляем вопросы в формате JSON
echo json_encode($questions);
?>
