<?php
/**
 * Router - система маршрутизации приложения
 */
class Router
{
    private $routes = [];
    private $params = [];

    /**
     * Регистрация GET маршрута
     */
    public function get($path, $handler)
    {
        $this->routes['GET'][$path] = $handler;
    }

    /**
     * Регистрация POST маршрута
     */
    public function post($path, $handler)
    {
        $this->routes['POST'][$path] = $handler;
    }

    /**
     * Регистрация PUT маршрута
     */
    public function put($path, $handler)
    {
        $this->routes['PUT'][$path] = $handler;
    }

    /**
     * Регистрация DELETE маршрута
     */
    public function delete($path, $handler)
    {
        $this->routes['DELETE'][$path] = $handler;
    }

    /**
     * Диспетчеризация запроса
     */
    public function dispatch($method, $uri)
    {
        if (!isset($this->routes[$method])) {
            throw new Exception("Метод $method не зарегистрирован");
        }

        foreach ($this->routes[$method] as $path => $handler) {
            if ($this->match($path, $uri)) {
                return $this->execute($handler);
            }
        }

        throw new Exception("Маршрут не найден: $method $uri");
    }

    /**
     * Проверка совпадения маршрута
     */
    private function match($pattern, $uri)
    {
        $pattern = preg_replace('/:\w+/', '([\w-]+)', $pattern);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $uri, $matches)) {
            array_shift($matches);
            $this->params = $matches;
            return true;
        }

        return false;
    }

    /**
     * Выполнение обработчика маршрута
     */
    private function execute($handler)
    {
        list($controller, $action) = explode('@', $handler);

        $controller_class = new $controller();

        if (!method_exists($controller_class, $action)) {
            throw new Exception("Метод $action не найден в $controller");
        }

        return call_user_func_array([$controller_class, $action], $this->params);
    }

    /**
     * Получение параметров маршрута
     */
    public function getParams()
    {
        return $this->params;
    }
}
?>