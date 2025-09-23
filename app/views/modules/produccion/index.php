<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-industry"></i> <?= $title ?>
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
                                <i class="fas fa-industry"></i> Producción
                            </span>
                        </li>
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
                            <h4 class="card-title"><?= count($lotes_programados) ?></h4>
                            <p class="card-text">Lotes Programados</p>
                        </div>
                        <i class="fas fa-calendar-alt fa-2x"></i>
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
                            <p class="card-text">En Proceso</p>
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
                            <h4 class="card-title"><?= count($lotes_en_maduracion) ?></h4>
                            <p class="card-text">En Maduración</p>
                        </div>
                        <i class="fas fa-hourglass-half fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title"><?= count($recetas_activas) ?></h4>
                            <p class="card-text">Recetas Activas</p>
                        </div>
                        <i class="fas fa-book fa-2x"></i>
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
                            <a href="<?= $this->url('produccion/lotes/crear') ?>" class="btn btn-primary btn-block">
                                <i class="fas fa-plus"></i> Crear Lote
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?= $this->url('produccion/recetas') ?>" class="btn btn-info btn-block">
                                <i class="fas fa-book"></i> Ver Recetas
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?= $this->url('produccion/lotes') ?>" class="btn btn-secondary btn-block">
                                <i class="fas fa-list"></i> Todos los Lotes
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?= $this->url('calidad/analisis') ?>" class="btn btn-success btn-block">
                                <i class="fas fa-microscope"></i> Control Calidad
                            </a>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-3 mb-2">
                            <a href="<?= $this->url('produccion/recetas/crear') ?>" class="btn btn-outline-primary btn-block">
                                <i class="fas fa-plus-circle"></i> Nueva Receta
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-outline-warning btn-block" onclick="checkRawMaterials()">
                                <i class="fas fa-warehouse"></i> Check Materias Primas
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?= $this->url('reportes') ?>" class="btn btn-outline-info btn-block">
                                <i class="fas fa-chart-line"></i> Reportes Producción
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-outline-success btn-block" onclick="performBatchQualityCheck()">
                                <i class="fas fa-clipboard-check"></i> Control Lotes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Lotes en proceso -->
    <?php if (!empty($lotes_en_proceso)): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs"></i> Lotes en Proceso
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Número de Lote</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Fecha Inicio</th>
                                    <th>Operador</th>
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
                                    <td><?= number_format($lote['cantidad_programada'], 2) ?> kg</td>
                                    <td><?= date('d/m/Y H:i', strtotime($lote['fecha_inicio'])) ?></td>
                                    <td><?= htmlspecialchars($lote['operador_nombre'] . ' ' . $lote['operador_apellidos']) ?></td>
                                    <td>
                                        <a href="<?= $this->url('produccion/lotes/ver/' . $lote['id']) ?>" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Ver
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
    
    <!-- Lotes en maduración -->
    <?php if (!empty($lotes_en_maduracion)): ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-hourglass-half"></i> Lotes en Maduración
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Número de Lote</th>
                                    <th>Producto</th>
                                    <th>Días Transcurridos</th>
                                    <th>Días Restantes</th>
                                    <th>Progreso</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lotes_en_maduracion as $lote): ?>
                                <?php 
                                    $progreso = ($lote['dias_maduracion'] / $lote['tiempo_maduracion']) * 100;
                                    $progreso = min(100, max(0, $progreso));
                                ?>
                                <tr>
                                    <td>
                                        <a href="<?= $this->url('produccion/lotes/ver/' . $lote['id']) ?>">
                                            <?= htmlspecialchars($lote['numero_lote']) ?>
                                        </a>
                                    </td>
                                    <td><?= htmlspecialchars($lote['producto_nombre']) ?></td>
                                    <td><?= $lote['dias_maduracion'] ?> días</td>
                                    <td><?= max(0, $lote['dias_restantes']) ?> días</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: <?= $progreso ?>%"
                                                 aria-valuenow="<?= $progreso ?>" 
                                                 aria-valuemin="0" aria-valuemax="100">
                                                <?= round($progreso) ?>%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="<?= $this->url('produccion/lotes/ver/' . $lote['id']) ?>" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Ver
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
// Funciones para acciones rápidas de producción
function checkRawMaterials() {
    // Verificar el estado de las materias primas críticas
    fetch('<?= BASE_URL ?>/ajax/materias-primas/check-raw-materials')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.data.critical_low.length > 0) {
                    let message = `¡Atención! Materias primas con stock crítico:\n\n`;
                    data.data.critical_low.forEach(item => {
                        message += `• ${item.nombre}: ${item.stock_actual} ${item.unidad_medida} (Mín: ${item.stock_minimo})\n`;
                    });
                    message += `\n¿Desea ir al módulo de Materias Primas?`;
                    
                    if (confirm(message)) {
                        window.location.href = '<?= BASE_URL ?>/materias-primas';
                    }
                } else {
                    alert('✓ Todas las materias primas tienen stock suficiente para la producción.');
                }
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error al verificar materias primas. Por favor, verifique manualmente.');
            console.error('Error:', error);
        });
}

function performBatchQualityCheck() {
    // Realizar verificación rápida de calidad de lotes activos
    fetch('<?= BASE_URL ?>/ajax/produccion/batch-quality-check')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.data.needs_attention) {
                    let message = 'Lotes que requieren atención:\n\n';
                    data.data.attention_items.forEach(item => {
                        message += `• ${item}\n`;
                    });
                    message += '\n¿Desea ir al módulo de Calidad?';
                    
                    if (confirm(message)) {
                        window.location.href = '<?= BASE_URL ?>/calidad';
                    }
                } else {
                    alert('✓ Todos los lotes están en estado normal.');
                }
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error al verificar lotes. Por favor, verifique manualmente.');
            console.error('Error:', error);
        });
}
</script>