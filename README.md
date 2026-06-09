# Установка проекта

## 1. Клонирование репозитория

```bash
git clone https://github.com/savckovalexei/catalog.git catalog
cd catalog
```
# Установка зависимостей

```bash
composer install
```
#Настройка окружения

```bash
cp .env.example .env
php artisan key:generate
```
Отредактируйте .env файл, укажите параметры подключения к БД.

# Запуск миграций и сидеров

```bash
php artisan migrate:fresh --seed
```

#  Запуск сервера

```bash
php artisan serve
```

Откройте в браузере: http://localhost:8000
