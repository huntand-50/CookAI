<?php
/**
 * CookAI - Application Entry Point
 * Главная точка входа приложения
 */

// Определение корневого пути
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');

// Автозагрузка классов
spl_autoload_register(function ($class) {
    $paths = [
        APP_PATH . '/core/' . $class . '.php',
        APP_PATH . '/controllers/' . $class . '.php',
        APP_PATH . '/models/' . $class . '.php',
        APP_PATH . '/services/' . $class . '.php',
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require $path;
            return;
        }
    }
});

// Загрузка переменных окружения
if (file_exists(ROOT_PATH . '/.env')) {
    $env = parse_ini_file(ROOT_PATH . '/.env');
    foreach ($env as $key => $value) {
        putenv("$key=$value");
        $_ENV[$key] = $value;
    }
}

// Запуск сессии
session_start();

// Подключение к БД
try {
    $db_host = $_ENV['DB_HOST'] ?? 'localhost';
    $db_user = $_ENV['DB_USER'] ?? 'root';
    $db_pass = $_ENV['DB_PASS'] ?? '';
    $db_name = $_ENV['DB_NAME'] ?? 'cookai';
    
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
    
    define('DB', $pdo);
} catch (PDOException $e) {
    die('Ошибка подключения к БД: ' . $e->getMessage());
}

// Инициализация роутера
$router = new Router();

// HOME ROUTES
$router->get('/', 'HomeController@index');

// AUTH ROUTES
$router->get('/login', 'AuthController@loginForm');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@registerForm');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');

// RECIPE ROUTES
$router->get('/recipes', 'RecipeController@index');
$router->get('/recipe/:id', 'RecipeController@show');
$router->get('/recipes/search', 'RecipeController@search');
$router->post('/recipes/create', 'RecipeController@store');

// AI ROUTES
$router->get('/ai', 'AIController@index');
$router->get('/ai/generator', 'AIController@generator');
$router->post('/ai/generate-recipe', 'AIController@generateRecipe');
$router->post('/ai/scan-calories', 'AIController@scanCalories');
$router->post('/ai/advice', 'AIController@advice');
$router->get('/ai/meal-plan', 'AIController@mealPlan');

// Получение URL
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Удаление префикса /CookAI/public
if (strpos($uri, '/CookAI/public') === 0) {
    $uri = substr($uri, strlen('/CookAI/public'));
}

// Если пусто, переход на главную
if (empty($uri) || $uri === '/') {
    $uri = '/';
}

// Удаление слэша в конце
$uri = rtrim($uri, '/') ?: '/';

// Запуск маршрута
try {
    $router->dispatch($method, $uri);
} catch (Exception $e) {
    http_response_code(404);
    echo "Ошибка: " . htmlspecialchars($e->getMessage());
}
?>