<?php
/**
 * Recipe Model
 */
class Recipe
{
    protected $db;
    protected $table = 'recipes';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Получить рецепт по ID
     */
    public function getById($id)
    {
        return $this->db->one(
            "SELECT r.*, u.username FROM {$this->table} r
             LEFT JOIN users u ON r.user_id = u.id
             WHERE r.id = ? AND r.status = 'active'",
            [$id]
        );
    }

    /**
     * Получить все активные рецепты
     */
    public function getActive($limit = 12, $offset = 0)
    {
        return $this->db->all(
            "SELECT r.*, u.username FROM {$this->table} r
             LEFT JOIN users u ON r.user_id = u.id
             WHERE r.status = 'active'
             ORDER BY r.created_at DESC
             LIMIT ? OFFSET ?",
            [$limit, $offset]
        );
    }

    /**
     * Получить популярные рецепты
     */
    public function getPopular($limit = 6)
    {
        return $this->db->all(
            "SELECT r.*, u.username FROM {$this->table} r
             LEFT JOIN users u ON r.user_id = u.id
             WHERE r.status = 'active'
             ORDER BY r.views DESC, r.created_at DESC
             LIMIT ?",
            [$limit]
        );
    }

    /**
     * Получить сезонные рецепты
     */
    public function getSeasonal($limit = 6)
    {
        return $this->db->all(
            "SELECT r.*, u.username FROM {$this->table} r
             LEFT JOIN users u ON r.user_id = u.id
             WHERE r.status = 'active' AND r.is_seasonal = 1
             ORDER BY r.created_at DESC
             LIMIT ?",
            [$limit]
        );
    }

    /**
     * Поиск рецептов
     */
    public function search($query, $limit = 50)
    {
        $search_term = '%' . $query . '%';
        return $this->db->all(
            "SELECT r.*, u.username FROM {$this->table} r
             LEFT JOIN users u ON r.user_id = u.id
             WHERE r.status = 'active' AND (
                r.title LIKE ? OR
                r.description LIKE ? OR
                r.ingredients LIKE ?
             )
             ORDER BY r.created_at DESC
             LIMIT ?",
            [$search_term, $search_term, $search_term, $limit]
        );
    }

    /**
     * Создать рецепт
     */
    public function create($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->table, $data);
    }

    /**
     * Обновить рецепт
     */
    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->update($this->table, $data, 'id = ?', [$id]);
    }

    /**
     * Увеличить счётчик просмотров
     */
    public function incrementViews($id)
    {
        return $this->db->update(
            $this->table,
            ['views' => DB::raw('views + 1')],
            'id = ?',
            [$id]
        );
    }
}
?>