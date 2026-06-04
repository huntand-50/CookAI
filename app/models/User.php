<?php
/**
 * User Model
 */
class User
{
    protected $db;
    protected $table = 'users';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Получить пользователя по ID
     */
    public function getById($id)
    {
        return $this->db->one(
            "SELECT * FROM {$this->table} WHERE id = ? AND status = 'active'",
            [$id]
        );
    }

    /**
     * Получить пользователя по email
     */
    public function getByEmail($email)
    {
        return $this->db->one(
            "SELECT * FROM {$this->table} WHERE email = ? AND status = 'active'",
            [$email]
        );
    }

    /**
     * Создать пользователя
     */
    public function create($data)
    {
        $data['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT);
        unset($data['password']);
        $data['created_at'] = date('Y-m-d H:i:s');

        return $this->db->insert($this->table, $data);
    }

    /**
     * Проверить пароль
     */
    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Обновить профиль
     */
    public function updateProfile($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->update($this->table, $data, 'id = ?', [$id]);
    }

    /**
     * Получить всех пользователей (для администратора)
     */
    public function getAll($limit = 50, $offset = 0)
    {
        return $this->db->all(
            "SELECT id, username, email, role, status, created_at FROM {$this->table} ORDER BY created_at DESC LIMIT ? OFFSET ?",
            [$limit, $offset]
        );
    }
}
?>