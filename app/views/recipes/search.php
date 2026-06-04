<?php
/**
 * Search results view
 */
?>

<section>
    <h1 style="font-size: 2rem; color: #2D3436; margin-bottom: 1rem;">
        🔍 Результаты поиска: "<?php echo htmlspecialchars($query); ?>"
    </h1>
    
    <div class="recipes-grid">
        <?php if (!empty($recipes)): ?>
            <p style="grid-column: 1/-1; color: #888; margin-bottom: 1rem;">Найдено <?php echo count($recipes); ?> рецепт<?php echo count($recipes) % 10 == 1 ? '' : 'ов'; ?></p>
            
            <?php foreach ($recipes as $recipe): ?>
                <div class="recipe-card">
                    <div class="recipe-image">
                        <?php if ($recipe['image_path']): ?>
                            <img src="<?php echo htmlspecialchars($recipe['image_path']); ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>">
                        <?php endif; ?>
                    </div>
                    <div class="recipe-info">
                        <a href="/CookAI/public/recipe/<?php echo $recipe['id']; ?>" class="recipe-title">
                            <?php echo htmlspecialchars($recipe['title']); ?>
                        </a>
                        <p class="recipe-description">
                            <?php echo substr(htmlspecialchars($recipe['description']), 0, 100) . '...'; ?>
                        </p>
                        <p class="recipe-author">от <?php echo htmlspecialchars($recipe['username'] ?? 'Гость'); ?></p>
                        <div class="recipe-footer">
                            <span class="recipe-rating">👁️ <?php echo $recipe['views']; ?></span>
                            <a href="/CookAI/public/recipe/<?php echo $recipe['id']; ?>" class="btn-small">Смотреть</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 2rem;">
                <p style="font-size: 1.2rem; color: #888;">Рецепты не найдены 😢</p>
                <p style="color: #aaa; margin-top: 1rem;">Попробуйте изменить поисковый запрос или используйте AI для генерации нового рецепта</p>
                <a href="/CookAI/public/ai/generator" style="display: inline-block; margin-top: 1rem; padding: 0.8rem 1.5rem; background: #FF6B6B; color: white; text-decoration: none; border-radius: 25px; font-weight: 600;">
                    🤖 Генератор рецептов
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>
