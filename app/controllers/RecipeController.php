<?php
/**
 * RecipeController - управление рецептами
 */
class RecipeController extends Controller
{
    protected $protected = true;

    /**
     * Список рецептов
     */
    public function index()
    {
        $db = Database::getInstance();
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $per_page = 12;
        $offset = ($page - 1) * $per_page;
        
        $recipes = $db->all(
            "SELECT r.*, u.username FROM recipes r 
             LEFT JOIN users u ON r.user_id = u.id 
             WHERE r.status = 'active'
             ORDER BY r.created_at DESC 
             LIMIT ? OFFSET ?",
            [$per_page, $offset]
        );
        
        $total = $db->count('recipes', "status = 'active'");
        $total_pages = ceil($total / $per_page);
        
        $this->render('recipes/index', [
            'recipes' => $recipes,
            'current_page' => $page,
            'total_pages' => $total_pages
        ]);
    }

    /**
     * Показ одного рецепта
     */
    public function show($id)
    {
        $db = Database::getInstance();
        $recipe = $db->one(
            "SELECT r.*, u.username FROM recipes r 
             LEFT JOIN users u ON r.user_id = u.id 
             WHERE r.id = ? AND r.status = 'active'",
            [$id]
        );
        
        if (!$recipe) {
            http_response_code(404);
            die("Рецепт не найден");
        }
        
        // Увеличение счетчика просмотров
        $db->update('recipes', 
            ['views' => $recipe['views'] + 1], 
            'id = ?', 
            [$id]
        );
        
        // Получение комментариев
        $comments = $db->all(
            "SELECT c.*, u.username FROM comments c 
             LEFT JOIN users u ON c.user_id = u.id 
             WHERE c.recipe_id = ? 
             ORDER BY c.created_at DESC",
            [$id]
        );
        
        $this->render('recipes/show', [
            'recipe' => $recipe,
            'comments' => $comments,
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }

    /**
     * Поиск рецептов
     */
    public function search()
    {
        $query = trim($_GET['q'] ?? '');
        $db = Database::getInstance();
        
        if (empty($query)) {
            $this->redirect('/CookAI/public/recipes');
        }
        
        $search_term = '%' . $query . '%';
        
        $recipes = $db->all(
            "SELECT r.*, u.username FROM recipes r 
             LEFT JOIN users u ON r.user_id = u.id 
             WHERE r.status = 'active' AND 
             (r.title LIKE ? OR r.description LIKE ? OR r.ingredients LIKE ?)
             ORDER BY r.created_at DESC 
             LIMIT 50",
            [$search_term, $search_term, $search_term]
        );
        
        $this->render('recipes/search_results', [
            'recipes' => $recipes,
            'query' => $query
        ]);
    }

    /**
     * Создание рецепта
     */
    public function store()
    {
        $this->validateCSRF();
        
        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse(false, 'Вы не авторизованы', [], 403);
        }
        
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $ingredients = trim($_POST['ingredients'] ?? '');
        $instructions = trim($_POST['instructions'] ?? '');
        $time_minutes = (int)($_POST['time_minutes'] ?? 0);
        $difficulty = trim($_POST['difficulty'] ?? 'medium');
        $servings = (int)($_POST['servings'] ?? 1);
        
        // Валидация
        if (empty($title) || empty($description) || empty($ingredients)) {
            $this->jsonResponse(false, 'Заполните обязательные поля');
        }
        
        $db = Database::getInstance();
        
        // Загрузка изображения
        $image_path = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image_path = $this->uploadImage($_FILES['image']);
        }
        
        // Сохранение рецепта
        $db->insert('recipes', [
            'user_id' => $_SESSION['user_id'],
            'title' => $title,
            'description' => $description,
            'ingredients' => $ingredients,
            'instructions' => $instructions,
            'time_minutes' => $time_minutes,
            'difficulty' => $difficulty,
            'servings' => $servings,
            'image_path' => $image_path,
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        $recipe_id = $db->lastInsertId();
        
        $this->jsonResponse(true, 'Рецепт создан успешно', [
            'id' => $recipe_id,
            'redirect' => '/CookAI/public/recipe/' . $recipe_id
        ]);
    }

    /**
     * Загрузка изображения
     */
    private function uploadImage($file)
    {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = basename($file['name']);
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (!in_array($ext, $allowed)) {
            return null;
        }
        
        $new_name = uniqid('recipe_') . '.' . $ext;
        $upload_dir = ROOT_PATH . '/storage/uploads/recipes/';
        
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        if (move_uploaded_file($file['tmp_name'], $upload_dir . $new_name)) {
            return '/storage/uploads/recipes/' . $new_name;
        }
        
        return null;
    }
}
?>
