<?php
/**
 * RecipeController - контроллер рецептов
 */
class RecipeController extends Controller
{
    private $recipe;
    protected $protected = true;

    public function __construct()
    {
        parent::__construct();
        $this->recipe = new Recipe();
    }

    /**
     * Список рецептов
     */
    public function index()
    {
        $page = $_GET['page'] ?? 1;
        $limit = 12;
        $offset = ($page - 1) * $limit;

        $recipes = $this->recipe->getActive($limit, $offset);
        $total = Database::getInstance()->count('recipes', "status = 'active'");
        $pages = ceil($total / $limit);

        $this->render('recipes.index', [
            'recipes' => $recipes,
            'current_page' => $page,
            'total_pages' => $pages
        ]);
    }

    /**
     * Просмотр одного рецепта
     */
    public function show($id)
    {
        $recipe = $this->recipe->getById($id);

        if (!$recipe) {
            http_response_code(404);
            echo "Рецепт не найден";
            return;
        }

        // Увеличение просмотров
        $this->recipe->incrementViews($id);

        // Парсинг JSON полей
        $recipe['ingredients'] = json_decode($recipe['ingredients'], true) ?? [];
        $recipe['instructions'] = json_decode($recipe['instructions'], true) ?? [];

        $this->render('recipes.show', [
            'recipe' => $recipe
        ]);
    }

    /**
     * Поиск рецептов
     */
    public function search()
    {
        $query = $_GET['q'] ?? '';

        if (empty($query)) {
            $recipes = [];
        } else {
            $recipes = $this->recipe->search($query);
        }

        $this->render('recipes.search', [
            'query' => $query,
            'recipes' => $recipes
        ]);
    }

    /**
     * Создание рецепта
     */
    public function store()
    {
        if (!$this->isAuthorized()) {
            $this->jsonResponse(false, 'Требуется авторизация', [], 401);
        }

        $this->validateCSRF();

        $data = [
            'user_id' => $this->getCurrentUser(),
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'ingredients' => json_encode($_POST['ingredients'] ?? []),
            'instructions' => json_encode($_POST['instructions'] ?? []),
            'time_minutes' => $_POST['time_minutes'] ?? 30,
            'difficulty' => $_POST['difficulty'] ?? 'medium',
            'servings' => $_POST['servings'] ?? 1,
            'calories' => $_POST['calories'] ?? null,
            'status' => 'draft'
        ];

        // Валидация
        if (empty($data['title']) || empty($data['description'])) {
            $this->jsonResponse(false, 'Заполните все обязательные поля', [], 400);
        }

        // Загрузка изображения
        if (!empty($_FILES['image'])) {
            $image_path = $this->uploadImage($_FILES['image']);
            if ($image_path) {
                $data['image_path'] = $image_path;
            }
        }

        try {
            $this->recipe->create($data);
            $recipe_id = Database::getInstance()->lastInsertId();

            $this->jsonResponse(true, 'Рецепт создан', [
                'redirect' => '/CookAI/public/recipe/' . $recipe_id
            ]);
        } catch (Exception $e) {
            $this->jsonResponse(false, 'Ошибка: ' . $e->getMessage(), [], 500);
        }
    }

    /**
     * Загрузка изображения
     */
    private function uploadImage($file)
    {
        $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
        $max_size = 5 * 1024 * 1024; // 5MB

        if (!in_array($file['type'], $allowed_types)) {
            return false;
        }

        if ($file['size'] > $max_size) {
            return false;
        }

        $upload_dir = ROOT_PATH . '/storage/uploads/recipes/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $filename = uniqid() . '_' . basename($file['name']);
        $filepath = $upload_dir . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return '/CookAI/storage/uploads/recipes/' . $filename;
        }

        return false;
    }
}
?>