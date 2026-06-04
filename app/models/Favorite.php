<?php
/**
 * Favorite Model
 */
class Favorite
{
    protected $db;
    protected $table = 'favorites';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Получить избранные рецепты пользователя
     */
    public function getUserFavorites($user_id)
    {
        return $this->db->all(
            "SELECT r.* FROM recipes r
             INNER JOIN {$this->table} f ON r.id = f.recipe_id
             WHERE f.user_id = ?
             ORDER BY f.created_at DESC",
            [$user_id]
        );
    }

    /**
     * Проверить избранное
     */
    public function isFavorite($user_id, $recipe_id)
    {
        return $this->db->one(
            "SELECT * FROM {$this->table} WHERE user_id = ? AND recipe_id = ?",
            [$user_id, $recipe_id]
        );
    }

    /**
     * Добавить в избранное
     */
    public function add($user_id, $recipe_id)
    {
        if (!$this->isFavorite($user_id, $recipe_id)) {
            return $this->db->insert($this->table, [
                'user_id' => $user_id,
                'recipe_id' => $recipe_id
            ]);
        }
        return false;
    }

    /**
     * Удалить из избранного
     */
    public function remove($user_id, $recipe_id)
    {
        return $this->db->delete(
            $this->table,
            'user_id = ? AND recipe_id = ?',
            [$user_id, $recipe_id]
        );
    }
}
?>