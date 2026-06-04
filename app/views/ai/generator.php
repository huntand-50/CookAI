<?php
/**
 * AI Recipe Generator
 */
?>

<section>
    <div style="max-width: 600px; margin: 0 auto;">
        <h1 style="text-align: center; font-size: 2rem; color: #2D3436; margin-bottom: 2rem;">✨ Генератор рецептов</h1>
        
        <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            <form id="generatorForm">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                
                <div class="form-group">
                    <label for="ingredients">
                        <h3 style="color: #2D3436; margin-bottom: 0.5rem;">🥘 Ингредиенты</h3>
                        <p style="color: #888; font-size: 0.9rem; margin: 0;">Перечислите доступные ингредиенты через запятую</p>
                    </label>
                    <textarea id="ingredients" name="ingredients" required placeholder="Пример: куриное филе, помидоры, чеснок, оливковое масло" style="width: 100%; padding: 1rem; border: 2px solid #DFE6E9; border-radius: 8px; font-family: inherit; font-size: 1rem; min-height: 80px;"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="diet" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2D3436;">🥗 Тип диеты</label>
                    <select id="diet" name="diet" style="width: 100%; padding: 0.8rem; border: 2px solid #DFE6E9; border-radius: 8px; font-size: 1rem;">
                        <option value="healthy">🌿 Здоровая</option>
                        <option value="keto">⚡ Кето</option>
                        <option value="vegan">🌱 Веган</option>
                        <option value="muscle_gain">💪 Для набора мышц</option>
                        <option value="weight_loss">🏃 Для похудения</option>
                        <option value="any">😋 Любая</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="time" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2D3436;">⏱️ Максимальное время приготовления</label>
                    <div style="display: flex; gap: 1rem;">
                        <input type="range" id="time" name="time" min="5" max="180" value="30" style="flex: 1;">
                        <span id="timeDisplay" style="min-width: 50px; text-align: center; font-weight: 600; color: #FF6B6B;">30 мин</span>
                    </div>
                </div>
                
                <button type="submit" style="width: 100%; padding: 1rem; background: linear-gradient(135deg, #FFE66D, #95E1D3); color: #2D3436; border: none; border-radius: 8px; font-weight: 600; font-size: 1rem; cursor: pointer; margin-top: 2rem;">
                    🤖 Сгенерировать рецепт
                </button>
            </form>
            
            <div id="result" style="display: none; margin-top: 2rem; padding: 1.5rem; background: #f5f5f5; border-radius: 8px;">
                <!-- Результат будет здесь -->
            </div>
        </div>
    </div>
</section>

<script>
    // Update time display
    document.getElementById('time').addEventListener('input', function() {
        document.getElementById('timeDisplay').textContent = this.value + ' мин';
    });
    
    // Handle form submission
    document.getElementById('generatorForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        const resultDiv = document.getElementById('result');
        
        submitBtn.disabled = true;
        submitBtn.textContent = '⏳ Генерирую...';
        resultDiv.style.display = 'none';
        
        try {
            const response = await fetch('/CookAI/public/ai/generate-recipe', {
                method: 'POST',
                body: new FormData(form)
            });
            
            const data = await response.json();
            
            if (data.success) {
                const recipe = data.data.recipe;
                let html = '<h3 style="color: #FF6B6B; margin-bottom: 1rem;">✨ Вот ваш рецепт:</h3>';
                html += '<h4 style="color: #2D3436; margin: 1rem 0 0.5rem 0;">' + recipe.title + '</h4>';
                html += '<p style="color: #888; margin: 0.5rem 0 1rem 0;">' + recipe.description + '</p>';
                
                if (recipe.ingredients) {
                    html += '<h5 style="color: #2D3436; margin-top: 1rem;">Ингредиенты:</h5>';
                    html += '<ul style="margin: 0.5rem 0;">';
                    recipe.ingredients.forEach(ing => {
                        html += '<li>' + ing + '</li>';
                    });
                    html += '</ul>';
                }
                
                if (recipe.instructions) {
                    html += '<h5 style="color: #2D3436; margin-top: 1rem;">Инструкции:</h5>';
                    html += '<ol style="margin: 0.5rem 0;">';
                    recipe.instructions.forEach(ins => {
                        html += '<li>' + ins + '</li>';
                    });
                    html += '</ol>';
                }
                
                resultDiv.innerHTML = html;
                resultDiv.style.display = 'block';
                showNotification('Рецепт успешно сгенерирован! 🎉', 'success');
            } else {
                showNotification(data.message, 'error');
            }
        } catch (err) {
            showNotification('Ошибка: ' + err.message, 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = '🤖 Сгенерировать рецепт';
        }
    });
</script>
