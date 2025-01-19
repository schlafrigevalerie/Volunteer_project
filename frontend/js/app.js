document.getElementById('registrationForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Предотвращаем стандартное поведение формы

    const formData = new FormData(this); // Получаем данные формы

    fetch('../backend/index.php', { // Отправляем данные на сервер
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message); // Успешная регистрация
        } else {
            alert('Ошибка: ' + data.message); // Обработка ошибок
        }
    })
    .catch(error => console.error('Ошибка:', error));
});

// Обработчик для формы авторизации
document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Предотвращаем стандартное поведение формы

    const formData = new FormData(this); // Получаем данные формы

    fetch('../backend/login.php', { // Отправляем данные на сервер
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message); // Успешная авторизация
            window.location.href = 'home-avt.html'; // Перенаправление на страницу после успешной авторизации
        } else {
            alert('Ошибка: ' + data.message); // Обработка ошибок
        }
    })
    .catch(error => console.error('Ошибка:', error));
});
