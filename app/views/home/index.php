<?php
/**
 * Home page - главная страница
 */
?>

<section class="hero">
    <div class="hero-content">
        <h1>🍳 CookAI - Готовь с Умом</h1>
        <p>Генерируй рецепты, анализируй питание и делись своими кулинарными шедеврами</p>
        <div class="hero-buttons">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="/CookAI/public/register" class="btn-primary">Начать бесплатно</a>
            <?php else: ?>
                <a href="/CookAI/public/ai" class="btn-primary">🤖 AI Генератор</a>
            <?php endif; ?>
            <a href="/CookAI/public/recipes" class="btn-primary" style="background: var(--accent); color: var(--dark);">Смотреть рецепты</a>
        </div>
    </div>
</section>

<!-- POPULAR RECIPES -->
<section>
    <h2>⭐ Популярные рецепты</h2>
    <div class="recipes-grid">
        <?php if (!empty($popular_recipes)): ?>
            <?php foreach ($popular_recipes as $recipe): ?>
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
                        <div class="recipe-meta">
                            <span class="meta-tag time"><?php echo $recipe['time_minutes']; ?> мин</span>
                            <span class="meta-tag difficulty <?php echo $recipe['difficulty']; ?>">
                                <?php 
                                $diff = ['easy' => 'Легко', 'medium' => 'Среднее', 'hard' => 'Сложно'];
                                echo $diff[$recipe['difficulty']] ?? 'Среднее';
                                ?>
                            </span>
                            <span class="meta-tag servings"><?php echo $recipe['servings']; ?> порций</span>
                        </div>
                        <p class="recipe-author">от <?php echo htmlspecialchars($recipe['username'] ?? 'Гость'); ?></p>
                        <div class="recipe-footer">
                            <span class="recipe-rating">👁️ <?php echo $recipe['views']; ?></span>
                            <button class="btn-small">В избранное ♡</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-muted">Рецепты пока не добавлены</p>
        <?php endif; ?>
    </div>
</section>

<!-- SEASONAL RECIPES -->
<section style="background: #fff9e6;">
    <h2>🌾 Сезонные рецепты</h2>
    <div class="recipes-grid">
        <?php if (!empty($seasonal_recipes)): ?>
            <?php foreach ($seasonal_recipes as $recipe): ?>
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
                        <div class="recipe-footer">
                            <span class="recipe-rating">⭐ 4.8</span>
                            <button class="btn-small">Готовить</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-muted">Сезонные рецепты не найдены</p>
        <?php endif; ?>
    </div>
</section>

<!-- AI FEATURES -->
<section style="background: linear-gradient(135deg, #FFE66D20 0%, #95E1D320 100%);">
    <h2>🤖 AI возможности CookAI</h2>
    <div class="recipes-grid" style="margin-top: 2rem;">
        <div class="recipe-card" style="border: 2px solid var(--secondary);">
            <div class="recipe-image" style="background: linear-gradient(135deg, #FFE66D, #FFD93D);">
                <span style="font-size: 3rem;">✨</span>
            </div>
            <div class="recipe-info">
                <h3 class="recipe-title">Генератор рецептов</h3>
                <p class="recipe-description">
                    Опишите ингредиенты, диету и время - AI создаст идеальный рецепт за секунды
                </p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/CookAI/public/ai/generator" class="btn-small" style="width: 100%; text-align: center;">Попробовать</a>
                <?php else: ?>
                    <a href="/CookAI/public/register" class="btn-small" style="width: 100%; text-align: center;">Зарегистрироваться</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="recipe-card" style="border: 2px solid var(--accent);">
            <div class="recipe-image" style="background: linear-gradient(135deg, #95E1D3, #7DD3C0);">
                <span style="font-size: 3rem;">📸</span>
            </div>
            <div class="recipe-info">
                <h3 class="recipe-title">Сканирование калорий</h3>
                <p class="recipe-description">
                    Сфотографируй блюдо и AI определит его состав, калорийность и БЖУК
                </p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/CookAI/public/ai/scan" class="btn-small" style="width: 100%; text-align: center;">Сканировать</a>
                <?php else: ?>
                    <a href="/CookAI/public/register" class="btn-small" style="width: 100%; text-align: center;">Зарегистрироваться</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="recipe-card" style="border: 2px solid var(--primary);">
            <div class="recipe-image" style="background: linear-gradient(135deg, #FF6B6B, #FF5252);">
                <span style="font-size: 3rem;">📋</span>
            </div>
            <div class="recipe-info">
                <h3 class="recipe-title">План питания</h3>
                <p class="recipe-description">
                    AI создаст персональный план питания с учётом вашей диеты и целей
                </p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/CookAI/public/ai/meal-plan" class="btn-small" style="width: 100%; text-align: center;">Создать план</a>
                <?php else: ?>
                    <a href="/CookAI/public/register" class="btn-small" style="width: 100%; text-align: center;">Зарегистрироваться</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- STATS -->
<section>
    <h2>📊 Статистика CookAI</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; text-align: center;">
        <div>
            <h3 style="font-size: 2.5rem; color: var(--primary);">1000+</h3>
            <p>Рецептов в базе</p>
        </div>
        <div>
            <h3 style="font-size: 2.5rem; color: var(--accent);">500+</h3>
            <p>Активных пользователей</p>
        </div>
        <div>
            <h3 style="font-size: 2.5rem; color: var(--secondary);">10K+</h3>
            <p>Прогнозов в день</p>
        </div>
    </div>
</section>
