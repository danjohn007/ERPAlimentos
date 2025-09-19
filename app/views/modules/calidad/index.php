<?php include_once VIEWS_PATH . "layouts/header.php"; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-microscope"></i> <?php echo $title; ?></h2>
            </div>
            
            <!-- Alertas de productos próximos a vencer -->
            <?php if (!empty($productos_vencer)): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-warning alert-dismissible fade show">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>¡Atención!</strong> Hay <?php echo count($productos_vencer); ?> productos próximos a vencer que requieren análisis.
                        <a href="#productos-vencer" class="alert-link">Ver detalles</a>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Estadísticas de Calidad -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>
                                        <?php
                                        $conformes = 0;
                                        foreach($estadisticas as $est) {
                                            if($est['resultado'] === 'conforme') $conformes = $est['total'];
                                        }
                                        echo $conformes;
                                        ?>
                                    </h4>
                                    <p>Análisis Conformes</p>
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
                                    <h4><?php echo count($analisis_no_conformes); ?></h4>
                                    <p>No Conformidades</p>
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
                                    <h4><?php echo count($lotes_en_proceso); ?></h4>
                                    <p>Lotes en Proceso</p>
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
                                    <h4><?php echo count($productos_vencer ?? []); ?></h4>
                                    <p>Próximos a Vencer</p>
                                </div>
                                <i class="fas fa-clock fa-2x"></i>
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
                                <div class="col-md-3 text-center mb-3">
                                    <a href="/calidad/nuevo_analisis" class="btn btn-primary btn-lg btn-block">
                                        <i class="fas fa-plus"></i><br>
                                        Nuevo Análisis
                                    </a>
                                </div>
                                <div class="col-md-3 text-center mb-3">
                                    <a href="/calidad/trazabilidad" class="btn btn-info btn-lg btn-block">
                                        <i class="fas fa-search"></i><br>
                                        Trazabilidad
                                    </a>
                                </div>
                                <div class="col-md-3 text-center mb-3">
                                    <a href="/calidad/no_conformidades" class="btn btn-warning btn-lg btn-block">
                                        <i class="fas fa-shield-alt"></i><br>
                                        No Conformidades
                                    </a>
                                </div>
                                <div class="col-md-3 text-center mb-3">
                                    <a href="/calidad/estadisticas" class="btn btn-success btn-lg btn-block">
                                        <i class="fas fa-chart-bar"></i><br>
                                        Estadísticas
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Análisis Recientes -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-history"></i> Análisis Recientes</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($analisis_recientes)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-microscope fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">No hay análisis registrados</h4>
                                <p class="text-muted">Comienza realizando tu primer análisis de calidad</p>
                                <a href="/calidad/nuevo_analisis" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Realizar Primer Análisis
                                </a>
                            </div>
                            <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
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
                                            <td><?php echo date('d/m/Y H:i', strtotime($analisis['fecha_analisis'])); ?></td>
                                            <td>
                                                <?php
                                                $tipos = [
                                                    'materia_prima' => '<span class="badge bg-primary">Materia Prima</span>',
                                                    'producto_terminado' => '<span class="badge bg-success">Producto</span>',
                                                    'proceso' => '<span class="badge bg-warning">Proceso</span>'
                                                ];
                                                echo $tipos[$analisis['tipo']] ?? $analisis['tipo'];
                                                ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($analisis['item_nombre']); ?>
                                                <?php if ($analisis['numero_lote']): ?>
                                                <br><small class="text-muted">Lote: <?php echo $analisis['numero_lote']; ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $analisis['ph'] ? number_format($analisis['ph'], 1) : '-'; ?></td>
                                            <td><?php echo $analisis['humedad'] ? number_format($analisis['humedad'], 2) . '%' : '-'; ?></td>
                                            <td><?php echo $analisis['temperatura'] ? number_format($analisis['temperatura'], 1) . '°C' : '-'; ?></td>
                                            <td><?php echo htmlspecialchars($analisis['analista_nombre'] . ' ' . $analisis['analista_apellidos']); ?></td>
                                            <td>
                                                <?php
                                                $resultados = [
                                                    'conforme' => '<span class="badge bg-success">Conforme</span>',
                                                    'no_conforme' => '<span class="badge bg-danger">No Conforme</span>',
                                                    'requiere_revision' => '<span class="badge bg-warning">Requiere Revisión</span>'
                                                ];
                                                echo $resultados[$analisis['resultado']] ?? $analisis['resultado'];
                                                ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="/calidad/ver_analisis/<?php echo $analisis['id']; ?>" 
                                                       class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="text-center mt-3">
                                <a href="/calidad/analisis" class="btn btn-outline-primary">
                                    <i class="fas fa-list"></i> Ver Todos los Análisis
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- No Conformidades Recientes -->
            <?php if (!empty($analisis_no_conformes)): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-danger">
                        <div class="card-header bg-danger text-white">
                            <h5><i class="fas fa-exclamation-triangle"></i> No Conformidades Recientes</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Tipo</th>
                                            <th>Item</th>
                                            <th>Problema</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (array_slice($analisis_no_conformes, 0, 5) as $analisis): ?>
                                        <tr>
                                            <td><?php echo date('d/m/Y', strtotime($analisis['fecha_analisis'])); ?></td>
                                            <td><?php echo ucfirst(str_replace('_', ' ', $analisis['tipo'])); ?></td>
                                            <td><?php echo htmlspecialchars($analisis['item_nombre']); ?></td>
                                            <td><?php echo htmlspecialchars(substr($analisis['observaciones'], 0, 50)) . '...'; ?></td>
                                            <td>
                                                <a href="/calidad/ver_analisis/<?php echo $analisis['id']; ?>" 
                                                   class="btn btn-sm btn-outline-danger">Ver</a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="text-center">
                                <a href="/calidad/no_conformidades" class="btn btn-outline-danger">
                                    Ver Todas las No Conformidades
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Lotes que requieren análisis -->
            <?php if (!empty($lotes_en_proceso)): ?>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-clock"></i> Lotes que Requieren Análisis</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Número de Lote</th>
                                            <th>Receta</th>
                                            <th>Fecha Inicio</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($lotes_en_proceso as $lote): ?>
                                        <tr>
                                            <td>
                                                <a href="/produccion/verLote/<?php echo $lote['id']; ?>">
                                                    <?php echo htmlspecialchars($lote['numero_lote']); ?>
                                                </a>
                                            </td>
                                            <td><?php echo htmlspecialchars($lote['receta_nombre'] ?? 'N/A'); ?></td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($lote['fecha_inicio'])); ?></td>
                                            <td>
                                                <span class="badge bg-warning">En Proceso</span>
                                            </td>
                                            <td>
                                                <a href="/calidad/nuevo_analisis?lote=<?php echo $lote['id']; ?>" 
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
    </div>
</div>

<?php include_once VIEWS_PATH . "layouts/footer.php"; ?>
