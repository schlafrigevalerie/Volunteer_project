<?php
require 'db.php';  // Подключаем файл с базой данных

// SQL-запрос для получения всех вакансий
$sql = "SELECT * FROM jobs";

// Выполняем запрос
$result = $conn->query($sql);

// Проверка, если есть результаты
if ($result->num_rows > 0) {
    // Сохраняем вакансии в массив
    $jobs = [];
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }

    // Отправляем результат в формате JSON
    echo json_encode($jobs);
} else {
    echo json_encode([]);
}

// Закрытие подключения
$conn->close();
?>
