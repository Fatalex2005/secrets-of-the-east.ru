## Установка проекта из репозитория
1. Склонируйте репозиторий
```shell
cd domains
git clone https://github.com/Fatalex2005/secrets-of-the-east.ru
```
2. Перейдите в папку с проектом и установите composer-зависимости
```shell
cd secrets-of-the-east.ru
composer install
```
3. Скопируйте файл .env.example в .env
```shell
copy .env.example .env
```
4. Сгенерируйте ключ шифрования
```shell
php artisan key:generate
```
5. Измените файл конфигурации .env (пример для БД MySQL)
```php
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=secrets-of-the-east.ru
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
```
