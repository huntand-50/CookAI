<?php
/**
 * Comment Model
 */
class Comment
{
    protected $db;
    protected $table = 'comments';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Получить комментарии рецепта
     */
    public function getByRecipe($recipe_id, $limit = 50)
    {
        return $this->db->all(
            "SELECT c.*, u.username, u.avatar_path FROM {$this->table} c
             LEFT JOIN users u ON c.user_id = u.id
             WHERE c.recipe_id = ?
             ORDER BY c.created_at DESC
             LIMIT ?",
            [$recipe_id, $limit]
        );
    }

    /**
     * Создать комментарий
     */
    public function create($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->table, $data);
    }

    /**
     * Удалить комментарий
     */
    public function delete($id)
    {
        return $this->db->delete($this->table, 'id = ?', [$id]);
    }
}
?>