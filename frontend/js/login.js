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
            // Сохраняем данные о пользователе в localStorage
            localStorage.setItem('userName', data.user_name);
            localStorage.setItem('firstName', data.first_name);
            localStorage.setItem('lastName', data.last_name);
            localStorage.setItem('middleName', data.middle_name);
            localStorage.setItem('birthDate', data.birth_date);
            localStorage.setItem('phoneNumber', data.phone_number);
            localStorage.setItem('email', data.email);

            alert(data.message); // Успешная авторизация
            window.location.href = 'home-avt.html'; // Перенаправление на страницу после успешной авторизации
        } else {
            alert('Ошибка: ' + data.message); // Обработка ошибок
        }
    })
    .catch(error => console.error('Ошибка:', error));
});
