document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Предотвращаем стандартное поведение формы

    const formData = new FormData(this); // Получаем данные формы

    fetch('../backend/login.php', { // Отправляем данные на сервер
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log(data); // Логируем ответ с сервера для отладки

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

            // Перенаправление в зависимости от роли
            if (data.role === 'job_seeker') {
                window.location.href = 'home-avt.html'; // Для соискателя
            } else if (data.role === 'employer') {
                window.location.href = 'home_avt_employer.html'; // Для работодателя
            } else {
                window.location.href = 'home.html'; // На случай, если роль неизвестна
            }
        } else {
            alert('Ошибка: ' + data.message); // Обработка ошибок
        }
    })
    .catch(error => console.error('Ошибка:', error));
});
