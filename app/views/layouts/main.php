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
            padding: 12px 15px;
            border-radius: 8px;
            margin: 3px 0;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), transparent);
            transition: width 0.3s ease;
            z-index: -1;
        }
        
        .sidebar .nav-link:hover::before {
            width: 100%;
        }
        
        .sidebar .nav-link:hover {
            background-color: rgba(46, 139, 87, 0.1);
            color: var(--primary-color);
            transform: translateX(3px);
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color), #20704a);
            color: white;
            font-weight: 600;
            border-left: 4px solid var(--secondary-color);
            box-shadow: 0 2px 8px rgba(46, 139, 87, 0.3);
        }
        
        .sidebar .nav-link.active i {
            color: var(--secondary-color);
        }
        
        .sidebar .nav-link.sub-active {
            background-color: rgba(46, 139, 87, 0.15);
            color: var(--primary-color);
            font-weight: 500;
            border-left: 3px solid var(--primary-color);
        }
        
        /* Indicador de sección activa en el sidebar */
        .sidebar {
            position: relative;
        }
        
        .sidebar::before {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            width: 3px;
            height: 100%;
            background: linear-gradient(180deg, var(--primary-color), var(--secondary-color));
            opacity: 0.3;
        }
        
        /* Animación para los iconos */
        .sidebar .nav-link i {
            margin-right: 10px;
            transition: transform 0.3s ease;
            width: 20px;
            text-align: center;
        }
        
        .sidebar .nav-link.active i,
        .sidebar .nav-link:hover i {
            transform: scale(1.1);
        }
        
        /* Breadcrumb personalizado */
        .content-wrapper::before {
            content: '';
            display: block;
            height: 2px;
            background: linear-gradient(90deg, var(--primary-color), transparent);
            margin-bottom: 20px;
        }
        
        /* Estilos personalizados para breadcrumbs estilo botón */
        .custom-breadcrumb {
            background-color: #f8f9fa;
            border-radius: 25px;
            padding: 10px 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .breadcrumb-btn {
            border-radius: 20px !important;
            transition: all 0.3s ease;
            text-decoration: none !important;
            margin: 0 5px;
            font-weight: 500;
            border-width: 2px;
            display: inline-block;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            line-height: 1.5;
            text-align: center;
        }

        .breadcrumb-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            text-decoration: none;
        }

        .btn-outline-primary.breadcrumb-btn:hover {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
        }

        .btn-outline-info.breadcrumb-btn:hover {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: white;
        }
        
        .btn-outline-secondary.breadcrumb-btn:hover {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        .breadcrumb-item::before {
            content: "→";
            color: #6c757d;
            font-weight: bold;
            margin: 0 10px;
            font-size: 1.1rem;
        }

        .breadcrumb-item:first-child::before {
            content: "";
        }

        .breadcrumb-item.active .btn.disabled {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
            opacity: 1;
        }
        
        /* Estilos para botones de acción (btn-block mejorados) */
        .action-buttons .btn-block {
            border-radius: 25px;
            padding: 12px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            border-width: 2px;
            position: relative;
            overflow: hidden;
        }
        
        .action-buttons .btn-block::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        
        .action-buttons .btn-block:hover::before {
            left: 100%;
        }
        
        .action-buttons .btn-block:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }
        
        .action-buttons .btn-primary:hover {
            background: linear-gradient(135deg, #007bff, #0056b3);
        }
        
        .action-buttons .btn-info:hover {
            background: linear-gradient(135deg, #17a2b8, #138496);
        }
        
        .action-buttons .btn-success:hover {
            background: linear-gradient(135deg, #28a745, #1e7e34);
        }
        
        .action-buttons .btn-warning:hover {
            background: linear-gradient(135deg, #ffc107, #e0a800);
        }
        
        .action-buttons .btn-danger:hover {
            background: linear-gradient(135deg, #dc3545, #c82333);
        }
        
        .action-buttons .btn-secondary:hover {
            background: linear-gradient(135deg, #6c757d, #5a6268);
        }
        
        /* Responsive para móviles */
        @media (max-width: 768px) {
            .custom-breadcrumb {
                padding: 8px 15px;
            }
            
            .breadcrumb-btn {
                font-size: 0.8rem;
                padding: 0.25rem 0.5rem;
                margin: 0 2px;
            }
            
            .breadcrumb-btn i {
                display: none;
            }
            
            .action-buttons .btn-block {
                padding: 10px 15px;
                font-size: 0.9rem;
            }
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
                        <a class="nav-link" href="<?= $this->url('dashboard') ?>" data-menu="dashboard">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                        
                        <hr class="my-2">
                        
                        <a class="nav-link" href="<?= $this->url('produccion') ?>" data-menu="produccion">
                            <i class="fas fa-industry"></i> Producción
                        </a>
                        <a class="nav-link ms-3" href="<?= $this->url('produccion/recetas') ?>" data-menu="produccion-recetas">
                            <i class="fas fa-book"></i> Recetas
                        </a>
                        <a class="nav-link ms-3" href="<?= $this->url('produccion/lotes') ?>" data-menu="produccion-lotes">
                            <i class="fas fa-boxes"></i> Lotes
                        </a>
                        
                        <a class="nav-link" href="<?= $this->url('materias-primas') ?>" data-menu="materias-primas">
                            <i class="fas fa-seedling"></i> Materias Primas
                        </a>
                        <a class="nav-link ms-3" href="<?= $this->url('materias-primas/proveedores') ?>" data-menu="materias-primas-proveedores">
                            <i class="fas fa-truck"></i> Proveedores
                        </a>
                        
                        <a class="nav-link" href="<?= $this->url('calidad') ?>" data-menu="calidad">
                            <i class="fas fa-award"></i> Calidad
                        </a>
                        
                        <a class="nav-link" href="<?= $this->url('inventario') ?>" data-menu="inventario">
                            <i class="fas fa-warehouse"></i> Inventario
                        </a>
                        
                        <a class="nav-link" href="<?= $this->url('ventas') ?>" data-menu="ventas">
                            <i class="fas fa-shopping-cart"></i> Ventas
                        </a>
                        <a class="nav-link ms-3" href="<?= $this->url('ventas/clientes') ?>" data-menu="ventas-clientes">
                            <i class="fas fa-users"></i> Clientes
                        </a>
                        
                        <a class="nav-link" href="<?= $this->url('compras') ?>" data-menu="compras">
                            <i class="fas fa-shopping-bag"></i> Compras
                        </a>
                        
                        <a class="nav-link" href="<?= $this->url('finanzas') ?>" data-menu="finanzas">
                            <i class="fas fa-chart-line"></i> Finanzas
                        </a>
                        
                        <a class="nav-link" href="<?= $this->url('rrhh') ?>" data-menu="rrhh">
                            <i class="fas fa-users-cog"></i> RRHH
                        </a>
                        
                        <a class="nav-link" href="<?= $this->url('reportes') ?>" data-menu="reportes">
                            <i class="fas fa-chart-bar"></i> Reportes
                        </a>
                        
                        <?php if (Auth::hasRole('admin')): ?>
                        <hr class="my-2">
                        <a class="nav-link" href="<?= $this->url('admin') ?>" data-menu="admin">
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
        // Activar link del menú actual
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            
            // Limpiar estados activos previos
            navLinks.forEach(link => {
                link.classList.remove('active', 'sub-active');
            });
            
            // Mapeo de rutas a elementos del menú
            const routeMapping = {
                '/dashboard': 'dashboard',
                '/produccion': 'produccion',
                '/produccion/recetas': 'produccion-recetas',
                '/produccion/lotes': 'produccion-lotes',
                '/materias-primas': 'materias-primas',
                '/materias-primas/proveedores': 'materias-primas-proveedores',
                '/calidad': 'calidad',
                '/inventario': 'inventario',
                '/ventas': 'ventas',
                '/ventas/clientes': 'ventas-clientes',
                '/compras': 'compras',
                '/finanzas': 'finanzas',
                '/rrhh': 'rrhh',
                '/reportes': 'reportes',
                '/admin': 'admin'
            };
            
            // Encontrar coincidencia exacta o la más específica
            let activeRoute = null;
            let maxMatch = 0;
            
            for (const [route, id] of Object.entries(routeMapping)) {
                if (currentPath.includes(route) && route.length > maxMatch) {
                    activeRoute = id;
                    maxMatch = route.length;
                }
            }
            
            if (activeRoute) {
                // Activar el enlace específico
                const activeLink = document.querySelector(`[data-menu="${activeRoute}"]`);
                if (activeLink) {
                    activeLink.classList.add('active');
                    
                    // Si es una subsección, también marcar la sección padre
                    const parentSection = activeRoute.split('-')[0];
                    if (activeRoute.includes('-') && parentSection) {
                        const parentLink = document.querySelector(`[data-menu="${parentSection}"]`);
                        if (parentLink && !parentLink.classList.contains('active')) {
                            parentLink.classList.add('sub-active');
                        }
                    }
                }
            }
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
        
        // Función para abrir el modal de perfil
        function openProfileModal() {
            // Obtener datos del usuario actual del servidor
            fetch('<?= BASE_URL ?>/test_ajax.php?endpoint=profile')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Llenar campos del formulario
                        document.getElementById('profile_nombre').value = data.user.nombre || '';
                        document.getElementById('profile_apellidos').value = data.user.apellidos || '';
                        document.getElementById('profile_username').value = data.user.username || '';
                        document.getElementById('profile_email').value = data.user.email || '';
                        document.getElementById('profile_rol').value = data.user.rol || '';
                        document.getElementById('profile_ultimo_acceso').value = data.user.ultimo_acceso || 'Nunca';
                        
                        // Actualizar información del avatar
                        document.getElementById('profile_fullname').textContent = 
                            (data.user.nombre || '') + ' ' + (data.user.apellidos || '');
                        document.getElementById('profile_role_badge').textContent = 
                            (data.user.rol || 'Usuario').charAt(0).toUpperCase() + (data.user.rol || 'Usuario').slice(1);
                        document.getElementById('profile_member_since').textContent = 
                            formatDate(data.user.fecha_creacion);
                        
                        // Mostrar modal
                        const modal = new bootstrap.Modal(document.getElementById('profileModal'));
                        modal.show();
                    } else {
                        alert('Error al cargar los datos del perfil: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error de conexión al cargar el perfil');
                });
        }
        
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