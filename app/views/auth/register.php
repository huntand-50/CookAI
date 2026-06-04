<?php
/**
 * Register form view
 */
?>

<div style="max-width: 400px; margin: 50px auto; padding: 20px;">
    <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <h2 style="text-align: center; color: #95E1D3; margin-bottom: 30px;">✨ Регистрация в CookAI</h2>
        
        <form id="registerForm" style="display: none;">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            
            <div style="margin-bottom: 20px;">
                <label for="username" style="display: block; margin-bottom: 8px; font-weight: 500;">Имя пользователя:</label>
                <input type="text" id="username" name="username" required minlength="3" style="width: 100%; padding: 10px; border: 2px solid #DFE6E9; border-radius: 8px; font-size: 1rem;">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label for="email" style="display: block; margin-bottom: 8px; font-weight: 500;">Email:</label>
                <input type="email" id="email" name="email" required style="width: 100%; padding: 10px; border: 2px solid #DFE6E9; border-radius: 8px; font-size: 1rem;">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label for="password" style="display: block; margin-bottom: 8px; font-weight: 500;">Пароль:</label>
                <input type="password" id="password" name="password" required minlength="6" style="width: 100%; padding: 10px; border: 2px solid #DFE6E9; border-radius: 8px; font-size: 1rem;">
                <small style="color: #888;">Минимум 6 символов</small>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label for="password_confirm" style="display: block; margin-bottom: 8px; font-weight: 500;">Подтверждение пароля:</label>
                <input type="password" id="password_confirm" name="password_confirm" required minlength="6" style="width: 100%; padding: 10px; border: 2px solid #DFE6E9; border-radius: 8px; font-size: 1rem;">
            </div>
            
            <button type="submit" style="width: 100%; padding: 12px; background: #95E1D3; color: #2D3436; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 1rem; margin-top: 20px;">
                Зарегистрироваться
            </button>
        </form>
        
        <div style="text-align: center; margin-top: 20px;">
            <p style="color: #888; margin-bottom: 10px;">Уже есть аккаунт?</p>
            <a href="/CookAI/public/login" style="color: #FF6B6B; text-decoration: none; font-weight: 600;">Войти</a>
        </div>
    </div>
</div>

<script>
    handleFormSubmit('registerForm', '/CookAI/public/register');
</script>
