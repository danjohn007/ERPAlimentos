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
        }
        
        .stats-icon {
            font-size: 2rem;
            opacity: 0.8;
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
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i>
                            <?= Auth::getUserName() ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= $this->url('admin/usuarios') ?>">
                                <i class="fas fa-user-cog"></i> Mi Perfil
                            </a></li>
                            <li><a class="dropdown-item" href="<?= $this->url('admin/configuracion') ?>">
                                <i class="fas fa-cog"></i> Configuración
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= $this->url('logout') ?>">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </a></li>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Activar link del menú actual
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            
            navLinks.forEach(link => {
                if (currentPath.includes(link.getAttribute('href'))) {
                    link.classList.add('active');
                }
            });
        });
        
        // Confirmación para eliminar
        function confirmarEliminacion(mensaje = '¿Está seguro de que desea eliminar este elemento?') {
            return confirm(mensaje);
        }
        
        // Loading state para formularios
        function showLoading(button) {
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
        }
        
        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                if (alert.classList.contains('alert-success') || alert.classList.contains('alert-info')) {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }
            });
        }, 5000);
    </script>
</body>
</html>