<?php
// Подключаемся к базе данных
include('db.php');

// Запрашиваем все вопросы и ответы, где ответ не пустой
$sql = "SELECT question, answer FROM questions WHERE answer IS NOT NULL";
$result = $conn->query($sql);

// Проверяем, если данные найдены
$questions = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
}

// Закрываем соединение с БД
$conn->close();

// Отправляем данные в формате JSON
header('Content-Type: application/json');
echo json_encode($questions);
?>
