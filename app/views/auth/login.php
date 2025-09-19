<h2><?= $title ?></h2>

<form method="POST" id="loginForm">
    <div class="mb-3">
        <label for="username" class="form-label">
            <i class="fas fa-user"></i> Usuario o Email
        </label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>
    
    <div class="mb-3">
        <label for="password" class="form-label">
            <i class="fas fa-lock"></i> Contraseña
        </label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    
    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="remember">
        <label class="form-check-label" for="remember">
            Recordar mis datos
        </label>
    </div>
    
    <div class="d-grid">
        <button type="submit" class="btn btn-primary" id="loginBtn">
            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
        </button>
    </div>
</form>



<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    const originalBtnText = loginBtn.innerHTML;
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            // Show loading state
            loginBtn.disabled = true;
            loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Iniciando sesión...';
            
            // Set a timeout to restore button if something goes wrong
            setTimeout(function() {
                if (loginBtn.disabled) {
                    loginBtn.disabled = false;
                    loginBtn.innerHTML = originalBtnText;
                }
            }, 10000); // 10 seconds timeout
        });
    }
});
</script>