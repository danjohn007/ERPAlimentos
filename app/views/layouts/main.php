<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - ' : '' ?><?= APP_NAME ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2E8B57;
            --secondary-color: #FFD700;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --dark-color: #343a40;
            --light-color: #f8f9fa;
        }
        
        .navbar-brand {
            font-weight: bold;
            color: var(--primary-color) !important;
        }
        
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: var(--light-color);
            border-right: 1px solid #dee2e6;
        }
        
        .sidebar .nav-link {
            color: var(--dark-color);
            padding: 10px 15px;
            border-radius: 5px;
            margin: 2px 0;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: var(--primary-color);
            color: white;
        }
        
        .card-stats {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .card-stats .card-header {
            border: none;
            border-radius: 10px 10px 0 0;
            padding: 1.25rem;
        }
        
        .card-stats .d-flex {
            align-items: center;
            gap: 15px;
        }
        
        .stats-icon {
            font-size: 2rem;
            opacity: 0.8;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            margin-left: auto;
        }
        
        .table th {
            background-color: var(--light-color);
            border-top: none;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #246447;
            border-color: #246447;
        }
        
        .content-wrapper {
            padding: 20px;
        }
        
        .loading {
            display: none;
        }
        
        .cheese-icon {
            color: var(--secondary-color);
        }
        
        /* Estilos para el menú de usuario */
        .user-dropdown .user-menu {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 1px solid #dee2e6;
            border-radius: 25px;
            padding: 8px 15px;
            transition: all 0.3s ease;
            text-decoration: none;
            color: var(--dark-color) !important;
        }
        
        .user-dropdown .user-menu:hover {
            background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transform: translateY(-1px);
        }
        
        .user-avatar {
            display: inline-block;
            margin-right: 8px;
        }
        
        .user-avatar i {
            font-size: 1.8rem;
            color: var(--primary-color);
        }
        
        .user-info {
            display: inline-block;
            vertical-align: middle;
            line-height: 1.2;
        }
        
        .user-name {
            display: block;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .user-role {
            display: block;
            font-size: 0.75rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .user-dropdown-menu {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            padding: 0;
            min-width: 280px;
            margin-top: 10px;
        }
        
        .user-dropdown-menu .dropdown-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #246447 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 15px 15px 0 0;
            margin: 0;
        }
        
        .user-info-header {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .user-avatar-large {
            font-size: 2.5rem;
            color: rgba(255,255,255,0.9);
        }
        
        .user-dropdown-menu .dropdown-item {
            padding: 12px 20px;
            transition: all 0.3s ease;
            border-radius: 0;
        }
        
        .user-dropdown-menu .dropdown-item:hover {
            background-color: #f8f9fa;
            padding-left: 25px;
        }
        
        .user-dropdown-menu .dropdown-item i {
            width: 20px;
            text-align: center;
            margin-right: 10px;
            color: var(--primary-color);
        }
        
        .user-dropdown-menu .logout-item {
            border-radius: 0 0 15px 15px;
        }
        
        .user-dropdown-menu .logout-item:hover {
            background-color: #fff5f5;
            color: var(--danger-color);
        }
        
        .user-dropdown-menu .logout-item:hover i {
            color: var(--danger-color);
        }
        
        .user-dropdown-menu .dropdown-divider {
            margin: 5px 0;
        }
        
        @media (max-width: 768px) {
            .user-info {
                display: none;
            }
            
            .user-dropdown-menu {
                min-width: 250px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= $this->url('dashboard') ?>">
                <i class="fas fa-cheese cheese-icon"></i>
                <?= APP_NAME ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown user-dropdown">
                        <a class="nav-link dropdown-toggle user-menu" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <span class="user-info">
                                <span class="user-name"><?= Auth::getUserName() ?></span>
                                <span class="user-role"><?= ucfirst(Auth::getUserRole() ?? 'Usuario') ?></span>
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end user-dropdown-menu">
                            <li class="dropdown-header">
                                <div class="user-info-header">
                                    <i class="fas fa-user-circle user-avatar-large"></i>
                                    <div>
                                        <strong><?= Auth::getUserName() ?></strong>
                                        <small class="text-muted d-block"><?php $user = Auth::getUser(); echo $user['email'] ?? 'Sin email'; ?></small>
                                    </div>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="openProfileModal()">
                                    <i class="fas fa-user-edit"></i> Mi Perfil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?= $this->url('admin/configuracion') ?>">
                                    <i class="fas fa-cog"></i> Configuración
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item logout-item" href="<?= $this->url('logout') ?>">
                                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 col-lg-2 px-0">
                <div class="sidebar">
                    <nav class="nav flex-column p-3">
                        <a class="nav-link" href="<?= $this->url('dashboard') ?>">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                        
                        <hr class="my-2">
                        
                        <a class="nav-link" href="<?= $this->url('produccion') ?>">
                            <i class="fas fa-industry"></i> Producción
                        </a>
                        <a class="nav-link ms-3" href="<?= $this->url('produccion/recetas') ?>">
                            <i class="fas fa-book"></i> Recetas
                        </a>
                        <a class="nav-link ms-3" href="<?= $this->url('produccion/lotes') ?>">
                            <i class="fas fa-boxes"></i> Lotes
                        </a>
                        
                        <a class="nav-link" href="<?= $this->url('materias-primas') ?>">
                            <i class="fas fa-seedling"></i> Materias Primas
                        </a>
                        <a class="nav-link ms-3" href="<?= $this->url('materias-primas/proveedores') ?>">
                            <i class="fas fa-truck"></i> Proveedores
                        </a>
                        
                        <a class="nav-link" href="<?= $this->url('calidad') ?>">
                            <i class="fas fa-award"></i> Calidad
                        </a>
                        
                        <a class="nav-link" href="<?= $this->url('inventario') ?>">
                            <i class="fas fa-warehouse"></i> Inventario
                        </a>
                        
                        <a class="nav-link" href="<?= $this->url('ventas') ?>">
                            <i class="fas fa-shopping-cart"></i> Ventas
                        </a>
                        <a class="nav-link ms-3" href="<?= $this->url('ventas/clientes') ?>">
                            <i class="fas fa-users"></i> Clientes
                        </a>
                        
                        <a class="nav-link" href="<?= $this->url('compras') ?>">
                            <i class="fas fa-shopping-bag"></i> Compras
                        </a>
                        
                        <a class="nav-link" href="<?= $this->url('finanzas') ?>">
                            <i class="fas fa-chart-line"></i> Finanzas
                        </a>
                        
                        <a class="nav-link" href="<?= $this->url('rrhh') ?>">
                            <i class="fas fa-users-cog"></i> RRHH
                        </a>
                        
                        <a class="nav-link" href="<?= $this->url('reportes') ?>">
                            <i class="fas fa-chart-bar"></i> Reportes
                        </a>
                        
                        <?php if (Auth::hasRole('admin')): ?>
                        <hr class="my-2">
                        <a class="nav-link" href="<?= $this->url('admin') ?>">
                            <i class="fas fa-cogs"></i> Administración
                        </a>
                        <?php endif; ?>
                    </nav>
                </div>
            </div>
            
            <!-- Content -->
            <div class="col-md-10 col-lg-10">
                <div class="content-wrapper">
                    <!-- Flash Messages -->
                    <?= $this->flash() ?>
                    
                    <!-- Page Content -->
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Perfil de Usuario -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="profileModalLabel">
                        <i class="fas fa-user-edit"></i> Mi Perfil
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Información del Usuario -->
                        <div class="col-md-8">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-info-circle text-primary"></i> Información Personal
                            </h6>
                            <form id="profileForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="profile_nombre" class="form-label">Nombre</label>
                                            <input type="text" class="form-control" id="profile_nombre" name="nombre" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="profile_apellidos" class="form-label">Apellidos</label>
                                            <input type="text" class="form-control" id="profile_apellidos" name="apellidos" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="profile_username" class="form-label">Usuario</label>
                                            <input type="text" class="form-control" id="profile_username" name="username" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="profile_email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="profile_email" name="email" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="profile_rol" class="form-label">Rol</label>
                                            <input type="text" class="form-control" id="profile_rol" name="rol" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="profile_ultimo_acceso" class="form-label">Último Acceso</label>
                                            <input type="text" class="form-control" id="profile_ultimo_acceso" name="ultimo_acceso" readonly>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Avatar y Estadísticas -->
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="profile-avatar mb-3">
                                    <i class="fas fa-user-circle" style="font-size: 4rem; color: var(--primary-color);"></i>
                                </div>
                                <h6 id="profile_fullname" class="mb-1"></h6>
                                <span id="profile_role_badge" class="badge bg-primary mb-3"></span>
                                
                                <div class="profile-stats">
                                    <div class="row text-center">
                                        <div class="col-12 mb-2">
                                            <small class="text-muted">Miembro desde</small>
                                            <div id="profile_member_since" class="fw-bold"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Cambio de Contraseña -->
                    <hr class="my-4">
                    <h6 class="border-bottom pb-2 mb-3">
                        <i class="fas fa-key text-warning"></i> Cambiar Contraseña
                    </h6>
                    <form id="changePasswordForm">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Contraseña Actual</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Nueva Contraseña</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                                    <div class="form-text">Mínimo 8 caracteres</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-key"></i> Cambiar Contraseña
                            </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Variables globales
        let isLoading = false;
        
        // Inicialización cuando el DOM está listo
        document.addEventListener('DOMContentLoaded', function() {
            initializeApp();
        });
        
        function initializeApp() {
            activateCurrentNavLink();
            setupNavigationEffects();
            setupLoadingStates();
            setupFormEnhancements();
            setupAutoHideAlerts();
            addPageTransition();
        }
        
        // Activar link del menú actual
        function activateCurrentNavLink() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            
            navLinks.forEach(link => {
                link.classList.remove('active');
                const linkPath = link.getAttribute('href');
                if (linkPath && currentPath.includes(linkPath) && linkPath !== '/') {
                    link.classList.add('active');
                }
            });
            
            // Activar dashboard si estamos en la raíz
            if (currentPath === '/' || currentPath === '/dashboard') {
                const dashboardLink = document.querySelector('a[href*="dashboard"]');
                if (dashboardLink) {
                    dashboardLink.classList.add('active');
                }
            }
        }
        
        // Configurar efectos de navegación
        function setupNavigationEffects() {
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    if (!this.getAttribute('href').startsWith('#')) {
                        showPageLoading();
                    }
                });
            });
        }
        
        // Estados de carga para páginas
        function showPageLoading() {
            if (isLoading) return;
            
            isLoading = true;
            const overlay = createLoadingOverlay();
            document.body.appendChild(overlay);
            
            setTimeout(() => {
                overlay.classList.add('show');
            }, 10);
            
            // Auto-hide después de 3 segundos por si acaso
            setTimeout(() => {
                hidePageLoading();
            }, 3000);
        }
        
        function hidePageLoading() {
            isLoading = false;
            const overlay = document.querySelector('.loading-overlay');
            if (overlay) {
                overlay.classList.remove('show');
                setTimeout(() => {
                    overlay.remove();
                }, 300);
            }
        }
        
        function createLoadingOverlay() {
            const overlay = document.createElement('div');
            overlay.className = 'loading-overlay';
            overlay.innerHTML = `
                <div class="text-center">
                    <div class="loading-spinner"></div>
                    <div class="mt-3">
                        <strong>Cargando...</strong>
                    </div>
                </div>
            `;
            return overlay;
        }
        
        // Configurar estados de carga para formularios y botones
        function setupLoadingStates() {
            // Para formularios
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('button[type="submit"], input[type="submit"]');
                    if (submitBtn && !submitBtn.classList.contains('no-loading')) {
                        showButtonLoading(submitBtn);
                    }
                });
            });
            
            // Para botones con data-loading
            const loadingButtons = document.querySelectorAll('[data-loading]');
            loadingButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    showButtonLoading(this);
                });
            });
        }
        
        function showButtonLoading(button, text = 'Procesando...') {
            if (button.classList.contains('loading')) return;
            
            button.classList.add('loading');
            button.disabled = true;
            button.dataset.originalText = button.innerHTML;
            button.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${text}`;
        }
        
        function hideButtonLoading(button) {
            button.classList.remove('loading');
            button.disabled = false;
            if (button.dataset.originalText) {
                button.innerHTML = button.dataset.originalText;
                delete button.dataset.originalText;
            }
        }
        
        // Mejorar formularios
        function setupFormEnhancements() {
            // Auto-focus en el primer campo de formularios
            const firstInput = document.querySelector('form input:not([type="hidden"]):not([disabled])');
            if (firstInput) {
                firstInput.focus();
            }
            
            // Validación en tiempo real para campos requeridos
            const requiredFields = document.querySelectorAll('input[required], select[required], textarea[required]');
            requiredFields.forEach(field => {
                field.addEventListener('blur', function() {
                    validateField(this);
                });
                
                field.addEventListener('input', function() {
                    if (this.classList.contains('is-invalid')) {
                        validateField(this);
                    }
                });
            });
        }
        
        function validateField(field) {
            const isValid = field.checkValidity();
            field.classList.toggle('is-invalid', !isValid);
            field.classList.toggle('is-valid', isValid);
            
            // Mostrar/ocultar mensaje de error personalizado
            let errorDiv = field.parentNode.querySelector('.invalid-feedback');
            if (!isValid && !errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = field.validationMessage;
                field.parentNode.appendChild(errorDiv);
            } else if (isValid && errorDiv) {
                errorDiv.remove();
            }
        }
        
        // Auto-hide alerts mejorado
        function setupAutoHideAlerts() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    if (alert.classList.contains('alert-success') || alert.classList.contains('alert-info')) {
                        hideAlert(alert);
                    }
                });
            }, 5000);
        }
        
        function hideAlert(alert) {
            alert.style.transition = 'opacity 0.5s, transform 0.5s';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 500);
        }
        
        // Agregar transición de página
        function addPageTransition() {
            const contentWrapper = document.querySelector('.content-wrapper');
            if (contentWrapper) {
                contentWrapper.classList.add('page-transition');
            }
        }
        
        // Confirmación para eliminar mejorada
        function confirmarEliminacion(mensaje = '¿Está seguro de que desea eliminar este elemento?', titulo = 'Confirmar eliminación') {
            return new Promise((resolve) => {
                if (window.confirm(`${titulo}\n\n${mensaje}\n\nEsta acción no se puede deshacer.`)) {
                    resolve(true);
                } else {
                    resolve(false);
                }
            });
        }
        
        // Utilidades adicionales
        function formatDate(dateString) {
            if (!dateString) return 'No disponible';
            try {
                const date = new Date(dateString);
                return date.toLocaleDateString('es-ES', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            } catch (e) {
                return dateString;
            }
        }
        
        function formatMoney(amount, currency = '$') {
            if (isNaN(amount)) return currency + '0.00';
            return currency + parseFloat(amount).toLocaleString('es-ES', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
        
        // Función para mostrar notificaciones toast
        function showToast(message, type = 'info', duration = 3000) {
            const toast = document.createElement('div');
            toast.className = `alert alert-${type} toast-notification`;
            toast.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-${getToastIcon(type)} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
                </div>
            `;
            
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                min-width: 300px;
                animation: slideInRight 0.3s ease-out;
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                hideAlert(toast);
            }, duration);
        }
        
        function getToastIcon(type) {
            const icons = {
                'success': 'check-circle',
                'danger': 'exclamation-triangle',
                'warning': 'exclamation-circle',
                'info': 'info-circle',
                'primary': 'bell'
            };
            return icons[type] || 'info-circle';
        }
        
        // Evento cuando la página se ha cargado completamente
        window.addEventListener('load', function() {
            hidePageLoading();
        });
        
        // Función para abrir el modal de perfil (mantenida por compatibilidad)
        function openProfileModal() {
            showToast('Funcionalidad de perfil en desarrollo', 'info');
        }
    </script>
    
    <!-- Estilos adicionales para animaciones -->
    <style>
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .toast-notification {
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border: none;
            border-radius: 10px;
        }
    </style>
        
        // Manejar cambio de contraseña
        document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const currentPassword = document.getElementById('current_password').value;
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            // Validaciones
            if (newPassword.length < 8) {
                alert('La nueva contraseña debe tener al menos 8 caracteres');
                return;
            }
            
            if (newPassword !== confirmPassword) {
                alert('Las contraseñas no coinciden');
                return;
            }
            
            // Enviar cambio de contraseña
            const formData = new FormData();
            formData.append('current_password', currentPassword);
            formData.append('new_password', newPassword);
            formData.append('confirm_password', confirmPassword);
            
            fetch('<?= BASE_URL ?>/test_ajax.php?endpoint=change_password', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Contraseña cambiada exitosamente');
                    document.getElementById('changePasswordForm').reset();
                    bootstrap.Modal.getInstance(document.getElementById('profileModal')).hide();
                } else {
                    alert('Error al cambiar la contraseña: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error de conexión al cambiar la contraseña');
            });
        });
        
        // Función auxiliar para formatear fechas
        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('es-ES', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }
    </script>
</body>
</html>