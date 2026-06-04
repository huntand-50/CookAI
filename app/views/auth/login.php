<?php
/**
 * Login form view
 */
?>

<div style="max-width: 400px; margin: 50px auto; padding: 20px;">
    <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <h2 style="text-align: center; color: #FF6B6B; margin-bottom: 30px;">🔐 Вход в CookAI</h2>
        
        <form id="loginForm" style="display: none;">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            
            <div style="margin-bottom: 20px;">
                <label for="email" style="display: block; margin-bottom: 8px; font-weight: 500;">Email:</label>
                <input type="email" id="email" name="email" required style="width: 100%; padding: 10px; border: 2px solid #DFE6E9; border-radius: 8px; font-size: 1rem;">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label for="password" style="display: block; margin-bottom: 8px; font-weight: 500;">Пароль:</label>
                <input type="password" id="password" name="password" required style="width: 100%; padding: 10px; border: 2px solid #DFE6E9; border-radius: 8px; font-size: 1rem;">
            </div>
            
            <button type="submit" style="width: 100%; padding: 12px; background: #FF6B6B; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 1rem; margin-top: 20px;">
                Войти
            </button>
        </form>
        
        <div style="text-align: center; margin-top: 20px;">
            <p style="color: #888; margin-bottom: 10px;">Нет аккаунта?</p>
            <a href="/CookAI/public/register" style="color: #95E1D3; text-decoration: none; font-weight: 600;">Зарегистрироваться</a>
        </div>
    </div>
</div>

<script>
    handleFormSubmit('loginForm', '/CookAI/public/login');
</script>
