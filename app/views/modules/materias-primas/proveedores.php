<?php include_once VIEWS_PATH . "layouts/header.php"; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-truck"></i> <?= $title ?>
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 custom-breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?= $this->url('dashboard') ?>" class="btn btn-outline-primary btn-sm breadcrumb-btn">
                                <i class="fas fa-home"></i> Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?= $this->url('materias-primas') ?>" class="btn btn-outline-info btn-sm breadcrumb-btn">
                                <i class="fas fa-seedling"></i> Materias Primas
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <span class="btn btn-success btn-sm disabled">
                                <i class="fas fa-truck"></i> Proveedores
                            </span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <!-- Estadísticas de Proveedores -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title"><?= count($proveedores) ?></h4>
                            <p class="card-text">Total Proveedores</p>
                        </div>
                        <i class="fas fa-truck fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <?php 
                            $activos = array_filter($proveedores, function($p) { return $p['estado'] === 'activo'; });
                            ?>
                            <h4 class="card-title"><?= count($activos) ?></h4>
                            <p class="card-text">Proveedores Activos</p>
                        </div>
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <?php 
                            $certificados = array_filter($proveedores, function($p) { 
                                return !empty($p['certificaciones']); 
                            });
                            ?>
                            <h4 class="card-title"><?= count($certificados) ?></h4>
                            <p class="card-text">Certificados</p>
                        </div>
                        <i class="fas fa-certificate fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <?php 
                            $tipos = array_unique(array_column($proveedores, 'tipo'));
                            ?>
                            <h4 class="card-title"><?= count($tipos) ?></h4>
                            <p class="card-text">Tipos de Suministro</p>
                        </div>
                        <i class="fas fa-tags fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Acciones Rápidas para Proveedores -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt"></i> Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body action-buttons">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="<?= $this->url('compras/nuevo_proveedor') ?>" class="btn btn-primary btn-block">
                                <i class="fas fa-plus"></i> Nuevo Proveedor
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-info btn-block" onclick="evaluarProveedores()">
                                <i class="fas fa-star"></i> Evaluar Proveedores
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-success btn-block" onclick="generarReporteProveedores()">
                                <i class="fas fa-chart-line"></i> Reporte Desempeño
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-warning btn-block" onclick="verificarCertificaciones()">
                                <i class="fas fa-shield-alt"></i> Verificar Certificaciones
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-filter"></i> Filtros y Búsqueda
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="filtro-tipo">Tipo de Proveedor</label>
                            <select class="form-control" id="filtro-tipo" onchange="aplicarFiltros()">
                                <option value="">Todos los tipos</option>
                                <option value="leche">Leche</option>
                                <option value="insumos">Insumos</option>
                                <option value="empaques">Empaques</option>
                                <option value="servicios">Servicios</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filtro-estado">Estado</label>
                            <select class="form-control" id="filtro-estado" onchange="aplicarFiltros()">
                                <option value="">Todos los estados</option>
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filtro-certificacion">Certificación</label>
                            <select class="form-control" id="filtro-certificacion" onchange="aplicarFiltros()">
                                <option value="">Todos</option>
                                <option value="certificado">Con Certificación</option>
                                <option value="sin_certificacion">Sin Certificación</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="buscar-proveedor">Buscar</label>
                            <input type="text" class="form-control" id="buscar-proveedor" 
                                   placeholder="Nombre, código o contacto..." onkeyup="aplicarFiltros()">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Lista de Proveedores -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> Lista de Proveedores
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($proveedores)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No hay proveedores registrados</h4>
                        <p class="text-muted">Comience registrando sus primeros proveedores</p>
                        <a href="<?= $this->url('compras/nuevo_proveedor') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Registrar Primer Proveedor
                        </a>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tabla-proveedores">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Contacto</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Certificaciones</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($proveedores as $proveedor): ?>
                                <tr data-tipo="<?= $proveedor['tipo'] ?>" 
                                    data-estado="<?= $proveedor['estado'] ?>"
                                    data-certificacion="<?= !empty($proveedor['certificaciones']) ? 'certificado' : 'sin_certificacion' ?>"
                                    data-nombre="<?= strtolower($proveedor['nombre'] . ' ' . $proveedor['codigo'] . ' ' . $proveedor['contacto']) ?>">
                                    <td>
                                        <code><?= htmlspecialchars($proveedor['codigo']) ?></code>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($proveedor['nombre']) ?></strong>
                                    </td>
                                    <td>
                                        <?php 
                                        $tipos = [
                                            'leche' => '<span class="badge badge-primary text-white">Leche</span>',
                                            'insumos' => '<span class="badge badge-secondary text-white">Insumos</span>',
                                            'empaques' => '<span class="badge badge-info text-white">Empaques</span>',
                                            'servicios' => '<span class="badge badge-warning text-dark">Servicios</span>'
                                        ];
                                        echo $tipos[$proveedor['tipo']] ?? '<span class="badge badge-light text-dark">Otro</span>';
                                        ?>
                                    </td>
                                    <td><?= htmlspecialchars($proveedor['contacto'] ?? '') ?></td>
                                    <td>
                                        <?php if ($proveedor['telefono']): ?>
                                            <a href="tel:<?= $proveedor['telefono'] ?>" class="text-decoration-none">
                                                <?= htmlspecialchars($proveedor['telefono']) ?>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($proveedor['email']): ?>
                                            <a href="mailto:<?= $proveedor['email'] ?>" class="text-decoration-none">
                                                <?= htmlspecialchars($proveedor['email']) ?>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($proveedor['certificaciones']): ?>
                                            <span class="badge badge-success text-white" title="<?= htmlspecialchars($proveedor['certificaciones']) ?>">
                                                <i class="fas fa-certificate"></i> Certificado
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-light text-dark">Sin certificación</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($proveedor['estado'] === 'activo'): ?>
                                            <span class="badge badge-success text-white">Activo</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary text-white">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-info" onclick="verProveedor(<?= $proveedor['id'] ?>)" 
                                                    title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-warning" onclick="editarProveedor(<?= $proveedor['id'] ?>)" 
                                                    title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-success" onclick="evaluarProveedor(<?= $proveedor['id'] ?>)" 
                                                    title="Evaluar">
                                                <i class="fas fa-star"></i>
                                            </button>
                                            <?php if ($proveedor['estado'] === 'activo'): ?>
                                                <button class="btn btn-secondary" onclick="desactivarProveedor(<?= $proveedor['id'] ?>)" 
                                                        title="Desactivar">
                                                    <i class="fas fa-pause"></i>
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-success" onclick="activarProveedor(<?= $proveedor['id'] ?>)" 
                                                        title="Activar">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Funciones para la gestión de proveedores
function aplicarFiltros() {
    const tipo = document.getElementById('filtro-tipo').value.toLowerCase();
    const estado = document.getElementById('filtro-estado').value.toLowerCase();
    const certificacion = document.getElementById('filtro-certificacion').value.toLowerCase();
    const busqueda = document.getElementById('buscar-proveedor').value.toLowerCase();
    
    const filas = document.querySelectorAll('#tabla-proveedores tbody tr');
    
    filas.forEach(fila => {
        const tipoFila = fila.getAttribute('data-tipo');
        const estadoFila = fila.getAttribute('data-estado');
        const certificacionFila = fila.getAttribute('data-certificacion');
        const nombreFila = fila.getAttribute('data-nombre');
        
        let mostrar = true;
        
        if (tipo && tipoFila !== tipo) mostrar = false;
        if (estado && estadoFila !== estado) mostrar = false;
        if (certificacion && certificacionFila !== certificacion) mostrar = false;
        if (busqueda && !nombreFila.includes(busqueda)) mostrar = false;
        
        fila.style.display = mostrar ? '' : 'none';
    });
}

function limpiarFiltros() {
    document.getElementById('filtro-tipo').value = '';
    document.getElementById('filtro-estado').value = '';
    document.getElementById('filtro-certificacion').value = '';
    document.getElementById('buscar-proveedor').value = '';
    aplicarFiltros();
}

function evaluarProveedores() {
    const proveedores = <?= json_encode($proveedores) ?>;
    let evaluacion = 'Evaluación Rápida de Proveedores:\n\n';
    
    let certificados = 0;
    let activos = 0;
    let sinContacto = 0;
    
    proveedores.forEach(p => {
        if (p.certificaciones) certificados++;
        if (p.estado === 'activo') activos++;
        if (!p.telefono && !p.email) sinContacto++;
    });
    
    evaluacion += `• Total de proveedores: ${proveedores.length}\n`;
    evaluacion += `• Proveedores activos: ${activos}\n`;
    evaluacion += `• Proveedores certificados: ${certificados}\n`;
    evaluacion += `• Proveedores sin contacto: ${sinContacto}\n\n`;
    
    if (sinContacto > 0) {
        evaluacion += '⚠️ Recomendación: Actualizar información de contacto faltante.\n';
    }
    
    const porcentajeCertificados = (certificados / proveedores.length * 100).toFixed(1);
    evaluacion += `📊 ${porcentajeCertificados}% de proveedores están certificados.`;
    
    alert(evaluacion);
}

function generarReporteProveedores() {
    alert('📊 Generando reporte de desempeño de proveedores...\n\nEsta funcionalidad incluirá:\n• Tiempos de entrega promedio\n• Calidad de productos\n• Cumplimiento de órdenes\n• Análisis de costos\n\n(En desarrollo)');
}

function verificarCertificaciones() {
    const proveedores = <?= json_encode($proveedores) ?>;
    const certificados = proveedores.filter(p => p.certificaciones);
    
    if (certificados.length === 0) {
        alert('No hay proveedores con certificaciones registradas.');
        return;
    }
    
    let mensaje = 'Proveedores con certificaciones:\n\n';
    certificados.forEach(p => {
        mensaje += `• ${p.nombre}: ${p.certificaciones}\n`;
    });
    
    mensaje += '\n¿Desea revisar las fechas de vencimiento de las certificaciones?';
    if (confirm(mensaje)) {
        alert('🔍 Verificación de certificaciones en desarrollo.\n\nIncluirá:\n• Fechas de vencimiento\n• Alertas automáticas\n• Renovaciones pendientes');
    }
}

function verProveedor(id) {
    alert(`Ver detalles del proveedor ID: ${id}`);
}

function editarProveedor(id) {
    window.location.href = `/compras/nuevo_proveedor?edit=${id}`;
}

function evaluarProveedor(id) {
    alert(`Evaluar proveedor ID: ${id}\n\nEsta funcionalidad incluirá:\n• Calificación de calidad\n• Puntualidad de entregas\n• Competitividad de precios\n• Servicio al cliente\n\n(En desarrollo)`);
}

function desactivarProveedor(id) {
    if (confirm('¿Está seguro de que desea desactivar este proveedor?')) {
        alert(`Proveedor ${id} desactivado (funcionalidad en desarrollo)`);
    }
}

function activarProveedor(id) {
    if (confirm('¿Está seguro de que desea activar este proveedor?')) {
        alert(`Proveedor ${id} activado (funcionalidad en desarrollo)`);
    }
}
</script>

<?php include_once VIEWS_PATH . "layouts/footer.php"; ?>
