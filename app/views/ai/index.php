<?php
/**
 * AI main page
 */
?>

<section>
    <h1 style="text-align: center; font-size: 2.5rem; color: #2D3436; margin-bottom: 1rem;">🤖 AI Помощник CookAI</h1>
    <p style="text-align: center; font-size: 1.1rem; color: #888; margin-bottom: 3rem;">
        Используй мощь искусственного интеллекта для создания идеальных рецептов
    </p>
    
    <div class="recipes-grid">
        <!-- Generator Card -->
        <div class="recipe-card" style="border: 2px solid #FFE66D;">
            <div class="recipe-image" style="background: linear-gradient(135deg, #FFE66D, #FFD93D);">
                <span style="font-size: 3rem;">✨</span>
            </div>
            <div class="recipe-info">
                <h3 class="recipe-title">Генератор рецептов</h3>
                <p class="recipe-description">
                    Напишите ингредиенты, выберите диету и время - AI создаст уникальный рецепт за секунды
                </p>
                <div class="recipe-footer" style="border-top: none;">
                    <a href="/CookAI/public/ai/generator" class="btn-small" style="width: 100%; text-align: center;">
                        Начать генерацию →
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Calorie Scanner Card -->
        <div class="recipe-card" style="border: 2px solid #95E1D3;">
            <div class="recipe-image" style="background: linear-gradient(135deg, #95E1D3, #7DD3C0);">
                <span style="font-size: 3rem;">📸</span>
            </div>
            <div class="recipe-info">
                <h3 class="recipe-title">Сканер калорий</h3>
                <p class="recipe-description">
                    Сфотографируй блюдо и AI определит его состав, калорийность и БЖУК
                </p>
                <div class="recipe-footer" style="border-top: none;">
                    <a href="#" onclick="alert('Функция в разработке'); return false;" class="btn-small" style="width: 100%; text-align: center;">
                        Сканировать →
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Meal Plan Card -->
        <div class="recipe-card" style="border: 2px solid #FF6B6B;">
            <div class="recipe-image" style="background: linear-gradient(135deg, #FF6B6B, #FF5252);">
                <span style="font-size: 3rem;">📋</span>
            </div>
            <div class="recipe-info">
                <h3 class="recipe-title">План питания</h3>
                <p class="recipe-description">
                    AI создаст персональный план питания с учётом вашей диеты и целей на неделю
                </p>
                <div class="recipe-footer" style="border-top: none;">
                    <a href="#" onclick="alert('Функция в разработке'); return false;" class="btn-small" style="width: 100%; text-align: center;">
                        Создать план →
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
