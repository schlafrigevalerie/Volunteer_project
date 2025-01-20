// updateUser.php
<?php
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

// Получаем данные из запроса
$first_name = $data['first_name'];
$last_name = $data['last_name'];
$email = $data['email'];
$phone_number = $data['phone_number'];
$user_id = $_SESSION['user_id']; // Предполагается, что у вас есть идентификатор пользователя в сессии

// Обновляем данные в базе
$sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, phone_number = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $first_name, $last_name, $email, $phone_number, $user_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}

$stmt->close();
$conn->close();
?>
