window.addEventListener('DOMContentLoaded', function() {
    // Проверяем, если данные о пользователе сохранены в localStorage
    const userName = localStorage.getItem('userName');
    const firstName = localStorage.getItem('firstName');
    const lastName = localStorage.getItem('lastName');
    const middleName = localStorage.getItem('middleName');
    const birthDate = localStorage.getItem('birthDate');
    const phoneNumber = localStorage.getItem('phoneNumber');
    const email = localStorage.getItem('email');

    if (userName && firstName && lastName && middleName && birthDate && phoneNumber && email) {
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

    // Открытие модального окна
    const editButton = document.getElementById('editButton');
    const modal = document.getElementById('editModal');
    const closeModal = document.getElementsByClassName('close')[0];

    editButton.addEventListener('click', function() {
        document.getElementById('first_name_input').value = firstName;
        document.getElementById('last_name_input').value = lastName;
        document.getElementById('middle_name_input').value = middleName;
        document.getElementById('birth_date_input').value = birthDate;
        document.getElementById('phone_number_input').value = phoneNumber;
        document.getElementById('email_input').value = email;
        modal.style.display = 'block';
    });

    closeModal.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    // Закрытие модального окна при клике вне его области
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Обработка отправки формы редактирования данных
    document.getElementById('editForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Предотвращаем стандартное поведение формы

        const updatedData = {
            first_name: document.getElementById('first_name_input').value,
            last_name: document.getElementById('last_name_input').value,
            middle_name: document.getElementById('middle_name_input').value,
            birth_date: document.getElementById('birth_date_input').value,
            phone_number: document.getElementById('phone_number_input').value,
            email: document.getElementById('email_input').value
        };

        // Отправка обновленных данных на сервер
        fetch('updateUser.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(updatedData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Данные успешно обновлены!');
                // Обновляем localStorage с новыми данными
                localStorage.setItem('firstName', updatedData.first_name);
                localStorage.setItem('lastName', updatedData.last_name);
                localStorage.setItem('middleName', updatedData.middle_name);
                localStorage.setItem('birthDate', updatedData.birth_date);
                localStorage.setItem('phoneNumber', updatedData.phone_number);
                localStorage.setItem('email', updatedData.email);
                modal.style.display = 'none'; // Закрываем модальное окно
            } else {
                alert('Ошибка при обновлении данных');
            }
        })
        .catch(error => console.error('Ошибка:', error));
    });
});
