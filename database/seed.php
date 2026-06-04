<?php
/**
 * Simple database seeder for development
 */

require_once __DIR__ . '/../public/index.php';

echo "🌱 Seeding database...\n\n";

try {
    // Create sample user
    $user = new User();
    $user->create([
        'username' => 'admin',
        'email' => 'admin@cookai.local',
        'password' => 'admin123',
        'role' => 'admin'
    ]);
    echo "✓ Admin user created (admin@cookai.local / admin123)\n";
    
    // Create sample recipes
    $recipe = new Recipe();
    
    $sample_recipes = [
        [
            'user_id' => 1,
            'title' => 'Паста Карбонара',
            'description' => 'Классическая итальянская паста с беконом, яйцами и сливочным сыром',
            'ingredients' => json_encode(['Спагетти 400г', 'Бекон 200г', 'Яйца 3 шт', 'Пармезан 100г', 'Соль', 'Черный перец']),
            'instructions' => json_encode(['Отварить пасту', 'Обжарить бекон', 'Смешать яйца с сыром', 'Соединить все компоненты']),
            'time_minutes' => 20,
            'difficulty' => 'easy',
            'servings' => 4,
            'calories' => 650,
            'status' => 'active',
            'is_seasonal' => 0
        ],
        [
            'user_id' => 1,
            'title' => 'Грилованный лосось',
            'description' => 'Нежный лосось на гриле с лимоном и травами',
            'ingredients' => json_encode(['Филе лосося 600г', 'Лимон 1 шт', 'Оливковое масло', 'Розмарин', 'Тимьян', 'Чеснок']),
            'instructions' => json_encode(['Подготовить лосось', 'Замариновать в масле и травах', 'Гриль на среднем огне 12-15 минут', 'Подать с лимоном']),
            'time_minutes' => 25,
            'difficulty' => 'medium',
            'servings' => 2,
            'calories' => 450,
            'status' => 'active',
            'is_seasonal' => 1
        ],
        [
            'user_id' => 1,
            'title' => 'Овощной салат',
            'description' => 'Свежий салат из сезонных овощей с бальзамическим соусом',
            'ingredients' => json_encode(['Помидоры 300г', 'Салат 150g', 'Огурец 1 шт', 'Болгарский перец', 'Оливковое масло', 'Бальзамический уксус']),
            'instructions' => json_encode(['Нарезать овощи', 'Смешать в салатнице', 'Заправить маслом и уксусом', 'Подать охлажденным']),
            'time_minutes' => 10,
            'difficulty' => 'easy',
            'servings' => 2,
            'calories' => 120,
            'status' => 'active',
            'is_seasonal' => 1
        ]
    ];
    
    foreach ($sample_recipes as $r) {
        $recipe->create($r);
    }
    
    echo "✓ Sample recipes created\n\n";
    echo "✅ Database seeding completed!\n";
    echo "\n📝 Test credentials:\n";
    echo "   Email: admin@cookai.local\n";
    echo "   Password: admin123\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>