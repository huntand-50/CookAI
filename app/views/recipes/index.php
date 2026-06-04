<?php
/**
 * Recipes list view
 */
?>

<section>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="font-size: 2rem; color: #2D3436;">📚 Все рецепты</h1>
        
        <form id="searchForm" style="display: flex; gap: 1rem;">
            <input type="text" id="searchInput" placeholder="Поиск рецептов..." 
                   style="padding: 0.8rem 1rem; border: 2px solid #DFE6E9; border-radius: 25px; flex: 1; font-size: 1rem;">
            <button type="submit" style="padding: 0.8rem 1.5rem; background: #FF6B6B; color: white; border: none; border-radius: 25px; cursor: pointer; font-weight: 600;">
                Поиск
            </button>
        </form>
    </div>
    
    <div class="recipes-grid">
        <?php if (!empty($recipes)): ?>
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
                        <div class="recipe-meta">
                            <span class="meta-tag time"><?php echo $recipe['time_minutes']; ?> мин</span>
                            <span class="meta-tag difficulty <?php echo $recipe['difficulty']; ?>">
                                <?php 
                                $diff = ['easy' => 'Легко', 'medium' => 'Среднее', 'hard' => 'Сложно'];
                                echo $diff[$recipe['difficulty']] ?? 'Среднее';
                                ?>
                            </span>
                        </div>
                        <p class="recipe-author">👨‍🍳 <?php echo htmlspecialchars($recipe['username'] ?? 'Гость'); ?></p>
                        <div class="recipe-footer">
                            <span class="recipe-rating">👁️ <?php echo $recipe['views']; ?></span>
                            <a href="/CookAI/public/recipe/<?php echo $recipe['id']; ?>" class="btn-small">Смотреть</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 2rem; color: #888;">
                <p style="font-size: 1.1rem;">Рецепты не найдены 😢</p>
            </div>
        <?php endif; ?>
    </div>
    
    <?php if (isset($total_pages) && $total_pages > 1): ?>
        <div style="display: flex; justify-content: center; gap: 1rem; margin-top: 2rem;">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" 
                   style="padding: 0.5rem 1rem; background: <?php echo $i == ($current_page ?? 1) ? '#FF6B6B' : '#DFE6E9'; ?>; color: <?php echo $i == ($current_page ?? 1) ? 'white' : '#2D3436'; ?>; text-decoration: none; border-radius: 6px;">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</section>
