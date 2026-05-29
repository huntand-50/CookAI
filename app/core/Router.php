<?php
/**
 * Router - роутизатор URL
 */
class Router
{
    private $routes = [];
    private $current_route = null;

    /**
     * Регистрация GET-маршрута
     */
    public function get($path, $action)
    {
        $this->routes['GET'][$path] = $action;
    }

    /**
     * Регистрация POST-маршрута
     */
    public function post($path, $action)
    {
        $this->routes['POST'][$path] = $action;
    }

    /**
     * Регистрация DELETE-маршрута
     */
    public function delete($path, $action)
    {
        $this->routes['DELETE'][$path] = $action;
    }

    /**
     * Проверка роута и вывыв контроллера
     */
    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Обрезание base URL
        $base = '/CookAI/public';
        $url = str_replace($base, '', $url);
        if (empty($url)) $url = '/';
        
        $this->current_route = $url;
        
        // Поиск точного маршрута
        if (isset($this->routes[$method][$url])) {
            $this->executeRoute($this->routes[$method][$url], []);
            return;
        }

        // Поиск динамического маршрута с параметрами
        foreach ($this->routes[$method] ?? [] as $pattern => $action) {
            if ($this->matchRoute($pattern, $url, $params)) {
                $this->executeRoute($action, $params);
                return;
            }
        }

        // Ошибка 404
        $this->error404();
    }

    /**
     * Построение регекспа из маршрута
     */
    private function matchRoute($pattern, $url, &$params)
    {
        $pattern = str_replace('/', '\\/', $pattern);
        $pattern = preg_replace('/:[a-zA-Z0-9_]+/', '([a-zA-Z0-9_-]+)', $pattern);
        
        if (preg_match('/^' . $pattern . '$/', $url, $matches)) {
            array_shift($matches);
            $params = $matches;
            return true;
        }
        
        return false;
    }

    /**
     * Выполнение маршрута
     */
    private function executeRoute($action, $params)
    {
        [$controller_name, $method_name] = explode('@', $action);
        
        // Подключение нужного контроллера
        $controller_file = ROOT_PATH . '/app/controllers/' . $controller_name . '.php';
        
        if (!file_exists($controller_file)) {
            $this->error404();
            return;
        }
        
        require_once $controller_file;
        
        // Создание экземпляра контроллера
        $controller = new $controller_name();
        
        // Проверка аутентификации
        if (!empty($controller->protected) && !isset($_SESSION['user_id'])) {
            header('Location: /CookAI/public/login');
            exit;
        }
        
        // Проверка прав администратора
        if (!empty($controller->admin_only) && ($_SESSION['user_role'] ?? 'user') !== 'admin') {
            http_response_code(403);
            die("Доступ запрещен");
        }
        
        // Вывыв метода контроллера
        call_user_func_array([$controller, $method_name], $params);
    }

    /**
     * Ошибка 404
     */
    private function error404()
    {
        http_response_code(404);
        require ROOT_PATH . '/app/views/errors/404.php';
    }
}
?>
