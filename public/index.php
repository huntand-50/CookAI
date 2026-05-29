<?php
/**
 * CookAI - Интеллектуальная кулинарная платформа
 * Точка входа приложения
 */

// Установка заголовков безопасности
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Content-Type: text/html; charset=utf-8');

// Путь к корню приложения
define('ROOT_PATH', dirname(dirname(__FILE__)));
define('PUBLIC_PATH', __DIR__);

// Инициализация
require ROOT_PATH . '/config/database.php';
require ROOT_PATH . '/app/core/Router.php';
require ROOT_PATH . '/app/core/Controller.php';

// Обработка сессии
session_start();

// Загрузка переменных окружения
$env_file = ROOT_PATH . '/config/.env';
if (file_exists($env_file)) {
    $env_vars = parse_ini_file($env_file);
    foreach ($env_vars as $key => $value) {
        if (!isset($_ENV[$key])) {
            $_ENV[$key] = $value;
        }
    }
}

// Роутинг
$router = new Router();

// Публичные маршруты
$router->get('/', 'HomeController@index');
$router->get('/recipes', 'RecipeController@index');
$router->get('/recipe/:id', 'RecipeController@show');
$router->get('/search', 'RecipeController@search');

// Аутентификация
$router->get('/login', 'AuthController@loginForm');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@registerForm');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');

// AI инструменты
$router->get('/ai', 'AIController@index');
$router->get('/ai/generator', 'AIController@generator');
$router->post('/api/ai/generate-recipe', 'AIController@generateRecipe');
$router->post('/api/ai/scan-calories', 'AIController@scanCalories');
$router->post('/api/ai/advice', 'AIController@advice');
$router->get('/ai/meal-plan', 'AIController@mealPlan');

// Личный кабинет (защищено аутентификацией)
$router->get('/profile', 'ProfileController@index');
$router->get('/cookbooks', 'CookbookController@index');
$router->post('/api/cookbooks', 'CookbookController@create');
$router->get('/cookbook/:id', 'CookbookController@show');
$router->post('/api/favorite', 'ProfileController@toggleFavorite');

// Социальные функции
$router->get('/community', 'SocialController@communities');
$router->get('/community/:id', 'SocialController@showCommunity');
$router->post('/api/communities', 'SocialController@createCommunity');
$router->get('/challenges', 'SocialController@challenges');
$router->get('/ratings', 'SocialController@ratings');
$router->get('/friends', 'SocialController@friends');
$router->post('/api/messages', 'SocialController@sendMessage');
$router->get('/api/messages/:userId', 'SocialController@getMessages');

// Админ-панель (защищено ролью)
$router->get('/admin', 'AdminController@dashboard');
$router->get('/admin/users', 'AdminController@users');
$router->get('/admin/recipes', 'AdminController@recipes');
$router->get('/admin/communities', 'AdminController@communities');
$router->post('/api/admin/user/:id', 'AdminController@updateUser');
$router->post('/api/admin/recipe/:id', 'AdminController@updateRecipe');
$router->get('/admin/settings', 'AdminController@settings');

// API эндпойнты (AJAX)
$router->post('/api/recipe', 'RecipeController@store');
$router->post('/api/recipe/:id', 'RecipeController@update');
$router->delete('/api/recipe/:id', 'RecipeController@delete');

// Запуск маршрутизатора
$router->dispatch();
?>