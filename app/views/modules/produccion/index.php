<?php include_once VIEWS_PATH . "layouts/header.php"; ?>

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
    
    <!-- Tarjetas de resumen mejoradas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card card-stats bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title"><?= $lotes_programados_count ?? 0 ?></h4>
                            <p class="card-text">Lotes Programados</p>
                            <small>Para hoy: <?= $lotes_hoy_count ?? 0 ?></small>
                        </div>
                        <i class="fas fa-calendar-alt stats-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card card-stats bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title"><?= $lotes_proceso_count ?? 0 ?></h4>
                            <p class="card-text">En Proceso</p>
                            <small>Activos ahora</small>
                        </div>
                        <i class="fas fa-cogs stats-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card card-stats bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title"><?= $lotes_maduracion_count ?? 0 ?></h4>
                            <p class="card-text">En Maduración</p>
                            <small>Listos: <?= $lotes_listos_count ?? 0 ?></small>
                        </div>
                        <i class="fas fa-hourglass-half stats-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card card-stats bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title"><?= $recetas_count ?? 0 ?></h4>
                            <p class="card-text">Recetas Activas</p>
                            <small>Disponibles</small>
                        </div>
                        <i class="fas fa-book stats-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Acciones rápidas mejoradas -->
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
                            <button class="btn btn-primary btn-block" data-bs-toggle="modal" data-bs-target="#modalCrearLote">
                                <i class="fas fa-plus"></i> Crear Lote Rápido
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-info btn-block" data-bs-toggle="modal" data-bs-target="#modalCrearReceta">
                                <i class="fas fa-book"></i> Nueva Receta
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?= $this->url('produccion/lotes') ?>" class="btn btn-secondary btn-block">
                                <i class="fas fa-list"></i> Gestionar Lotes
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?= $this->url('produccion/recetas') ?>" class="btn btn-success btn-block">
                                <i class="fas fa-recipe"></i> Ver Recetas
                            </a>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-outline-warning btn-block" onclick="verificarMateriasPrimas()">
                                <i class="fas fa-warehouse"></i> Verificar Materias
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-outline-info btn-block" onclick="generarReporteProduccion()">
                                <i class="fas fa-chart-line"></i> Reporte Diario
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-outline-success btn-block" onclick="controlCalidadRapido()">
                                <i class="fas fa-clipboard-check"></i> Control Calidad
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-outline-danger btn-block" onclick="alertasProduccion()">
                                <i class="fas fa-exclamation-triangle"></i> Ver Alertas
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Panel de Producción en Tiempo Real -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-clock"></i> Producción en Tiempo Real
                        <span class="badge bg-success ms-2" id="statusIndicator">En línea</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Lotes Activos Hoy</h6>
                            <div id="lotesActivosHoy" class="mb-3">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded mb-2">
                                    <span><strong>Lote #2024001</strong> - Queso Gouda</span>
                                    <span class="badge bg-warning">En Proceso</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded mb-2">
                                    <span><strong>Lote #2024002</strong> - Queso Cheddar</span>
                                    <span class="badge bg-info">Maduración</span>
                                </div>
                                <div class="text-center">
                                    <button class="btn btn-sm btn-outline-primary" onclick="actualizarLotesActivos()">
                                        <i class="fas fa-sync"></i> Actualizar
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Próximas Tareas</h6>
                            <div id="proximasTareas">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded mb-2">
                                    <span>Control pH Lote #2024001</span>
                                    <span class="text-warning"><small>14:30</small></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded mb-2">
                                    <span>Volteo Lote #2024003</span>
                                    <span class="text-info"><small>16:00</small></span>
                                </div>
                                <div class="text-center">
                                    <button class="btn btn-sm btn-outline-success" onclick="marcarTareaCompleta()">
                                        <i class="fas fa-check"></i> Marcar Completa
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-thermometer-half"></i> Condiciones Ambientales
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border rounded p-2">
                                <h4 class="text-primary mb-1" id="temperatura">18°C</h4>
                                <small class="text-muted">Temperatura</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-2">
                                <h4 class="text-info mb-1" id="humedad">75%</h4>
                                <small class="text-muted">Humedad</small>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-success alert-sm mb-2">
                        <i class="fas fa-check-circle"></i> Condiciones óptimas
                    </div>
                    <button class="btn btn-sm btn-outline-primary btn-block" onclick="actualizarCondiciones()">
                        <i class="fas fa-sync"></i> Actualizar
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Lotes en proceso -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-cogs"></i> Lotes en Proceso
                        </h5>
                        <button class="btn btn-sm btn-primary" onclick="actualizarLotes()">
                            <i class="fas fa-sync"></i> Actualizar
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark" text-bg-dark>
                                <tr>
                                    <th>Número de Lote</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Fecha Inicio</th>
                                    <th>Tiempo Transcurrido</th>
                                    <th>Operador</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tablaLotesProceso">
                                <?php if (!empty($lotes_en_proceso)): ?>
                                    <?php foreach ($lotes_en_proceso as $lote): ?>
                                        <?php
                                        $fecha_inicio = new DateTime($lote['fecha_inicio']);
                                        $ahora = new DateTime();
                                        $tiempo_transcurrido = $fecha_inicio->diff($ahora);
                                        $horas = $tiempo_transcurrido->h + ($tiempo_transcurrido->days * 24);
                                        $minutos = $tiempo_transcurrido->i;
                                        ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($lote['numero_lote']) ?></strong></td>
                                            <td><?= htmlspecialchars($lote['receta_nombre'] ?? 'Sin receta') ?></td>
                                            <td><?= number_format($lote['cantidad_programada'], 1) ?> kg</td>
                                            <td><?= date('d/m/Y H:i', strtotime($lote['fecha_inicio'])) ?></td>
                                            <td>
                                                <span class="badge bg-warning"><?= $horas ?>h <?= $minutos ?>m</span>
                                            </td>
                                            <td><?= htmlspecialchars($lote['operador_nombre'] ?? 'Sin asignar') ?></td>
                                            <td>
                                                <span class="badge bg-warning">
                                                    <?= ucfirst(str_replace('_', ' ', $lote['estado'])) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-info" onclick="verLote('<?= $lote['numero_lote'] ?>')" title="Ver detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-success" onclick="avanzarEtapa('<?= $lote['numero_lote'] ?>')" title="Avanzar etapa">
                                                        <i class="fas fa-arrow-right"></i>
                                                    </button>
                                                    <button class="btn btn-warning" onclick="registrarControl('<?= $lote['numero_lote'] ?>')" title="Control calidad">
                                                        <i class="fas fa-clipboard-check"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">
                                            <i class="fas fa-info-circle"></i> No hay lotes en proceso actualmente
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Lotes en maduración -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-hourglass-half"></i> Lotes en Maduración
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Número de Lote</th>
                                    <th>Producto</th>
                                    <th>Fecha Inicio</th>
                                    <th>Días Transcurridos</th>
                                    <th>Días Restantes</th>
                                    <th>Progreso</th>
                                    <th>Temperatura</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($lotes_en_maduracion)): ?>
                                    <?php foreach ($lotes_en_maduracion as $lote): ?>
                                        <?php
                                        $dias_transcurridos = $lote['dias_maduracion'] ?? 0;
                                        $dias_restantes = $lote['dias_restantes'] ?? 0;
                                        $tiempo_maduracion = $lote['tiempo_maduracion'] ?? 30;
                                        $progreso = $tiempo_maduracion > 0 ? round(($dias_transcurridos / $tiempo_maduracion) * 100, 1) : 0;
                                        ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($lote['numero_lote']) ?></strong></td>
                                            <td><?= htmlspecialchars($lote['producto_nombre'] ?? $lote['receta_nombre'] ?? 'Sin producto') ?></td>
                                            <td><?= date('d/m/Y', strtotime($lote['fecha_fin'] ?? $lote['fecha_inicio'])) ?></td>
                                            <td><?= $dias_transcurridos ?> días</td>
                                            <td><?= max(0, $dias_restantes) ?> días</td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar <?= $progreso > 80 ? 'bg-success' : ($progreso > 50 ? 'bg-info' : 'bg-warning') ?>" 
                                                         role="progressbar" style="width: <?= min(100, $progreso) ?>%" 
                                                         aria-valuenow="<?= $progreso ?>" aria-valuemin="0" aria-valuemax="100">
                                                        <?= $progreso ?>%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">
                                                    <?= $lote['temperatura_proceso'] ?? '12.0' ?>°C
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-info" onclick="verLote('<?= $lote['numero_lote'] ?>')" title="Ver detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-warning" onclick="voltearLote('<?= $lote['numero_lote'] ?>')" title="Voltear">
                                                        <i class="fas fa-redo"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">
                                            <i class="fas fa-info-circle"></i> No hay lotes en proceso de maduración
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recetas Disponibles -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-book"></i> Recetas Disponibles
                        </h5>
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalCrearReceta">
                            <i class="fas fa-plus"></i> Nueva Receta
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php if (!empty($recetas_activas)): ?>
                            <?php foreach ($recetas_activas as $receta): ?>
                                <div class="col-md-4 mb-3">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h6 class="card-title"><?= htmlspecialchars($receta['nombre']) ?></h6>
                                            <p class="card-text text-muted small">
                                                Tiempo: <?= $receta['tiempo_preparacion'] ?? 'N/A' ?> horas<br>
                                                Maduración: <?= $receta['tiempo_maduracion'] ?? 'N/A' ?> días<br>
                                                Rendimiento: <?= $receta['rendimiento_kg_queso'] ?? 'N/A' ?>kg/<?= $receta['rendimiento_litros_leche'] ?? '100' ?>L
                                            </p>
                                            <div class="d-flex justify-content-between">
                                                <button class="btn btn-sm btn-primary" onclick="crearLoteDeReceta(<?= $receta['id'] ?>)">
                                                    <i class="fas fa-play"></i> Usar
                                                </button>
                                                <button class="btn btn-sm btn-outline-info" onclick="verReceta(<?= $receta['id'] ?>)">
                                                    <i class="fas fa-eye"></i> Ver
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12">
                                <div class="alert alert-info text-center">
                                    <i class="fas fa-info-circle"></i> 
                                    No hay recetas activas disponibles. 
                                    <button class="btn btn-sm btn-success ms-2" data-bs-toggle="modal" data-bs-target="#modalCrearReceta">
                                        <i class="fas fa-plus"></i> Crear primera receta
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crear Lote Rápido -->
<div class="modal fade" id="modalCrearLote" tabindex="-1" aria-labelledby="modalCrearLoteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalCrearLoteLabel">
                    <i class="fas fa-plus"></i> Crear Lote de Producción
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formCrearLote">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="receta_id" class="form-label">Receta</label>
                                <select class="form-select" id="receta_id" name="receta_id" required>
                                    <option value="">Seleccionar receta...</option>
                                    <?php if (!empty($recetas_activas)): ?>
                                        <?php foreach ($recetas_activas as $receta): ?>
                                            <option value="<?= $receta['id'] ?>" 
                                                    data-rendimiento="<?= $receta['rendimiento_kg_queso'] ?? 10 ?>"
                                                    data-litros="<?= $receta['rendimiento_litros_leche'] ?? 100 ?>">
                                                <?= htmlspecialchars($receta['nombre']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" disabled>No hay recetas disponibles</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cantidad_leche" class="form-label">Cantidad de Leche (Litros)</label>
                                <input type="number" class="form-control" id="cantidad_leche" name="cantidad_leche" 
                                       placeholder="500" min="100" max="2000" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="operador" class="form-label">Operador Responsable</label>
                                <select class="form-select" id="operador" name="operador" required>
                                    <option value="">Seleccionar operador...</option>
                                    <option value="1">Juan Pérez</option>
                                    <option value="2">María García</option>
                                    <option value="3">Carlos López</option>
                                    <option value="4">Ana Martínez</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_inicio" class="form-label">Fecha y Hora de Inicio</label>
                                <input type="datetime-local" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3" 
                                  placeholder="Observaciones especiales para este lote..."></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Estimación:</strong> Este lote producirá aproximadamente <span id="estimacion">--</span> kg de queso.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary" onclick="guardarLote()">
                    <i class="fas fa-save"></i> Crear Lote
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crear Receta -->
<div class="modal fade" id="modalCrearReceta" tabindex="-1" aria-labelledby="modalCrearRecetaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalCrearRecetaLabel">
                    <i class="fas fa-book"></i> Nueva Receta de Queso
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formCrearReceta">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nombre_receta" class="form-label">Nombre de la Receta</label>
                                        <input type="text" class="form-control" id="nombre_receta" name="nombre_receta" 
                                               placeholder="Ej: Queso Gouda Especial" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tipo_queso" class="form-label">Tipo de Queso</label>
                                        <select class="form-select" id="tipo_queso" name="tipo_queso" required>
                                            <option value="">Seleccionar tipo...</option>
                                            <option value="gouda">Gouda</option>
                                            <option value="cheddar">Cheddar</option>
                                            <option value="manchego">Manchego</option>
                                            <option value="parmesano">Parmesano</option>
                                            <option value="otro">Otro</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="tiempo_proceso" class="form-label">Tiempo de Proceso (horas)</label>
                                        <input type="number" class="form-control" id="tiempo_proceso" name="tiempo_proceso" 
                                               placeholder="6" min="1" max="24" step="0.5" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="tiempo_maduracion" class="form-label">Tiempo Maduración (días)</label>
                                        <input type="number" class="form-control" id="tiempo_maduracion" name="tiempo_maduracion" 
                                               placeholder="30" min="1" max="365" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="rendimiento" class="form-label">Rendimiento (kg por 100L)</label>
                                        <input type="number" class="form-control" id="rendimiento" name="rendimiento" 
                                               placeholder="10" min="5" max="20" step="0.1" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="mb-0">Parámetros de Calidad</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <label for="temperatura_maduracion" class="form-label">Temp. Maduración (°C)</label>
                                        <input type="number" class="form-control form-control-sm" id="temperatura_maduracion" 
                                               name="temperatura_maduracion" placeholder="12" min="8" max="18" required>
                                    </div>
                                    <div class="mb-2">
                                        <label for="humedad_maduracion" class="form-label">Humedad (%)</label>
                                        <input type="number" class="form-control form-control-sm" id="humedad_maduracion" 
                                               name="humedad_maduracion" placeholder="85" min="70" max="95" required>
                                    </div>
                                    <div class="mb-2">
                                        <label for="ph_objetivo" class="form-label">pH Objetivo</label>
                                        <input type="number" class="form-control form-control-sm" id="ph_objetivo" 
                                               name="ph_objetivo" placeholder="6.2" min="4.5" max="7.0" step="0.1" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="ingredientes" class="form-label">Ingredientes y Cantidades</label>
                        <div id="ingredientes-container">
                            <div class="row mb-2 ingrediente-row">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Nombre del ingrediente" name="ingrediente_nombre[]">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" placeholder="Cantidad por 100L" name="ingrediente_cantidad[]">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-outline-success btn-sm" onclick="agregarIngrediente()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="procedimiento" class="form-label">Procedimiento</label>
                        <textarea class="form-control" id="procedimiento" name="procedimiento" rows="4" 
                                  placeholder="Describe paso a paso el procedimiento para elaborar este queso..." required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success" onclick="guardarReceta()">
                    <i class="fas fa-save"></i> Guardar Receta
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Inicializar fecha y hora actual
document.addEventListener('DOMContentLoaded', function() {
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    document.getElementById('fecha_inicio').value = now.toISOString().slice(0, 16);
    
    // Calcular estimación al cambiar receta o cantidad
    document.getElementById('receta_id').addEventListener('change', calcularEstimacion);
    document.getElementById('cantidad_leche').addEventListener('input', calcularEstimacion);
    
    // Actualizar condiciones ambientales cada 30 segundos
    setInterval(actualizarCondiciones, 30000);
});

// Funciones para gestión de lotes
function calcularEstimacion() {
    const recetaSelect = document.getElementById('receta_id');
    const cantidadLeche = document.getElementById('cantidad_leche').value;
    const estimacionElement = document.getElementById('estimacion');
    
    if (recetaSelect && recetaSelect.value && cantidadLeche && estimacionElement) {
        const selectedOption = recetaSelect.options[recetaSelect.selectedIndex];
        const rendimiento = parseFloat(selectedOption.dataset.rendimiento) || 10;
        const litrosBase = parseFloat(selectedOption.dataset.litros) || 100;
        
        const estimacion = ((cantidadLeche / litrosBase) * rendimiento).toFixed(1);
        estimacionElement.textContent = estimacion;
    } else if (estimacionElement) {
        estimacionElement.textContent = '--';
    }
}

function guardarLote() {
    console.log('guardarLote() called');
    const form = document.getElementById('formCrearLote');
    if (!form) {
        console.error('Formulario formCrearLote no encontrado');
        return;
    }
    
    const formData = new FormData(form);
    
    // Log form data for debugging
    console.log('Form data:', Object.fromEntries(formData));
    
    // Validaciones básicas
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Construir URL completa
    const url = '<?= BASE_URL ?>/ajax/produccion/crear-lote';
    console.log('Sending request to:', url);
    
    // Enviar datos al servidor
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            return response.text().then(text => {
                console.error('Response is not JSON:', text);
                throw new Error('Server did not return JSON');
            });
        }
        
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
            alert(`✅ Lote creado exitosamente!\n\nNúmero: ${data.numero_lote}\nReceta: ${data.receta_nombre}\nCantidad: ${formData.get('cantidad_leche')}L\n\nEl lote ha sido agregado a la cola de producción.`);
            
            // Cerrar modal y resetear form
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalCrearLote'));
            modal.hide();
            form.reset();
            
            // Recargar la página para mostrar los nuevos datos
            window.location.reload();
        } else {
            alert('❌ Error al crear el lote: ' + (data.message || 'Error desconocido'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Error de conexión al crear el lote: ' + error.message);
    });
}

function guardarReceta() {
    const form = document.getElementById('formCrearReceta');
    const formData = new FormData(form);
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Recopilar ingredientes
    const ingredientesNombres = form.querySelectorAll('input[name="ingrediente_nombre[]"]');
    const ingredientesCantidades = form.querySelectorAll('input[name="ingrediente_cantidad[]"]');
    const ingredientes = [];
    
    for (let i = 0; i < ingredientesNombres.length; i++) {
        if (ingredientesNombres[i].value && ingredientesCantidades[i].value) {
            ingredientes.push({
                nombre: ingredientesNombres[i].value,
                cantidad: ingredientesCantidades[i].value
            });
        }
    }
    
    formData.append('ingredientes', JSON.stringify(ingredientes));
    
    // Enviar datos al servidor
    fetch('/ajax/crear-receta', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const nombreReceta = document.getElementById('nombre_receta').value;
            alert(`✅ Receta "${nombreReceta}" guardada exitosamente!\n\nLa receta está ahora disponible para crear lotes de producción.`);
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalCrearReceta'));
            modal.hide();
            form.reset();
            
            // Recargar la página para mostrar los nuevos datos
            window.location.reload();
        } else {
            alert('❌ Error al guardar la receta: ' + (data.message || 'Error desconocido'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Error de conexión al guardar la receta');
    });
}

// Funciones para ingredientes dinámicos
function agregarIngrediente() {
    const container = document.getElementById('ingredientes-container');
    const newRow = document.createElement('div');
    newRow.className = 'row mb-2 ingrediente-row';
    newRow.innerHTML = `
        <div class="col-md-6">
            <input type="text" class="form-control" placeholder="Nombre del ingrediente" name="ingrediente_nombre[]">
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" placeholder="Cantidad por 100L" name="ingrediente_cantidad[]">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarIngrediente(this)">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    `;
    container.appendChild(newRow);
}

function eliminarIngrediente(button) {
    button.closest('.ingrediente-row').remove();
}

// Funciones de gestión de producción
function verificarMateriasPrimas() {
    fetch('/ajax/verificar-materias-primas')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let mensaje = 'Estado de Materias Primas:\n\n';
                let hayProblemas = false;
                
                data.materias.forEach(materia => {
                    const icono = materia.stock_actual > materia.stock_minimo ? '✅' : '⚠️';
                    mensaje += `${icono} ${materia.nombre}: ${materia.stock_actual} ${materia.unidad_medida}`;
                    
                    if (materia.stock_actual <= materia.stock_minimo) {
                        mensaje += ` (Mín: ${materia.stock_minimo})`;
                        hayProblemas = true;
                    }
                    mensaje += '\n';
                });
                
                if (hayProblemas) {
                    mensaje += '\n¿Desea ir al módulo de Materias Primas para gestionar el stock?';
                    if (confirm(mensaje)) {
                        window.open('/materias-primas', '_blank');
                    }
                } else {
                    alert(mensaje + '\n✅ Todas las materias primas tienen stock suficiente.');
                }
            } else {
                alert('❌ Error al verificar materias primas: ' + (data.message || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Error de conexión al verificar materias primas');
        });
}

function generarReporteProduccion() {
    fetch('/ajax/reporte-produccion-diario')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const fecha = new Date().toLocaleDateString();
                const reporte = `📊 Reporte de Producción - ${fecha}

📈 Estadísticas del Día:
• Lotes iniciados: ${data.lotes_iniciados}
• Lotes completados: ${data.lotes_completados}
• Lotes en proceso: ${data.lotes_en_proceso}
• Lotes en maduración: ${data.lotes_en_maduracion}

🧀 Producción por Tipo:
${data.produccion_por_tipo.map(tipo => `• ${tipo.nombre}: ${tipo.cantidad}kg`).join('\n')}

⏱️ Tiempo Promedio de Proceso: ${data.tiempo_promedio || 'N/A'} horas
📊 Eficiencia: ${data.eficiencia || 'N/A'}%
🌡️ Condiciones Promedio: ${data.temperatura_promedio || 'N/A'}°C, ${data.humedad_promedio || 'N/A'}% HR

¿Desea descargar el reporte completo?`;

                if (confirm(reporte)) {
                    window.open('/reportes/produccion-diario?formato=pdf', '_blank');
                }
            } else {
                alert('❌ Error al generar reporte: ' + (data.message || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Error de conexión al generar reporte');
        });
}

function controlCalidadRapido() {
    fetch('/ajax/control-calidad-rapido')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let mensaje = '🔬 Control de Calidad Rápido:\n\n';
                let requiereAtencion = false;
                
                data.controles.forEach(control => {
                    const icono = control.estado === 'conforme' ? '✅' : '⚠️';
                    mensaje += `${icono} ${control.numero_lote} - ${control.parametro}: ${control.valor}`;
                    
                    if (control.estado !== 'conforme') {
                        mensaje += ` (Objetivo: ${control.objetivo})`;
                        requiereAtencion = true;
                    }
                    mensaje += '\n';
                });
                
                if (requiereAtencion) {
                    mensaje += '\n¿Desea ir al módulo de Calidad para hacer los ajustes?';
                    if (confirm(mensaje)) {
                        window.open('/calidad', '_blank');
                    }
                } else {
                    alert(mensaje + '\n Todos los parámetros están dentro del rango objetivo.');
                }
            } else {
                alert(' Error al verificar calidad: ' + (data.message || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(' Error de conexión al verificar calidad');
        });
}

function alertasProduccion() {
    fetch('/ajax/alertas-produccion')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let mensaje = '🚨 Alertas de Producción:\n\n';
                
                if (data.alertas.length === 0) {
                    mensaje += ' No hay alertas activas en este momento.';
                } else {
                    data.alertas.forEach(alerta => {
                        const icono = alerta.prioridad === 'critica' ? '🔴' : 
                                     alerta.prioridad === 'alta' ? '⚠️' : 
                                     alerta.prioridad === 'media' ? '🟡' : 'ℹ️';
                        mensaje += `${icono} ${alerta.mensaje}\n`;
                    });
                }
                
                alert(mensaje);
            } else {
                alert(' Error al obtener alertas: ' + (data.message || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(' Error de conexión al obtener alertas');
        });
}

// Funciones para lotes específicos
function verLote(numeroLote) {
    alert(` Abriendo detalles del ${numeroLote}...\n\n(Redirigiendo a vista completa del lote)`);
    // window.location.href = `/produccion/lotes/ver/${numeroLote}`;
}

function avanzarEtapa(numeroLote) {
    if (confirm(`¿Confirma avanzar el ${numeroLote} a la siguiente etapa del proceso?`)) {
        alert(` ${numeroLote} avanzado a la siguiente etapa exitosamente.`);
        actualizarLotes();
    }
}

function registrarControl(numeroLote) {
    const parametros = prompt(`🔬 Registrar control de calidad para ${numeroLote}:\n\nIngrese valores separados por comas:\npH, Temperatura(°C), Humedad(%)\n\nEjemplo: 6.1, 16.5, 78`);
    
    if (parametros) {
        alert(` Control de calidad registrado para ${numeroLote}:\n${parametros}`);
    }
}

function voltearLote(numeroLote) {
    if (confirm(`¿Confirma realizar el volteo del ${numeroLote}?`)) {
        alert(` Volteo registrado para ${numeroLote}`);
    }
}

function crearLoteDeReceta(recetaId) {
    if (confirm(`¿Desea crear un nuevo lote usando esta receta?`)) {
        // Abrir modal con la receta preseleccionada
        document.getElementById('receta_id').value = recetaId;
        calcularEstimacion();
        
        const modal = new bootstrap.Modal(document.getElementById('modalCrearLote'));
        modal.show();
    }
}

function verReceta(recetaId) {
    window.open(`/produccion/recetas/ver/${recetaId}`, '_blank');
}

// Funciones de actualización
function actualizarLotes() {
    const indicator = document.getElementById('statusIndicator');
    indicator.textContent = 'Actualizando...';
    indicator.className = 'badge bg-warning ms-2';
    
    setTimeout(() => {
        indicator.textContent = 'En línea';
        indicator.className = 'badge bg-success ms-2';
        
        // Aquí se actualizarían los datos reales
        console.log('Lotes actualizados');
    }, 1500);
}

function actualizarLotesActivos() {
    actualizarLotes();
}

function marcarTareaCompleta() {
    alert('✅ Tarea marcada como completada');
}

function actualizarCondiciones() {
    // Simular variación de condiciones
    const tempBase = 18;
    const humBase = 75;
    const variacion = (Math.random() - 0.5) * 2;
    
    const nuevaTemp = (tempBase + variacion).toFixed(1);
    const nuevaHum = Math.round(humBase + variacion * 5);
    
    document.getElementById('temperatura').textContent = nuevaTemp + '°C';
    document.getElementById('humedad').textContent = nuevaHum + '%';
}
</script>

<?php include_once VIEWS_PATH . "layouts/footer.php"; ?>