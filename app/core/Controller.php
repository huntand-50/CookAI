<?php
/**
 * Base Controller
 */
class Controller
{
    protected $protected = false; // Требует аутентификацию
    protected $admin_only = false; // Только для администраторов

    /**
     * Рендер вью
     */
    protected function render($view, $data = [])
    {
        extract($data);
        require ROOT_PATH . '/app/views/' . $view . '.php';
    }

    /**
     * Вывод JSON
     */
    protected function json($data, $status = 200)
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($status);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Проверка CSRF-токена
     */
    protected function validateCSRF()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
            if (!$token || $token !== $_SESSION['csrf_token']) {
                $this->json(['error' => 'CSRF несовпадение'], 403);
            }
        }
    }

    /**
     * Отредактирование вывода в JSON
     */
    protected function jsonResponse($success = true, $message = '', $data = [])
    {
        $this->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
    }

    /**
     * Перенаправление
     */
    protected function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }

    /**
     * Получение CSRF-токена
     */
    protected function generateCSRFToken()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}
?>
