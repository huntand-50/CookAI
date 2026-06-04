<?php
/**
 * AuthController - контроллер аутентификации
 */
class AuthController extends Controller
{
    private $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = new User();
    }

    /**
     * Форма входа
     */
    public function loginForm()
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/CookAI/public/');
        }
        $this->render('auth.login', [
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }

    /**
     * Обработка входа
     */
    public function login()
    {
        $this->validateCSRF();

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->jsonResponse(false, 'Заполните все поля', [], 400);
        }

        $user = $this->user->getByEmail($email);

        if (!$user || !$this->user->verifyPassword($password, $user['password_hash'])) {
            $this->jsonResponse(false, 'Неверный email или пароль', [], 401);
        }

        // Установка сессии
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_email'] = $user['email'];

        $this->jsonResponse(true, 'Вы успешно вошли', [
            'redirect' => '/CookAI/public/'
        ]);
    }

    /**
     * Форма регистрации
     */
    public function registerForm()
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/CookAI/public/');
        }
        $this->render('auth.register', [
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }

    /**
     * Обработка регистрации
     */
    public function register()
    {
        $this->validateCSRF();

        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        // Валидация
        if (empty($username) || empty($email) || empty($password)) {
            $this->jsonResponse(false, 'Заполните все поля', [], 400);
        }

        if (strlen($username) < 3) {
            $this->jsonResponse(false, 'Имя пользователя должно быть не менее 3 символов', [], 400);
        }

        if (strlen($password) < 6) {
            $this->jsonResponse(false, 'Пароль должен быть не менее 6 символов', [], 400);
        }

        if ($password !== $password_confirm) {
            $this->jsonResponse(false, 'Пароли не совпадают', [], 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse(false, 'Некорректный email', [], 400);
        }

        // Проверка существования пользователя
        if ($this->user->getByEmail($email)) {
            $this->jsonResponse(false, 'Email уже зарегистрирован', [], 400);
        }

        // Создание пользователя
        try {
            $this->user->create([
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'role' => 'user'
            ]);

            $this->jsonResponse(true, 'Регистрация успешна', [
                'redirect' => '/CookAI/public/login'
            ]);
        } catch (Exception $e) {
            $this->jsonResponse(false, 'Ошибка регистрации: ' . $e->getMessage(), [], 500);
        }
    }

    /**
     * Выход
     */
    public function logout()
    {
        session_destroy();
        $this->redirect('/CookAI/public/');
    }
}
?>