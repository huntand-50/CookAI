<?php
/**
 * AIController - контроллер AI функций
 */
class AIController extends Controller
{
    private $ai_service;
    private $recipe;
    protected $protected = true;

    public function __construct()
    {
        parent::__construct();
        $this->ai_service = new AIService();
        $this->recipe = new Recipe();
    }

    /**
     * Главная страница AI
     */
    public function index()
    {
        $this->render('ai.index');
    }

    /**
     * Генератор рецептов
     */
    public function generator()
    {
        $this->render('ai.generator', [
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }

    /**
     * Генерация рецепта через AI
     */
    public function generateRecipe()
    {
        $this->validateCSRF();

        $diet = $_POST['diet'] ?? 'healthy';
        $time = $_POST['time'] ?? 30;
        $ingredients = $_POST['ingredients'] ?? '';

        if (empty($ingredients)) {
            $this->jsonResponse(false, 'Укажите ингредиенты', [], 400);
        }

        try {
            $params = [
                'diet' => $diet,
                'time' => $time,
                'ingredients' => $ingredients
            ];

            $recipe_data = $this->ai_service->generateRecipe($params);

            // Сохранение рецепта
            $recipe_id = $this->saveGeneratedRecipe($recipe_data);

            $this->jsonResponse(true, 'Рецепт сгенерирован', [
                'recipe' => $recipe_data,
                'recipe_id' => $recipe_id
            ]);
        } catch (Exception $e) {
            $this->jsonResponse(false, $e->getMessage(), [], 500);
        }
    }

    /**
     * Сканирование калорий
     */
    public function scanCalories()
    {
        if (empty($_FILES['image'])) {
            $this->jsonResponse(false, 'Загрузите изображение', [], 400);
        }

        try {
            $result = $this->ai_service->scanCalories($_FILES['image']);
            $this->jsonResponse(true, 'Анализ завершён', $result);
        } catch (Exception $e) {
            $this->jsonResponse(false, $e->getMessage(), [], 500);
        }
    }

    /**
     * AI советы
     */
    public function advice()
    {
        $question = $_POST['question'] ?? '';

        if (empty($question)) {
            $this->jsonResponse(false, 'Задайте вопрос', [], 400);
        }

        try {
            $answer = $this->ai_service->getAdvice($question);
            $this->jsonResponse(true, 'Ответ получен', [
                'advice' => $answer
            ]);
        } catch (Exception $e) {
            $this->jsonResponse(false, $e->getMessage(), [], 500);
        }
    }

    /**
     * План питания
     */
    public function mealPlan()
    {
        $this->render('ai.meal-plan', [
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }

    /**
     * Сохранение сгенерированного рецепта
     */
    private function saveGeneratedRecipe($recipe_data)
    {
        $data = [
            'user_id' => $this->getCurrentUser(),
            'title' => $recipe_data['title'] ?? 'AI Рецепт',
            'description' => $recipe_data['description'] ?? '',
            'ingredients' => json_encode($recipe_data['ingredients'] ?? []),
            'instructions' => json_encode($recipe_data['instructions'] ?? []),
            'time_minutes' => $recipe_data['time_minutes'] ?? 30,
            'difficulty' => $recipe_data['difficulty'] ?? 'medium',
            'servings' => $recipe_data['servings'] ?? 1,
            'calories' => $recipe_data['calories'] ?? null,
            'status' => 'draft'
        ];

        $this->recipe->create($data);
        return Database::getInstance()->lastInsertId();
    }
}
?>