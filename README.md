# CookAI — Интеллектуальная кулинарная платформа

## Описание проекта

CookAI — полнофункциональное веб-приложение для генерации рецептов, сканирования калорийности блюд, создания планов питания и активного взаимодействия в кулинарном сообществе с помощью искусственного интеллекта.

### Ключевые возможности

**AI-инструменты:**
- 🤖 **AI Генератор рецептов** — создание рецептов по ограничениям (диета, время, продукты)
- 📷 **Сканер калорий** — определение ингредиентов и КБЖУ по фото
- 💬 **AI Советник** — чат-ассистент для советов по готовке
- 🎲 **Режим Вдохновение** — оригинальные рецепты с AI-фото
- 🧊 **Подбор по холодильнику** — рецепты из доступных продуктов

**Личное пространство:**
- 📋 План питания на неделю
- 📚 Кулинарные книги (пользовательские сборки)
- ❤️ Избранное и история
- 👤 Профиль и статистика

**Социальный функционал:**
- 👥 Сообщества по интересам
- 🏆 Рейтинги пользователей
- 🎯 Еженедельные челленджи
- 💌 Друзья, личные сообщения, комментарии

---

## Требования и установка

### Системные требования

- **PHP:** 8.0 или выше
- **MySQL:** 5.7 или выше (с поддержкой InnoDB)
- **Apache/Nginx:** с поддержкой `.htaccess` (для Apache)
- **cURL:** для работы с API Yandex AI Studio
- **GD Library:** для обработки изображений (опционально)

### Установка на shared-хостинг

#### Шаг 1: Загрузка файлов

```bash
# Скопируйте все файлы проекта в папку public_html
cd public_html
# Распакуйте архив с проектом
unzip CookAI.zip
cd CookAI
```

#### Шаг 2: Настройка БД

1. Создайте новую БД MySQL:
   ```sql
   CREATE DATABASE cookai_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   CREATE USER 'cookai_user'@'localhost' IDENTIFIED BY 'ваш_пароль';
   GRANT ALL PRIVILEGES ON cookai_db.* TO 'cookai_user'@'localhost';
   FLUSH PRIVILEGES;
   ```

2. Импортируйте схему БД:
   ```bash
   mysql -u cookai_user -p cookai_db < database/schema.sql
   ```

#### Шаг 3: Конфигурация

1. Скопируйте `.env.example` в `.env`:
   ```bash
   cp config/.env.example config/.env
   ```

2. Отредактируйте `config/.env` с вашими данными:
   ```ini
   # Database
   DB_HOST=localhost
   DB_NAME=cookai_db
   DB_USER=cookai_user
   DB_PASSWORD=ваш_пароль
   
   # Yandex AI API
   YANDEX_API_KEY=ваш_API_ключ
   YANDEX_FOLDER_ID=ваш_folder_id
   
   # App Settings
   APP_URL=https://вашдомен.ru/CookAI
   APP_ENV=production
   ```

#### Шаг 4: Разрешения на папки

```bash
chmod 755 app/
chmod 755 public/
chmod 755 storage/uploads/
chmod 755 storage/cache/
chmod 644 public/.htaccess
```

#### Шаг 5: Проверка установки

Откройте в браузере:
```
https://вашдомен.ru/CookAI/public/
```

Должна загрузиться главная страница с дизайном CookAI.

---

## Получение API-ключей Yandex AI Studio

### Шаг 1: Регистрация

1. Перейдите на [Yandex Cloud Console](https://console.cloud.yandex.ru/)
2. Создайте аккаунт или войдите
3. Создайте новый проект

### Шаг 2: Включение сервисов

1. В разделе **Каталоги** активируйте:
   - Yandex GPT (для генерации текстов)
   - Yandex Image Generation (для создания фото)
   - Yandex Computer Vision (для сканирования калорий)

2. Создайте сервисный аккаунт:
   - Перейдите в **Service Accounts**
   - Создайте новый сервисный аккаунт
   - Скопируйте **ID папки** (Folder ID)

### Шаг 3: Создание API-ключа

1. Для сервисного аккаунта создайте **API Key**
2. Скопируйте ключ и сохраните в `.env`:
   ```ini
   YANDEX_API_KEY=ваш_сгенерированный_ключ
   YANDEX_FOLDER_ID=b1g****
   ```

---

## Структура проекта

```
CookAI/
├── app/
│   ├── controllers/          # Контроллеры приложения
│   │   ├── HomeController.php
│   │   ├── AuthController.php
│   │   ├── RecipeController.php
│   │   ├── AIController.php
│   │   ├── SocialController.php
│   │   └── AdminController.php
│   ├── models/               # Модели БД
│   │   ├── User.php
│   │   ├── Recipe.php
│   │   ├── Community.php
│   │   ├── Challenge.php
│   │   └── Message.php
│   ├── views/                # HTML шаблоны
│   │   ├── layouts/
│   │   ├── home/
│   │   ├── auth/
│   │   ├── recipes/
│   │   ├── ai/
│   │   ├── social/
│   │   └── admin/
│   ├── services/             # Бизнес-логика
│   │   ├── AIService.php     # Работа с Yandex AI
│   │   ├── RecipeService.php
│   │   └── SocialService.php
│   └── middleware/           # Middleware (аутентификация, логирование)
│
├── config/                   # Конфигурация
│   ├── database.php
│   ├── yandex_ai.php
│   ├── .env.example
│   └── .env
│
├── database/                 # SQL-скрипты
│   ├── schema.sql            # Структура БД
│   └── seeds.sql             # Тестовые данные
│
├── public/                   # Веб-корень
│   ├── index.php             # Точка входа
│   ├── .htaccess             # Переписание URL
│   ├── css/
│   │   ├── style.css         # Главные стили (CookAI дизайн)
│   │   ├── responsive.css
│   │   └── admin.css
│   ├── js/
│   │   ├── app.js            # Основной JS
│   │   ├── ai-tools.js
│   │   ├── social.js
│   │   └── admin.js
│   ├── images/               # Изображения и иконки
│   └── uploads/              # Загруженные файлы пользователей
│
├── storage/
│   ├── uploads/              # Пользоват��льские загрузки
│   ├── cache/                # Кэш AI результатов
│   └── logs/                 # Логи ошибок
│
└── docs/
    ├── API.md                # Документация API
    ├── DEVELOPMENT.md        # Для разработчиков
    └── TROUBLESHOOTING.md    # Решение проблем
```

---

## Первый запуск

### Создание тестовой учетной записи

```bash
# Через админ-панель (URL: /CookAI/public/admin)
# Логин: admin
# Пароль: admin123 (измените после первого входа!)
```

Или через БД:
```sql
INSERT INTO users (username, email, password_hash, role, created_at) 
VALUES ('admin', 'admin@example.com', '$2y$10$...хеш_пароля...', 'admin', NOW());
```

### Загрузка тестовых данных

```bash
mysql -u cookai_user -p cookai_db < database/seeds.sql
```

---

## Основные функции и примеры использования

### 1. Генерация рецепта через AI

```javascript
// Frontend: app/views/ai/generator.php
const generateRecipe = async (formData) => {
  const response = await fetch('/CookAI/public/api/ai/generate-recipe', {
    method: 'POST',
    body: JSON.stringify(formData)
  });
  const recipe = await response.json();
  displayRecipe(recipe);
};
```

### 2. Сканирование калорий по фото

```javascript
const scanCalories = async (imageFile) => {
  const formData = new FormData();
  formData.append('image', imageFile);
  
  const response = await fetch('/CookAI/public/api/ai/scan-calories', {
    method: 'POST',
    body: formData
  });
  
  const result = await response.json();
  showNutritionInfo(result); // КБЖУ, калории
};
```

### 3. Управление сообществами

```javascript
// Создание сообщества
const createCommunity = async (name, description) => {
  await fetch('/CookAI/public/api/social/communities', {
    method: 'POST',
    body: JSON.stringify({ name, description })
  });
};
```

---

## Администрирование

### Вход в админ-панель

```
https://вашдомен.ru/CookAI/public/admin/
```

**Функции админ-панели:**
- 👥 Управление пользователями (блокировка, удаление)
- 📚 Модерация рецептов
- 👫 Управление сообществами
- 🎯 Создание и редактирование челленджей
- 📊 Статистика и логи
- ⚙️ Настройки приложения и API

---

## Безопасность

### Важные меры безопасности

1. **Защита API-ключей:**
   - Никогда не коммитьте `.env` в Git
   - Используйте `.env.example` как шаблон
   - Ограничивайте доступ к `config/`

2. **Хеширование паролей:**
   - PHP bcrypt с `password_hash()`
   - Никогда не сравнивайте пароли напрямую

3. **CSRF-защита:**
   - Все формы содержат CSRF-токены
   - Проверка `X-CSRF-Token` в AJAX

4. **Валидация входных данных:**
   - Белые списки для категорий
   - Санитизация HTML
   - Проверка типов в PDO

5. **HTTPS обязателен:**
   - Включите SSL на хостинге
   - Установите редирект с HTTP на HTTPS в `.htaccess`

---

## Решение проблем

### "Ошибка подключения к БД"

```bash
# Проверьте учетные данные в config/.env
# Убедитесь, что MySQL запущен
mysql -u cookai_user -p cookai_db -e "SELECT 1;"
```

### "Ошибка Yandex API"

```bash
# Проверьте API-ключ и Folder ID
# Убедитесь, что включены нужные сервисы в Yandex Cloud Console
# Посмотрите логи: cat storage/logs/ai_errors.log
```

### "Permission denied на uploads"

```bash
# Установите правильные права доступа
chmod -R 755 storage/uploads/
chown -R www-data:www-data storage/
```

---

## Поддержка и документация

- 📖 **API Documentation:** `/docs/API.md`
- 🛠️ **Development Guide:** `/docs/DEVELOPMENT.md`
- ❓ **Troubleshooting:** `/docs/TROUBLESHOOTING.md`

---

## Лицензия

MIT License — свободное использование для личных и коммерческих проектов.

---

**Версия:** 1.0.0
**Последнее обновление:** май 2026
