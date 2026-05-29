<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CookAI - Интеллектуальная кулинарная платформа</title>
    <link rel="stylesheet" href="/CookAI/public/css/style.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='75' font-size='75'>🍳</text></svg>">
</head>
<body>
    <!-- HEADER -->
    <header>
        <div class="header-container">
            <a href="/CookAI/public/" class="logo">CookAI</a>
            
            <nav>
                <ul>
                    <li><a href="/CookAI/public/">Главная</a></li>
                    <li><a href="/CookAI/public/recipes">Рецепты</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="/CookAI/public/ai">AI Инструменты</a></li>
                        <li><a href="/CookAI/public/community">Сообщество</a></li>
                        <li><a href="/CookAI/public/cookbooks">Мои книги</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            
            <div class="user-section">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span>👤 <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                        <a href="/CookAI/public/admin" class="btn-auth">Админ</a>
                    <?php endif; ?>
                    <a href="/CookAI/public/profile" class="btn-auth btn-secondary">Профиль</a>
                    <a href="/CookAI/public/logout" class="btn-auth">Выход</a>
                <?php else: ?>
                    <a href="/CookAI/public/login" class="btn-auth">Вход</a>
                    <a href="/CookAI/public/register" class="btn-auth btn-secondary">Регистрация</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main>
        <?php echo $content; ?>
    </main>

    <!-- FOOTER -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>О CookAI</h3>
                <p>Интеллектуальная кулинарная платформа с AI для генерации рецептов и анализа питания.</p>
            </div>
            
            <div class="footer-section">
                <h3>Быстрые ссылки</h3>
                <ul>
                    <li><a href="/CookAI/public/">Главная</a></li>
                    <li><a href="/CookAI/public/recipes">Рецепты</a></li>
                    <li><a href="/CookAI/public/community">Сообщество</a></li>
                    <li><a href="/CookAI/public/ai">AI</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Поддержка</h3>
                <ul>
                    <li><a href="#">Справка</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Контакты</a></li>
                    <li><a href="#">Политика</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2026 CookAI. Все права защищены. | <a href="#" style="color: inherit;">Политика конфиденциальности</a></p>
        </div>
    </footer>

    <script src="/CookAI/public/js/app.js"></script>
</body>
</html>
