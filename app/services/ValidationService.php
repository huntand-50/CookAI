<?php
/**
 * ValidationService - сервис для валидации данных
 */
class ValidationService
{
    /**
     * Валидация email
     */
    public static function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Валидация URL
     */
    public static function validateURL($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    /**
     * Валидация IP адреса
     */
    public static function validateIP($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP);
    }

    /**
     * Очистка строки от опасных символов
     */
    public static function sanitize($string)
    {
        return htmlspecialchars(strip_tags(trim($string)), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Валидация числа
     */
    public static function isNumeric($value)
    {
        return is_numeric($value) && $value >= 0;
    }

    /**
     * Проверка размера пароля
     */
    public static function isValidPassword($password)
    {
        return strlen($password) >= 6 && strlen($password) <= 255;
    }
}
?>