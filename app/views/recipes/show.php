<?php
/**
 * Recipe detail view
 */
?>

<section>
    <div style="max-width: 800px; margin: 0 auto;">
        <?php if (isset($recipe)): ?>
            <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                
                <!-- Recipe Image -->
                <div style="width: 100%; height: 400px; background: linear-gradient(135deg, #FFE66D, #95E1D3); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                    <?php if ($recipe['image_path']): ?>
                        <img src="<?php echo htmlspecialchars($recipe['image_path']); ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <span style="font-size: 5rem; opacity: 0.3;">🍽️</span>
                    <?php endif; ?>
                </div>
                
                <!-- Recipe Info -->
                <div style="padding: 2rem;">
                    <h1 style="font-size: 2rem; color: #2D3436; margin-bottom: 1rem;"><?php echo htmlspecialchars($recipe['title']); ?></h1>
                    
                    <div style="display: flex; gap: 2rem; margin-bottom: 2rem; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 150px;">
                            <h3 style="color: #FF6B6B; margin-bottom: 0.5rem;">⏱️ Время</h3>
                            <p style="font-size: 1.2rem; font-weight: 600;"><?php echo $recipe['time_minutes']; ?> минут</p>
                        </div>
                        <div style="flex: 1; min-width: 150px;">
                            <h3 style="color: #95E1D3; margin-bottom: 0.5rem;">📊 Сложность</h3>
                            <p style="font-size: 1.2rem; font-weight: 600;">
                                <?php 
                                $diff = ['easy' => '🟢 Легко', 'medium' => '🟡 Среднее', 'hard' => '🔴 Сложно'];
                                echo $diff[$recipe['difficulty']] ?? 'Среднее';
                                ?>
                            </p>
                        </div>
                        <div style="flex: 1; min-width: 150px;">
                            <h3 style="color: #FFE66D; margin-bottom: 0.5rem;">🍴 Порций</h3>
                            <p style="font-size: 1.2rem; font-weight: 600;"><?php echo $recipe['servings']; ?></p>
                        </div>
                    </div>
                    
                    <?php if ($recipe['calories']): ?>
                        <div style="background: #f5f5f5; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
                            <p style="color: #FF6B6B; font-weight: 600;">🔥 Калории: <?php echo $recipe['calories']; ?> ккал на порцию</p>
                        </div>
                    <?php endif; ?>
                    
                    <div style="margin-bottom: 2rem;">
                        <h2 style="color: #2D3436; margin-bottom: 1rem;">📝 Описание</h2>
                        <p style="color: #555; line-height: 1.8;"><?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>
                    </div>
                    
                    <?php if (!empty($recipe['ingredients'])): ?>
                        <div style="margin-bottom: 2rem;">
                            <h2 style="color: #2D3436; margin-bottom: 1rem;">🥘 Ингредиенты</h2>
                            <ul style="list-style: none; padding: 0;">
                                <?php foreach ($recipe['ingredients'] as $ingredient): ?>
                                    <li style="padding: 0.5rem 0; border-bottom: 1px solid #DFE6E9; color: #555;">
                                        ✓ <?php echo htmlspecialchars(is_array($ingredient) ? ($ingredient['name'] ?? '') : $ingredient); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($recipe['instructions'])): ?>
                        <div style="margin-bottom: 2rem;">
                            <h2 style="color: #2D3436; margin-bottom: 1rem;">👨‍🍳 Инструкции</h2>
                            <ol style="padding-left: 1.5rem;">
                                <?php foreach ($recipe['instructions'] as $index => $instruction): ?>
                                    <li style="padding: 0.5rem 0; color: #555; margin-bottom: 0.5rem;">
                                        <?php echo htmlspecialchars(is_array($instruction) ? ($instruction['step'] ?? '') : $instruction); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ol>
                        </div>
                    <?php endif; ?>
                    
                    <div style="background: #f5f5f5; padding: 1rem; border-radius: 8px; text-align: center;">
                        <p style="color: #888; margin: 0;">👨‍🍳 Автор: <strong><?php echo htmlspecialchars($recipe['username'] ?? 'Гость'); ?></strong></p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 2rem;">
                <p style="font-size: 1.2rem; color: #888;">Рецепт не найден 😢</p>
            </div>
        <?php endif; ?>
    </div>
</section>
