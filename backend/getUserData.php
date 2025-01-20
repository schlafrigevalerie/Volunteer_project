<?php
require 'vendor/autoload.php';
use \Firebase\JWT\JWT;

$secret_key = "YOUR_SECRET_KEY"; // Ваш секретный ключ

if (!isset($_GET['token'])) {
    echo json_encode(['status' => 'error', 'message' => 'Токен не найден']);
    exit;
}

$jwt = $_GET['token'];

try {
    // Декодируем токен
    $decoded = JWT::decode($jwt, $secret_key, ['HS256']);
    // Логируем результат декодирования
    error_log('Декодированный токен: ' . json_encode($decoded));

    $userId = $decoded->user_id;

    require 'db.php';

    // Извлекаем данные пользователя
    $sql = "SELECT first_name, last_name, middle_name, birth_date, phone_number, email FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode([
            'status' => 'success',
            'data' => $user
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Пользователь не найден'
        ]);
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    // Логируем ошибку декодирования
    error_log('Ошибка при декодировании токена: ' . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Неверный или истекший токен']);
}
?>
