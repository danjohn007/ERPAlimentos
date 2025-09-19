<?php include_once VIEWS_PATH . "layouts/header.php"; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2><i class="fas fa-shopping-cart"></i> <?php echo $title; ?></h2>
            
            <!-- Resumen de Compras -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Órdenes Pendientes</h5>
                                    <h3><?php echo count($ordenes_pendientes); ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-file-alt fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>En Proceso</h5>
                                    <h3><?php echo count($ordenes_en_proceso); ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Por Recibir</h5>
                                    <h3><?php echo count($ordenes_por_recibir); ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-truck fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Proveedores</h5>
                                    <h3><?php echo count($proveedores_activos); ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Acciones Rápidas -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-bolt"></i> Acciones Rápidas</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2 text-center mb-3">
                                    <a href="/compras/nueva_orden" class="btn btn-primary btn-lg btn-block">
                                        <i class="fas fa-plus"></i><br>
                                        Nueva Orden de Compra
                                    </a>
                                </div>
                                <div class="col-md-2 text-center mb-3">
                                    <a href="/compras/ordenes" class="btn btn-info btn-lg btn-block">
                                        <i class="fas fa-list"></i><br>
                                        Ver Todas las Órdenes
                                    </a>
                                </div>
                                <div class="col-md-2 text-center mb-3">
                                    <a href="/compras/recepcion" class="btn btn-success btn-lg btn-block">
                                        <i class="fas fa-inbox"></i><br>
                                        Recepción de Mercancía
                                    </a>
                                </div>
                                <div class="col-md-2 text-center mb-3">
                                    <a href="/compras/proveedores" class="btn btn-secondary btn-lg btn-block">
                                        <i class="fas fa-building"></i><br>
                                        Gestión de Proveedores
                                    </a>
                                </div>
                                <div class="col-md-2 text-center mb-3">
                                    <a href="/compras/inventario_materias_primas" class="btn btn-warning btn-lg btn-block">
                                        <i class="fas fa-boxes"></i><br>
                                        Inventario de Materias
                                    </a>
                                </div>
                                <div class="col-md-2 text-center mb-3">
                                    <a href="/compras/reportes" class="btn btn-dark btn-lg btn-block">
                                        <i class="fas fa-chart-bar"></i><br>
                                        Reportes de Compras
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 text-center mb-3">
                                    <a href="/compras/nuevo_proveedor" class="btn btn-outline-primary btn-lg btn-block">
                                        <i class="fas fa-user-plus"></i><br>
                                        Nuevo Proveedor
                                    </a>
                                </div>
                                <div class="col-md-3 text-center mb-3">
                                    <button class="btn btn-outline-success btn-lg btn-block" onclick="generarRequisicion()">
                                        <i class="fas fa-file-export"></i><br>
                                        Generar Requisición
                                    </button>
                                </div>
                                <div class="col-md-3 text-center mb-3">
                                    <button class="btn btn-outline-info btn-lg btn-block" onclick="consultarPrecios()">
                                        <i class="fas fa-search-dollar"></i><br>
                                        Consultar Precios
                                    </button>
                                </div>
                                <div class="col-md-3 text-center mb-3">
                                    <button class="btn btn-outline-warning btn-lg btn-block" onclick="alertasInventario()">
                                        <i class="fas fa-exclamation-triangle"></i><br>
                                        Alertas de Inventario
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Órdenes Recientes -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-file-alt"></i> Órdenes Pendientes</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($ordenes_pendientes)): ?>
                                <p class="text-muted">No hay órdenes pendientes.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Número</th>
                                                <th>Proveedor</th>
                                                <th>Total</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($ordenes_pendientes as $orden): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($orden['numero_orden']); ?></td>
                                                    <td><?php echo htmlspecialchars($orden['proveedor_nombre']); ?></td>
                                                    <td>$<?php echo number_format($orden['total'], 2); ?></td>
                                                    <td>
                                                        <a href="/compras/ver_orden/<?php echo $orden['id']; ?>" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
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
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-truck"></i> Por Recibir</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($ordenes_por_recibir)): ?>
                                <p class="text-muted">No hay órdenes por recibir.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Número</th>
                                                <th>Proveedor</th>
                                                <th>Fecha Esperada</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($ordenes_por_recibir as $orden): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($orden['numero_orden']); ?></td>
                                                    <td><?php echo htmlspecialchars($orden['proveedor_nombre']); ?></td>
                                                    <td><?php echo date('d/m/Y', strtotime($orden['fecha_entrega_esperada'])); ?></td>
                                                    <td>
                                                        <a href="/compras/ver_orden/<?php echo $orden['id']; ?>" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
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
    </div>
</div>

<script>
function generarRequisicion() {
    alert('Funcionalidad de Generar Requisición: Esta función permitirá crear automáticamente órdenes de compra basadas en niveles mínimos de inventario.');
    // En una implementación completa, esto abriría un modal o redireccionaría a una página específica
}

function consultarPrecios() {
    alert('Funcionalidad de Consultar Precios: Esta función permitirá comparar precios de diferentes proveedores para una materia prima específica.');
    // En una implementación completa, esto abriría un modal de búsqueda
}

function alertasInventario() {
    // Obtener alertas de inventario vía AJAX
    fetch('/compras/alertas_inventario')
        .then(response => response.json())
        .then(data => {
            if (data.alertas && data.alertas.length > 0) {
                let mensaje = 'Alertas de Inventario:\n\n';
                data.alertas.forEach(alerta => {
                    mensaje += `• ${alerta.materia_prima}: Stock actual ${alerta.stock_actual}, Mínimo ${alerta.stock_minimo}\n`;
                });
                alert(mensaje);
            } else {
                alert('¡Excelente! No hay alertas de inventario en este momento.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('No se pudieron cargar las alertas de inventario.');
        });
}
</script>

<?php include_once VIEWS_PATH . "layouts/footer.php"; ?>
