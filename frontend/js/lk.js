window.addEventListener('DOMContentLoaded', function() {
    // Проверяем, если данные о пользователе сохранены в localStorage
    const firstName = localStorage.getItem('firstName');
    const lastName = localStorage.getItem('lastName');
    const middleName = localStorage.getItem('middleName');
    const birthDate = localStorage.getItem('birthDate');
    const phoneNumber = localStorage.getItem('phoneNumber');
    const email = localStorage.getItem('email');

    if (firstName && lastName && middleName && birthDate && phoneNumber && email) {
        // Отображаем данные на странице
        document.getElementById('first_name').textContent = firstName;
        document.getElementById('last_name').textContent = lastName;
        document.getElementById('middle_name').textContent = middleName;
        document.getElementById('birth_date').textContent = birthDate;
        document.getElementById('phone_number').textContent = phoneNumber;
        document.getElementById('email').textContent = email;
    } else {
        alert("Ошибка: пользователь не авторизован.");
        window.location.href = "login.html"; // Перенаправляем на страницу логина
    }
});
