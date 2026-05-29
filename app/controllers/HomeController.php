<?php
/**
 * HomeController - главная страница
 */
class HomeController extends Controller
{
    public function index()
    {
        $db = Database::getInstance();
        
        // Получение популярных рецептов
        $popular_recipes = $db->all(
            "SELECT r.*, u.username FROM recipes r 
             LEFT JOIN users u ON r.user_id = u.id 
             WHERE r.status = 'active' 
             ORDER BY r.views DESC LIMIT 6"
        );
        
        // Получение сезонных рецептов
        $seasonal_recipes = $db->all(
            "SELECT r.*, u.username FROM recipes r 
             LEFT JOIN users u ON r.user_id = u.id 
             WHERE r.status = 'active' AND r.is_seasonal = 1
             ORDER BY r.created_at DESC LIMIT 6"
        );
        
        // Получение всех рецептов с пагинацией
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $per_page = 12;
        $offset = ($page - 1) * $per_page;
        
        $all_recipes = $db->all(
            "SELECT r.*, u.username FROM recipes r 
             LEFT JOIN users u ON r.user_id = u.id 
             WHERE r.status = 'active'
             ORDER BY r.created_at DESC 
             LIMIT ? OFFSET ?",
            [$per_page, $offset]
        );
        
        $total_recipes = $db->count('recipes', "status = 'active'");
        $total_pages = ceil($total_recipes / $per_page);
        
        $this->render('home/index', [
            'popular_recipes' => $popular_recipes,
            'seasonal_recipes' => $seasonal_recipes,
            'all_recipes' => $all_recipes,
            'current_page' => $page,
            'total_pages' => $total_pages
        ]);
    }
}
?>
