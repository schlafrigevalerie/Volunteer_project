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

// Получаем данные из формы
$name = $_POST['name'] ?? null;
$salary = $_POST['salary'] ?? null;
$requirements = $_POST['requirements'] ?? null;
$conditions = $_POST['conditions'] ?? null;

// Переменные для ответа
$response = array('success' => false, 'message' => '');

// Шаг 1: Найдем компанию, связанная с этим пользователем
$sql = "SELECT id FROM company WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Проверяем, есть ли компания для этого пользователя
if ($result->num_rows > 0) {
    // Получаем ID компании
    $company = $result->fetch_assoc();
    $companyId = $company['id'];

    // Шаг 2: Вставляем вакансию в таблицу jobs
    $sql = "INSERT INTO jobs (company_id, user_id, name, salary, requirements, conditions)
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissss", $companyId, $userId, $name, $salary, $requirements, $conditions);

    // Выполняем запрос
    if ($stmt->execute()) {
        // Успешно сохраняем вакансию, редиректим
        header("Location: ../frontend/vacancy-emp.html");  // Перенаправляем на страницу вакансий
        exit();  // Останавливаем выполнение скрипта
    } else {
        $response['message'] = 'Ошибка при сохранении вакансии.';
    }

    // Закрываем соединение
    $stmt->close();
} else {
    $response['message'] = 'Компания не найдена для этого пользователя.';
}

// Закрываем соединение с базой данных
$conn->close();

// Выводим результат
echo json_encode($response);
?>
