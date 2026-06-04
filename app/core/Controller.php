<?php
/**
 * Controller - базовый класс для контроллеров
 */
class Controller
{
    protected $protected = false;
    protected $view_path = '';

    public function __construct()
    {
        // Проверка авторизации для защищённых контроллеров
        if ($this->protected && !isset($_SESSION['user_id'])) {
            $this->redirect('/CookAI/public/login');
        }

        $this->view_path = ROOT_PATH . '/app/views';
    }

    /**
     * Рендеринг представления
     */
    protected function render($view, $data = [])
    {
        $view_file = $this->view_path . '/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($view_file)) {
            throw new Exception("Представление не найдено: $view_file");
        }

        extract($data);

        ob_start();
        include $view_file;
        $content = ob_get_clean();

        include $this->view_path . '/layouts/main.php';
    }

    /**
     * Редирект
     */
    protected function redirect($url)
    {
        header("Location: $url");
        exit;
    }

    /**
     * JSON ответ
     */
    protected function jsonResponse($success, $message, $data = [], $status = 200)
    {
        header('Content-Type: application/json');
        http_response_code($status);

        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }

    /**
     * Генерация CSRF токена
     */
    protected function generateCSRFToken()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Валидация CSRF токена
     */
    protected function validateCSRF()
    {
        $token = $_POST['csrf_token'] ?? '';
        if (empty($token) || $token !== ($_SESSION['csrf_token'] ?? '')) {
            $this->jsonResponse(false, 'CSRF токен невалиден', [], 403);
        }
    }

    /**
     * Проверка авторизации
     */
    protected function isAuthorized()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Получение текущего пользователя
     */
    protected function getCurrentUser()
    {
        return $_SESSION['user_id'] ?? null;
    }
}
?>