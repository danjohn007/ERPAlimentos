<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-microscope"></i> <?= $title ?>
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?= $this->url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Control de Calidad</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <!-- Alertas de productos próximos a vencer -->
    <?php if (!empty($productos_vencer)): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-warning alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>¡Atención!</strong> Hay <?= count($productos_vencer) ?> productos próximos a vencer que requieren análisis.
                <a href="#productos-vencer" class="alert-link">Ver detalles</a>
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Tarjetas de estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">
                                <?php
                                $conformes = 0;
                                foreach($estadisticas as $est) {
                                    if($est['resultado'] === 'conforme') $conformes = $est['total'];
                                }
                                echo $conformes;
                                ?>
                            </h4>
                            <p class="card-text">Análisis Conformes</p>
                        </div>
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">
                                <?php
                                $noConformes = 0;
                                foreach($estadisticas as $est) {
                                    if($est['resultado'] === 'no_conforme') $noConformes = $est['total'];
                                }
                                echo $noConformes;
                                ?>
                            </h4>
                            <p class="card-text">No Conformes</p>
                        </div>
                        <i class="fas fa-times-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title"><?= count($lotes_en_proceso) ?></h4>
                            <p class="card-text">Lotes en Proceso</p>
                        </div>
                        <i class="fas fa-cogs fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title"><?= count($productos_vencer) ?></h4>
                            <p class="card-text">Próximos a Vencer</p>
                        </div>
                        <i class="fas fa-clock fa-2x"></i>
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
                            <a href="<?= $this->url('calidad/analisis') ?>" class="btn btn-primary btn-block">
                                <i class="fas fa-plus"></i> Nuevo Análisis
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?= $this->url('calidad/trazabilidad') ?>" class="btn btn-info btn-block">
                                <i class="fas fa-search"></i> Trazabilidad
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-warning btn-block" onclick="verificarCalidadLotes()">
                                <i class="fas fa-shield-alt"></i> Verificar Lotes
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-success btn-block" data-toggle="modal" data-target="#modalReporteCalidad">
                                <i class="fas fa-chart-bar"></i> Generar Reporte
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Análisis recientes -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history"></i> Análisis Recientes
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($analisis_recientes)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-microscope fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No hay análisis registrados</h4>
                        <p class="text-muted">Comienza realizando tu primer análisis de calidad</p>
                        <a href="<?= $this->url('calidad/analisis') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Realizar Primer Análisis
                        </a>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Item/Lote</th>
                                    <th>pH</th>
                                    <th>Humedad</th>
                                    <th>Temperatura</th>
                                    <th>Analista</th>
                                    <th>Resultado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($analisis_recientes as $analisis): ?>
                                <tr>
                                    <td><?= date('d/m/Y H:i', strtotime($analisis['fecha_analisis'])) ?></td>
                                    <td>
                                        <?php
                                        $tipos = [
                                            'materia_prima' => '<span class="badge badge-primary">Materia Prima</span>',
                                            'producto_terminado' => '<span class="badge badge-success">Producto</span>',
                                            'proceso' => '<span class="badge badge-warning">Proceso</span>'
                                        ];
                                        echo $tipos[$analisis['tipo']] ?? $analisis['tipo'];
                                        ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($analisis['item_nombre']) ?>
                                        <?php if ($analisis['numero_lote']): ?>
                                        <br><small class="text-muted">Lote: <?= $analisis['numero_lote'] ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $analisis['ph'] ? number_format($analisis['ph'], 1) : '-' ?></td>
                                    <td><?= $analisis['humedad'] ? number_format($analisis['humedad'], 2) . '%' : '-' ?></td>
                                    <td><?= $analisis['temperatura'] ? number_format($analisis['temperatura'], 1) . '°C' : '-' ?></td>
                                    <td><?= htmlspecialchars($analisis['analista_nombre']) ?></td>
                                    <td>
                                        <?php
                                        $resultados = [
                                            'conforme' => '<span class="badge badge-success">Conforme</span>',
                                            'no_conforme' => '<span class="badge badge-danger">No Conforme</span>',
                                            'requiere_revision' => '<span class="badge badge-warning">Requiere Revisión</span>'
                                        ];
                                        echo $resultados[$analisis['resultado']] ?? $analisis['resultado'];
                                        ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-info" onclick="verAnalisis(<?= $analisis['id'] ?>)" 
                                                    title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-warning" onclick="editarAnalisis(<?= $analisis['id'] ?>)" 
                                                    title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="<?= $this->url('calidad/analisis') ?>" class="btn btn-primary">
                            <i class="fas fa-list"></i> Ver Todos los Análisis
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Lotes que requieren análisis -->
    <?php if (!empty($lotes_en_proceso)): ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-circle"></i> Lotes que Requieren Análisis
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Número de Lote</th>
                                    <th>Producto</th>
                                    <th>Fecha Inicio</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lotes_en_proceso as $lote): ?>
                                <tr>
                                    <td>
                                        <a href="<?= $this->url('produccion/lotes/ver/' . $lote['id']) ?>">
                                            <?= htmlspecialchars($lote['numero_lote']) ?>
                                        </a>
                                    </td>
                                    <td><?= htmlspecialchars($lote['producto_nombre']) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($lote['fecha_inicio'])) ?></td>
                                    <td>
                                        <span class="badge badge-warning">En Proceso</span>
                                    </td>
                                    <td>
                                        <a href="<?= $this->url('calidad/analisis?lote=' . $lote['id']) ?>" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-microscope"></i> Analizar
                                        </a>
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
function verAnalisis(id) {
    // Implementar vista detallada del análisis
    window.location.href = '<?= $this->url("calidad/analisis/ver/") ?>' + id;
}

function editarAnalisis(id) {
    // Implementar edición del análisis
    window.location.href = '<?= $this->url("calidad/analisis/editar/") ?>' + id;
}

function verificarCalidadLotes() {
    // Implementar verificación automática de calidad
    alert('Verificando calidad de lotes en proceso...');
}
</script>
