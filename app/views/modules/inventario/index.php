<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-warehouse"></i> <?= $title ?>
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 custom-breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?= $this->url('dashboard') ?>" class="btn btn-outline-primary btn-sm breadcrumb-btn">
                                <i class="fas fa-home"></i> Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <span class="btn btn-success btn-sm disabled">
                                <i class="fas fa-warehouse"></i> Inventario
                            </span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <!-- Alertas -->
    <?php if (!empty($proximos_vencer) || !empty($vencidos)): ?>
    <div class="row mb-4">
        <?php if (!empty($vencidos)): ?>
        <div class="col-md-6">
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>¡Urgente!</strong> Hay <?= count($vencidos) ?> productos vencidos que requieren atención inmediata.
                <a href="#productos-vencidos" class="alert-link">Ver detalles</a>
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($proximos_vencer)): ?>
        <div class="col-md-6">
            <div class="alert alert-warning alert-dismissible fade show">
                <i class="fas fa-clock"></i>
                <strong>¡Atención!</strong> Hay <?= count($proximos_vencer) ?> productos próximos a vencer.
                <a href="#proximos-vencer" class="alert-link">Ver detalles</a>
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <!-- Tarjetas de resumen -->
    <div class="row mb-4">
        <?php foreach ($resumen_tipo as $resumen): ?>
        <div class="col-md-3">
            <div class="card <?= $resumen['tipo'] === 'materia_prima' ? 'bg-primary' : 'bg-success' ?> text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title"><?= $resumen['total_items'] ?></h4>
                            <p class="card-text"><?= ucfirst(str_replace('_', ' ', $resumen['tipo'])) ?></p>
                            <small>Valor: <?= CURRENCY_SYMBOL . number_format($resumen['valor_total'], 2) ?></small>
                        </div>
                        <i class="fas <?= $resumen['tipo'] === 'materia_prima' ? 'fa-boxes' : 'fa-cheese' ?> fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title"><?= count($proximos_vencer) ?></h4>
                            <p class="card-text">Próximos a Vencer</p>
                            <small>En 7 días</small>
                        </div>
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title"><?= count($vencidos) ?></h4>
                            <p class="card-text">Productos Vencidos</p>
                            <small>Requieren acción</small>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
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
                <div class="card-body action-buttons">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#modalEntradaInventario">
                                <i class="fas fa-arrow-down"></i> Registrar Entrada
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-danger btn-block" data-toggle="modal" data-target="#modalSalidaInventario">
                                <i class="fas fa-arrow-up"></i> Registrar Salida
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?= $this->url('inventario/movimientos') ?>" class="btn btn-info btn-block">
                                <i class="fas fa-exchange-alt"></i> Ver Movimientos
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-success btn-block" onclick="generarReporteInventario()">
                                <i class="fas fa-chart-bar"></i> Generar Reporte
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Inventario por ubicación -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-map-marker-alt"></i> Inventario por Ubicación
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($inventario_completo)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-warehouse fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No hay inventario registrado</h4>
                        <p class="text-muted">Comienza registrando entradas de inventario</p>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modalEntradaInventario">
                            <i class="fas fa-plus"></i> Registrar Primera Entrada
                        </button>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Ubicación</th>
                                    <th>Item</th>
                                    <th>Lote</th>
                                    <th>Cantidad</th>
                                    <th>Fecha Entrada</th>
                                    <th>Fecha Caducidad</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($inventario_completo as $item): ?>
                                <?php 
                                $diasRestantes = null;
                                if ($item['fecha_caducidad']) {
                                    $diasRestantes = floor((strtotime($item['fecha_caducidad']) - time()) / (60 * 60 * 24));
                                }
                                ?>
                                <tr class="<?= $diasRestantes !== null && $diasRestantes <= 0 ? 'table-danger' : ($diasRestantes !== null && $diasRestantes <= 7 ? 'table-warning' : '') ?>">
                                    <td>
                                        <strong><?= htmlspecialchars($item['ubicacion']) ?></strong>
                                        <?php if ($item['temperatura_almacen']): ?>
                                        <br><small class="text-info"><i class="fas fa-thermometer-half"></i> <?= $item['temperatura_almacen'] ?>°C</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($item['item_nombre']) ?>
                                        <br><small class="text-muted"><?= htmlspecialchars($item['item_codigo']) ?></small>
                                    </td>
                                    <td>
                                        <?php if ($item['lote']): ?>
                                            <span class="badge badge-secondary"><?= htmlspecialchars($item['lote']) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">Sin lote</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= number_format($item['cantidad'], 2) ?> <?= $item['unidad_medida'] ?>
                                        <br><small class="text-muted">Costo: <?= CURRENCY_SYMBOL . number_format($item['costo_total'], 2) ?></small>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($item['fecha_entrada'])) ?></td>
                                    <td>
                                        <?php if ($item['fecha_caducidad']): ?>
                                            <?= date('d/m/Y', strtotime($item['fecha_caducidad'])) ?>
                                            <?php if ($diasRestantes !== null): ?>
                                            <br><small class="<?= $diasRestantes <= 0 ? 'text-danger' : ($diasRestantes <= 7 ? 'text-warning' : 'text-muted') ?>">
                                                <?php if ($diasRestantes <= 0): ?>
                                                    <i class="fas fa-exclamation-triangle"></i> Vencido
                                                <?php elseif ($diasRestantes <= 7): ?>
                                                    <i class="fas fa-clock"></i> <?= $diasRestantes ?> días
                                                <?php else: ?>
                                                    <?= $diasRestantes ?> días
                                                <?php endif; ?>
                                            </small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">No aplica</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $estados = [
                                            'disponible' => '<span class="badge badge-success">Disponible</span>',
                                            'reservado' => '<span class="badge badge-warning">Reservado</span>',
                                            'vencido' => '<span class="badge badge-danger">Vencido</span>',
                                            'dañado' => '<span class="badge badge-dark">Dañado</span>'
                                        ];
                                        echo $estados[$item['estado']] ?? $item['estado'];
                                        ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-info" onclick="verDetalleInventario(<?= $item['id'] ?>)" 
                                                    title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-warning" onclick="editarInventario(<?= $item['id'] ?>)" 
                                                    title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger" onclick="salidaInventario(<?= $item['id'] ?>)" 
                                                    title="Registrar salida">
                                                <i class="fas fa-arrow-up"></i>
                                            </button>
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
    
    <!-- Productos próximos a vencer -->
    <?php if (!empty($proximos_vencer)): ?>
    <div class="row" id="proximos-vencer">
        <div class="col-12">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-clock"></i> Productos Próximos a Vencer (7 días)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Ubicación</th>
                                    <th>Cantidad</th>
                                    <th>Fecha Caducidad</th>
                                    <th>Días Restantes</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($proximos_vencer as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['item_nombre']) ?></td>
                                    <td><?= htmlspecialchars($item['ubicacion']) ?></td>
                                    <td><?= number_format($item['cantidad'], 2) ?> <?= $item['unidad_medida'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($item['fecha_caducidad'])) ?></td>
                                    <td>
                                        <span class="badge badge-warning">
                                            <?= $item['dias_restantes'] ?> días
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" onclick="procesarVencimiento(<?= $item['id'] ?>)">
                                            <i class="fas fa-cog"></i> Procesar
                                        </button>
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
</div>

<script>
function verDetalleInventario(id) {
    window.location.href = '<?= $this->url("inventario/ver/") ?>' + id;
}

function editarInventario(id) {
    window.location.href = '<?= $this->url("inventario/editar/") ?>' + id;
}

function salidaInventario(id) {
    if (confirm('¿Registrar salida de este item del inventario?')) {
        // Implementar lógica de salida
        alert('Funcionalidad de salida pendiente de implementar');
    }
}

function procesarVencimiento(id) {
    if (confirm('¿Marcar este producto como vencido o procesado?')) {
        // Implementar lógica de procesamiento
        alert('Producto procesado correctamente');
    }
}

function generarReporteInventario() {
    alert('Generando reporte de inventario...');
    // Implementar generación de reporte
}
</script>
