<?php
/**
 * AuthController - аутентификация и регистрация
 */
class AuthController extends Controller
{
    /**
     * Форма входа
     */
    public function loginForm()
    {
        $this->render('auth/login', [
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }

    /**
     * Вход пользователя
     */
    public function login()
    {
        $this->validateCSRF();
        
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $this->jsonResponse(false, 'Заполните все поля');
        }
        
        $db = Database::getInstance();
        $user = $db->one(
            "SELECT * FROM users WHERE email = ?",
            [$email]
        );
        
        if (!$user || !password_verify($password, $user['password_hash'])) {
            $this->jsonResponse(false, 'Неверные учетные данные');
        }
        
        if ($user['status'] === 'blocked') {
            $this->jsonResponse(false, 'Ваш аккаунт заблокирован');
        }
        
        // Установка сессии
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['email'] = $user['email'];
        
        // Обновление времени последнего входа
        $db->update('users', ['last_login' => date('Y-m-d H:i:s')], 'id = ?', [$user['id']]);
        
        $this->jsonResponse(true, 'Вы успешно вошли', [
            'redirect' => '/CookAI/public/'
        ]);
    }

    /**
     * Форма регистрации
     */
    public function registerForm()
    {
        $this->render('auth/register', [
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }

    /**
     * Регистрация нового пользователя
     */
    public function register()
    {
        $this->validateCSRF();
        
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        
        // Валидация
        if (empty($username) || empty($email) || empty($password)) {
            $this->jsonResponse(false, 'Заполните все поля');
        }
        
        if (strlen($username) < 3) {
            $this->jsonResponse(false, 'Имя пользователя должно быть минимум 3 символа');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse(false, 'Неверный формат email');
        }
        
        if (strlen($password) < 6) {
            $this->jsonResponse(false, 'Пароль должен быть минимум 6 символов');
        }
        
        if ($password !== $password_confirm) {
            $this->jsonResponse(false, 'Пароли не совпадают');
        }
        
        $db = Database::getInstance();
        
        // Проверка уникальности
        if ($db->count('users', 'email = ?', [$email]) > 0) {
            $this->jsonResponse(false, 'Этот email уже используется');
        }
        
        if ($db->count('users', 'username = ?', [$username]) > 0) {
            $this->jsonResponse(false, 'Это имя пользователя уже занято');
        }
        
        // Создание пользователя
        $password_hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
        
        $db->insert('users', [
            'username' => $username,
            'email' => $email,
            'password_hash' => $password_hash,
            'role' => 'user',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        $this->jsonResponse(true, 'Регистрация успешна! Перейдите на вход', [
            'redirect' => '/CookAI/public/login'
        ]);
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
