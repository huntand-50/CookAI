<?php
/**
 * HomeController - контроллер главной страницы
 */
class HomeController extends Controller
{
    private $recipe;

    public function __construct()
    {
        parent::__construct();
        $this->recipe = new Recipe();
    }

    /**
     * Главная страница
     */
    public function index()
    {
        $popular_recipes = $this->recipe->getPopular(6);
        $seasonal_recipes = $this->recipe->getSeasonal(3);

        $this->render('home.index', [
            'popular_recipes' => $popular_recipes,
            'seasonal_recipes' => $seasonal_recipes
        ]);
    }
}
?>