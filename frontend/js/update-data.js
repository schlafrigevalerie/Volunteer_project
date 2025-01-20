window.addEventListener('DOMContentLoaded', function() {
    // Загружаем данные из localStorage
    const userName = localStorage.getItem('userName');
    const firstName = localStorage.getItem('firstName');
    const lastName = localStorage.getItem('lastName');
    const middleName = localStorage.getItem('middleName');
    const birthDate = localStorage.getItem('birthDate');
    const phoneNumber = localStorage.getItem('phoneNumber');
    const email = localStorage.getItem('email');
    const userToken = localStorage.getItem('userToken');  // Токен пользователя

    if (userToken && firstName && lastName && middleName && birthDate && phoneNumber && email) {
        // Отображаем данные на странице
        document.getElementById('first_name_input').value = firstName;
        document.getElementById('last_name_input').value = lastName;
        document.getElementById('middle_name_input').value = middleName;
        document.getElementById('birth_date_input').value = birthDate;
        document.getElementById('phone_number_input').value = phoneNumber;
        document.getElementById('email_input').value = email;
    } else {
        alert("Ошибка: пользователь не авторизован.");
        window.location.href = "login.html"; // Перенаправляем на страницу логина
    }

    // Обработка отправки формы
    document.getElementById('editForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Предотвращаем стандартное поведение формы

        const updatedData = {}; // Это объявление переменной!

        // Собираем только изменённые данные
        if (document.getElementById('first_name_input').value) {
            updatedData.first_name = document.getElementById('first_name_input').value;
        }
        if (document.getElementById('last_name_input').value) {
            updatedData.last_name = document.getElementById('last_name_input').value;
        }
        if (document.getElementById('middle_name_input').value) {
            updatedData.middle_name = document.getElementById('middle_name_input').value;
        }
        if (document.getElementById('birth_date_input').value) {
            updatedData.birth_date = document.getElementById('birth_date_input').value;
        }
        if (document.getElementById('phone_number_input').value) {
            updatedData.phone_number = document.getElementById('phone_number_input').value;
        }
        if (document.getElementById('email_input').value) {
            updatedData.email = document.getElementById('email_input').value;
        }

        console.log("Отправляемые данные:", updatedData); // Логируем данные

        // Отправка обновленных данных на сервер
        fetch('../backend/updateUser.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${userToken}` // Отправляем токен в заголовке
            },
            body: JSON.stringify(updatedData)
        })
        .then(response => response.json())
        .then(data => {
            console.log('Ответ от сервера:', data); // Логируем ответ от сервера

            if (data.status === 'success') {
                alert('Данные успешно обновлены!');
                // Обновляем localStorage с новыми данными
                for (let key in updatedData) {
                    if (updatedData.hasOwnProperty(key)) {
                        localStorage.setItem(key, updatedData[key]);
                    }
                }
            } else {
                alert('Ошибка при обновлении данных');
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
            alert('Произошла ошибка при отправке данных');
        });
    });
});
