<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-shopping-cart"></i> <?= $title ?>
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?= $this->url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Ventas</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <!-- Tarjetas de resumen -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title"><?= count($ordenes_pendientes) ?></h4>
                            <p class="card-text">Órdenes Pendientes</p>
                        </div>
                        <i class="fas fa-clipboard-list fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title"><?= count($ordenes_proceso) ?></h4>
                            <p class="card-text">En Proceso</p>
                        </div>
                        <i class="fas fa-cogs fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title"><?= count($clientes_activos) ?></h4>
                            <p class="card-text">Clientes Activos</p>
                        </div>
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">
                                <?php
                                $ventasHoy = 0;
                                foreach($ordenes_pendientes as $orden) {
                                    if(date('Y-m-d', strtotime($orden['fecha_orden'])) === date('Y-m-d')) {
                                        $ventasHoy += $orden['total'];
                                    }
                                }
                                echo CURRENCY_SYMBOL . number_format($ventasHoy, 2);
                                ?>
                            </h4>
                            <p class="card-text">Ventas Hoy</p>
                        </div>
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Acciones rápidas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt"></i> Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="<?= $this->url('ventas/nueva_orden') ?>" class="btn btn-primary btn-block">
                                <i class="fas fa-plus"></i> Nueva Orden
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?= $this->url('ventas/clientes') ?>" class="btn btn-info btn-block">
                                <i class="fas fa-users"></i> Gestionar Clientes
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?= $this->url('ventas/ordenes') ?>" class="btn btn-secondary btn-block">
                                <i class="fas fa-list"></i> Todas las Órdenes
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?= $this->url('ventas/facturacion') ?>" class="btn btn-success btn-block">
                                <i class="fas fa-file-invoice"></i> Facturación
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Órdenes pendientes -->
    <?php if (!empty($ordenes_pendientes)): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-circle"></i> Órdenes Pendientes de Procesamiento
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Número de Orden</th>
                                    <th>Cliente</th>
                                    <th>Fecha Orden</th>
                                    <th>Fecha Entrega</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ordenes_pendientes as $orden): ?>
                                <tr>
                                    <td>
                                        <a href="<?= $this->url('ventas/ordenes/ver/' . $orden['id']) ?>">
                                            <?= htmlspecialchars($orden['numero_orden']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($orden['cliente_nombre']) ?>
                                        <br><small class="text-muted"><?= ucfirst(str_replace('_', ' ', $orden['cliente_tipo'])) ?></small>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($orden['fecha_orden'])) ?></td>
                                    <td>
                                        <?php if ($orden['fecha_entrega']): ?>
                                            <?= date('d/m/Y', strtotime($orden['fecha_entrega'])) ?>
                                        <?php else: ?>
                                            <span class="text-muted">Por definir</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= CURRENCY_SYMBOL . number_format($orden['total'], 2) ?></td>
                                    <td>
                                        <?php
                                        $estados = [
                                            'pendiente' => '<span class="badge badge-warning">Pendiente</span>',
                                            'proceso' => '<span class="badge badge-info">En Proceso</span>',
                                            'enviado' => '<span class="badge badge-primary">Enviado</span>',
                                            'entregado' => '<span class="badge badge-success">Entregado</span>',
                                            'cancelado' => '<span class="badge badge-danger">Cancelado</span>'
                                        ];
                                        echo $estados[$orden['estado']] ?? $orden['estado'];
                                        ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= $this->url('ventas/ordenes/ver/' . $orden['id']) ?>" 
                                               class="btn btn-info" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button class="btn btn-warning" onclick="editarOrden(<?= $orden['id'] ?>)" 
                                                    title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-success" onclick="procesarOrden(<?= $orden['id'] ?>)" 
                                                    title="Procesar">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Clientes más importantes -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-star"></i> Clientes Principales
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($clientes_activos)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No hay clientes registrados</h4>
                        <p class="text-muted">Comienza registrando tus primeros clientes</p>
                        <a href="<?= $this->url('ventas/clientes') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Registrar Cliente
                        </a>
                    </div>
                    <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach (array_slice($clientes_activos, 0, 5) as $cliente): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1"><?= htmlspecialchars($cliente['nombre']) ?></h6>
                                <small class="text-muted"><?= ucfirst(str_replace('_', ' ', $cliente['tipo'])) ?></small>
                            </div>
                            <div class="text-right">
                                <span class="badge badge-primary"><?= htmlspecialchars($cliente['codigo']) ?></span>
                                <br><small class="text-muted">Límite: <?= CURRENCY_SYMBOL . number_format($cliente['credito_limite'], 2) ?></small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="<?= $this->url('ventas/clientes') ?>" class="btn btn-outline-primary">
                            <i class="fas fa-list"></i> Ver Todos los Clientes
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie"></i> Resumen de Ventas
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="ventasChart" width="400" height="200"></canvas>
                    <div class="mt-3">
                        <div class="row text-center">
                            <div class="col-4">
                                <h6 class="text-primary">Pendientes</h6>
                                <h4><?= count($ordenes_pendientes) ?></h4>
                            </div>
                            <div class="col-4">
                                <h6 class="text-warning">En Proceso</h6>
                                <h4><?= count($ordenes_proceso) ?></h4>
                            </div>
                            <div class="col-4">
                                <h6 class="text-success">Este Mes</h6>
                                <h4>
                                    <?php
                                    $ventasMes = 0;
                                    foreach($ordenes_pendientes as $orden) {
                                        if(date('Y-m', strtotime($orden['fecha_orden'])) === date('Y-m')) {
                                            $ventasMes++;
                                        }
                                    }
                                    echo $ventasMes;
                                    ?>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function editarOrden(id) {
    window.location.href = '<?= $this->url("ventas/ordenes/editar/") ?>' + id;
}

function procesarOrden(id) {
    if (confirm('¿Está seguro de procesar esta orden?')) {
        // Aquí iría la lógica para procesar la orden
        alert('Orden procesada exitosamente');
    }
}
</script>
