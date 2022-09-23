## TODO

Для розгортання проекту після завантаження з репозиторію Вам доведеться виконати наступні кроки:

- Виконати composer install
- cp .env.example .env && php artisan key:generate 
- В новоствореному файлі .env окрім даних для підключення бд потрібно вказати Ваш токен з ресурсу FreelanceHunt d TOKEN=
- Виконати міграції командою php artisan migrate
- Для заповнення бази новими заявками потрібно виконати php artisan rojects:parse
