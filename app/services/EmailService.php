<?php
/**
 * EmailService - сервис отправки писем
 */
class EmailService
{
    private $from;
    private $host;
    private $port;
    private $username;
    private $password;

    public function __construct()
    {
        $this->from = $_ENV['MAIL_FROM'] ?? 'noreply@cookai.local';
        $this->host = $_ENV['MAIL_HOST'] ?? '';
        $this->port = $_ENV['MAIL_PORT'] ?? 465;
        $this->username = $_ENV['MAIL_USERNAME'] ?? '';
        $this->password = $_ENV['MAIL_PASSWORD'] ?? '';
    }

    /**
     * Отправка письма
     */
    public function send($to, $subject, $message, $isHTML = true)
    {
        $headers = "From: {$this->from}\r\n";
        $headers .= "Reply-To: {$this->from}\r\n";
        
        if ($isHTML) {
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        }

        return mail($to, $subject, $message, $headers);
    }

    /**
     * Письмо подтверждения регистрации
     */
    public function sendWelcomeEmail($email, $username)
    {
        $subject = 'Добро пожаловать в CookAI';
        $message = "<h2>Добро пожаловать, $username!</h2>";
        $message .= "<p>Вы успешно зарегистрировались на CookAI.</p>";
        $message .= "<p>Начните с просмотра наших рецептов и попробуйте AI генератор!</p>";
        
        return $this->send($email, $subject, $message);
    }

    /**
     * Письмо восстановления пароля
     */
    public function sendPasswordResetEmail($email, $reset_link)
    {
        $subject = 'Восстановление пароля';
        $message = "<h2>Восстановление пароля</h2>";
        $message .= "<p>Перейдите по ссылке для восстановления пароля:</p>";
        $message .= "<a href=\"$reset_link\">$reset_link</a>";
        $message .= "<p>Если вы не запрашивали восстановление пароля, игнорируйте это письмо.</p>";
        
        return $this->send($email, $subject, $message);
    }
}
?>