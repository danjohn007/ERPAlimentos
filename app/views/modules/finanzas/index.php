<?php include_once VIEWS_PATH . "layouts/header.php"; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-dollar-sign"></i> <?php echo $title; ?></h2>
            </div>
            
            <!-- Resumen Financiero -->
            <div class="row mb-4">
                <?php 
                $totales = [
                    'activos' => 0,
                    'pasivos' => 0,
                    'capital' => 0,
                    'ingresos' => 0,
                    'gastos' => 0
                ];
                
                foreach ($balance_general as $balance) {
                    $totales[$balance['tipo']] = $balance['total_saldo'];
                }
                ?>
                
                <div class="col-md-2">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h5>Activos</h5>
                            <h4>$<?php echo number_format($totales['activos'], 2); ?></h4>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <h5>Pasivos</h5>
                            <h4>$<?php echo number_format($totales['pasivos'], 2); ?></h4>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h5>Capital</h5>
                            <h4>$<?php echo number_format($totales['capital'], 2); ?></h4>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h5>Ingresos</h5>
                            <h4>$<?php echo number_format($totales['ingresos'], 2); ?></h4>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h5>Gastos</h5>
                            <h4>$<?php echo number_format($totales['gastos'], 2); ?></h4>
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
                                    <a href="/finanzas/nuevo_asiento" class="btn btn-primary btn-lg btn-block">
                                        <i class="fas fa-plus"></i><br>
                                        Nuevo Asiento
                                    </a>
                                </div>
                                <div class="col-md-3 text-center mb-3">
                                    <a href="/finanzas/contabilidad" class="btn btn-info btn-lg btn-block">
                                        <i class="fas fa-book"></i><br>
                                        Contabilidad
                                    </a>
                                </div>
                                <div class="col-md-3 text-center mb-3">
                                    <a href="/finanzas/balance_general" class="btn btn-success btn-lg btn-block">
                                        <i class="fas fa-chart-bar"></i><br>
                                        Balance General
                                    </a>
                                </div>
                                <div class="col-md-3 text-center mb-3">
                                    <a href="/finanzas/reportes" class="btn btn-secondary btn-lg btn-block">
                                        <i class="fas fa-file-alt"></i><br>
                                        Reportes
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Asientos Recientes y Estadísticas -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-file-alt"></i> Asientos Contables Recientes</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($asientos_recientes)): ?>
                                <p class="text-muted">No hay asientos contables registrados.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Número</th>
                                                <th>Fecha</th>
                                                <th>Concepto</th>
                                                <th>Debe</th>
                                                <th>Haber</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($asientos_recientes as $asiento): ?>
                                                <tr>
                                                    <td>
                                                        <a href="/finanzas/ver_asiento/<?php echo $asiento['id']; ?>">
                                                            <?php echo htmlspecialchars($asiento['numero_asiento']); ?>
                                                        </a>
                                                    </td>
                                                    <td><?php echo date('d/m/Y', strtotime($asiento['fecha'])); ?></td>
                                                    <td><?php echo htmlspecialchars(substr($asiento['concepto'], 0, 50)) . '...'; ?></td>
                                                    <td>$<?php echo number_format($asiento['total_debe'], 2); ?></td>
                                                    <td>$<?php echo number_format($asiento['total_haber'], 2); ?></td>
                                                    <td>
                                                        <?php
                                                        $estadoClass = [
                                                            'borrador' => 'secondary',
                                                            'confirmado' => 'success',
                                                            'anulado' => 'danger'
                                                        ];
                                                        $class = $estadoClass[$asiento['estado']] ?? 'secondary';
                                                        ?>
                                                        <span class="badge bg-<?php echo $class; ?>">
                                                            <?php echo ucfirst($asiento['estado']); ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center">
                                    <a href="/finanzas/contabilidad" class="btn btn-outline-primary">Ver Todos</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-chart-pie"></i> Resumen de Asientos</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($resumen_tipos)): ?>
                                <?php foreach ($resumen_tipos as $resumen): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span><?php echo ucfirst($resumen['tipo']); ?></span>
                                        <span class="badge bg-primary"><?php echo $resumen['total_asientos']; ?></span>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted">No hay datos para mostrar.</p>
                            <?php endif; ?>
                            
                            <hr>
                            
                            <h6>Asientos en Borrador</h6>
                            <div class="text-center">
                                <h3 class="text-warning"><?php echo count($asientos_borrador); ?></h3>
                                <p class="text-muted">Por confirmar</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once VIEWS_PATH . "layouts/footer.php"; ?>
