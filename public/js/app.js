/**
 * CookAI - Main JavaScript Application
 */

// API endpoints
const API = {
    generateRecipe: '/CookAI/public/ai/generate-recipe',
    scanCalories: '/CookAI/public/ai/scan-calories',
    searchRecipes: '/CookAI/public/recipes/search',
    addFavorite: '/CookAI/public/recipe',
    getAdvice: '/CookAI/public/ai/advice'
};

// ============================================
// NOTIFICATION SYSTEM
// ============================================
function showNotification(message, type = 'info', duration = 3000) {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        background: ${getNotificationColor(type)};
        color: white;
        border-radius: 8px;
        z-index: 10000;
        animation: slideIn 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        max-width: 400px;
        word-wrap: break-word;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, duration);
}

function getNotificationColor(type) {
    const colors = {
        'success': '#27AE60',
        'error': '#E74C3C',
        'warning': '#F39C12',
        'info': '#3498DB'
    };
    return colors[type] || colors['info'];
}

// ============================================
// LOADING STATE
// ============================================
function showLoading(element) {
    element.innerHTML = '<div class="loading"></div>';
}

function hideLoading(element) {
    element.innerHTML = '';
}

// ============================================
// FORM HANDLING
// ============================================
function handleFormSubmit(formId, apiEndpoint) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.style.display = 'block';
    
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        submitBtn.disabled = true;
        submitBtn.textContent = '⏳ Отправка...';
        
        try {
            const response = await fetch(apiEndpoint, {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                showNotification(result.message, 'success');
                if (result.data && result.data.redirect) {
                    setTimeout(() => window.location.href = result.data.redirect, 1000);
                }
                form.reset();
            } else {
                showNotification(result.message || 'Произошла ошибка', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Ошибка запроса: ' + error.message, 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });
}

// ============================================
// SEARCH FUNCTIONALITY
// ============================================
function initializeSearch() {
    const searchForm = document.getElementById('searchForm');
    if (!searchForm) return;

    searchForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const query = document.getElementById('searchInput').value.trim();
        
        if (!query) {
            showNotification('Введите поисковый запрос', 'warning');
            return;
        }
        
        window.location.href = `/CookAI/public/recipes/search?q=${encodeURIComponent(query)}`;
    });
    
    // Debounced search suggestions (optional)
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            const query = e.target.value.trim();
            
            if (query.length < 2) return;
            
            searchTimeout = setTimeout(() => {
                // Future: Add autocomplete suggestions
            }, 300);
        });
    }
}

// ============================================
// FAVORITE TOGGLE
// ============================================
function toggleFavorite(recipeId, button) {
    if (!button) return;
    
    button.disabled = true;
    const originalText = button.textContent;
    button.textContent = '⏳';
    
    fetch(`${API.addFavorite}/${recipeId}/favorite`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const isFavorite = data.data.isFavorite;
            button.textContent = isFavorite ? '❤️ В избранном' : '♡ В избранное';
            button.style.background = isFavorite ? '#FF6B6B' : 'transparent';
            button.style.color = isFavorite ? 'white' : 'inherit';
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message || 'Ошибка', 'error');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showNotification('Ошибка: ' + err.message, 'error');
    })
    .finally(() => {
        button.disabled = false;
        if (button.textContent === '⏳') {
            button.textContent = originalText;
        }
    });
}

// ============================================
// MODAL DIALOGS
// ============================================
function showModal(title, message, buttons = []) {
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.style.cssText = `
        display: flex;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        align-items: center;
        justify-content: center;
        z-index: 10001;
    `;
    
    const modalContent = document.createElement('div');
    modalContent.style.cssText = `
        background: white;
        padding: 2rem;
        border-radius: 12px;
        max-width: 500px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    `;
    
    let html = `<h2 style="color: #2D3436; margin-bottom: 1rem;">${title}</h2>`;
    html += `<p style="color: #666; margin-bottom: 1.5rem;">${message}</p>`;
    html += '<div style="display: flex; gap: 1rem; justify-content: flex-end;">';
    
    buttons.forEach(btn => {
        html += `<button style="padding: 0.6rem 1.2rem; border: 2px solid #DFE6E9; background: ${btn.bg || 'white'}; color: ${btn.color || '#2D3436'}; border-radius: 8px; cursor: pointer; font-weight: 600;" onclick="${btn.onclick}">${btn.text}</button>`;
    });
    
    html += '</div>';
    modalContent.innerHTML = html;
    modal.appendChild(modalContent);
    document.body.appendChild(modal);
    
    return modal;
}

function closeModal(modal) {
    if (modal) modal.remove();
}

// ============================================
// RECIPE RATING
// ============================================
function rateRecipe(recipeId, rating) {
    if (!recipeId || !rating) return;
    
    fetch(`/CookAI/public/recipe/${recipeId}/rate`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ rating: rating })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showNotification('Спасибо за оценку! ⭐', 'success');
            // Update star display
            updateStarRating(recipeId, rating);
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showNotification('Ошибка при сохранении оценки', 'error');
    });
}

function updateStarRating(recipeId, rating) {
    const starsContainer = document.querySelector(`[data-recipe-id="${recipeId}"] .stars`);
    if (!starsContainer) return;
    
    starsContainer.innerHTML = '';
    for (let i = 1; i <= 5; i++) {
        const star = document.createElement('span');
        star.textContent = i <= rating ? '★' : '☆';
        star.style.color = i <= rating ? '#FFE66D' : '#DFE6E9';
        star.style.cursor = 'pointer';
        star.style.fontSize = '1.5rem';
        star.style.marginRight = '0.2rem';
        star.onclick = () => rateRecipe(recipeId, i);
        starsContainer.appendChild(star);
    }
}

// ============================================
// COMMENT SYSTEM
// ============================================
function submitComment(recipeId, text) {
    if (!recipeId || !text.trim()) {
        showNotification('Введите комментарий', 'warning');
        return;
    }
    
    fetch(`/CookAI/public/recipe/${recipeId}/comment`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ text: text })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showNotification('Комментарий добавлен', 'success');
            // Refresh comments
            location.reload();
        } else {
            showNotification(data.message || 'Ошибка', 'error');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showNotification('Ошибка при отправке комментария', 'error');
    });
}

// ============================================
// TIME INPUT FORMATTER
// ============================================
function formatTimeInput(minutes) {
    if (minutes < 60) return `${minutes} мин`;
    const hours = Math.floor(minutes / 60);
    const mins = minutes % 60;
    return `${hours}ч ${mins > 0 ? mins + 'мин' : ''}`;
}

// ============================================
// LAZY LOAD IMAGES
// ============================================
function initLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');
    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });
    
    images.forEach(img => observer.observe(img));
}

// ============================================
// FILTER RECIPES
// ============================================
function filterRecipes(criteria) {
    const params = new URLSearchParams();
    
    if (criteria.difficulty) params.append('difficulty', criteria.difficulty);
    if (criteria.time) params.append('time', criteria.time);
    if (criteria.diet) params.append('diet', criteria.diet);
    if (criteria.search) params.append('q', criteria.search);
    
    window.location.href = `/CookAI/public/recipes?${params.toString()}`;
}

// ============================================
// RECIPE SCALE
// ============================================
function scaleRecipe(servings, originalServings) {
    if (!servings || !originalServings) return 1;
    return servings / originalServings;
}

function updateIngredientQuantities(scale) {
    const ingredients = document.querySelectorAll('.ingredient-quantity');
    ingredients.forEach(ingredient => {
        const originalQuantity = parseFloat(ingredient.dataset.original);
        const newQuantity = (originalQuantity * scale).toFixed(2);
        ingredient.textContent = newQuantity + ' ' + ingredient.dataset.unit;
    });
}

// ============================================
// PRINT RECIPE
// ============================================
function printRecipe(recipeId) {
    const recipeElement = document.querySelector(`[data-recipe-id="${recipeId}"]`);
    if (!recipeElement) return;
    
    const printWindow = window.open('', '', 'height=400,width=600');
    printWindow.document.write(`
        <html>
            <head>
                <title>Рецепт</title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    h1 { color: #FF6B6B; }
                    h2 { color: #2D3436; border-bottom: 2px solid #FFE66D; padding-bottom: 0.5rem; }
                    ul, ol { line-height: 1.8; }
                </style>
            </head>
            <body>
                ${recipeElement.innerHTML}
                <script>window.print();</script>
            </body>
        </html>
    `);
    printWindow.document.close();
}

// ============================================
// EXPORT RECIPE
// ============================================
function exportRecipeJSON(recipeId) {
    const recipeElement = document.querySelector(`[data-recipe-id="${recipeId}"]`);
    if (!recipeElement) return;
    
    const recipe = {
        title: recipeElement.querySelector('.recipe-title')?.textContent,
        description: recipeElement.querySelector('.recipe-description')?.textContent,
        ingredients: Array.from(recipeElement.querySelectorAll('.ingredient')).map(el => el.textContent),
        instructions: Array.from(recipeElement.querySelectorAll('.instruction')).map(el => el.textContent),
        time: recipeElement.dataset.time,
        difficulty: recipeElement.dataset.difficulty,
        servings: recipeElement.dataset.servings,
        exportedAt: new Date().toISOString()
    };
    
    const dataStr = JSON.stringify(recipe, null, 2);
    const dataBlob = new Blob([dataStr], { type: 'application/json' });
    const url = URL.createObjectURL(dataBlob);
    const link = document.createElement('a');
    link.href = url;
    link.download = `${recipe.title}.json`;
    link.click();
}

// ============================================
// INITIALIZATION
// ============================================
document.addEventListener('DOMContentLoaded', () => {
    initializeSearch();
    initLazyLoading();
    
    // CSS animations setup
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #DFE6E9;
            border-top-color: #FF6B6B;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    `;
    document.head.appendChild(style);
});

// ============================================
// KEYBOARD SHORTCUTS
// ============================================
document.addEventListener('keydown', (e) => {
    // Ctrl/Cmd + / to focus search
    if ((e.ctrlKey || e.metaKey) && e.key === '/') {
        e.preventDefault();
        document.getElementById('searchInput')?.focus();
    }
    
    // Escape to close modals
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal').forEach(m => m.remove());
    }
});

// ============================================
// UTILITY FUNCTIONS
// ============================================
function debounce(func, delay) {
    let timeoutId;
    return function(...args) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => func(...args), delay);
    };
}

function throttle(func, delay) {
    let lastCall = 0;
    return function(...args) {
        const now = Date.now();
        if (now - lastCall >= delay) {
            func(...args);
            lastCall = now;
        }
    };
}

// Local storage utilities
const Storage = {
    set: (key, value) => localStorage.setItem(key, JSON.stringify(value)),
    get: (key) => {
        const item = localStorage.getItem(key);
        return item ? JSON.parse(item) : null;
    },
    remove: (key) => localStorage.removeItem(key),
    clear: () => localStorage.clear()
};
