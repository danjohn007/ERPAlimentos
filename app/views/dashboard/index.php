<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-tachometer-alt"></i> Dashboard</h2>
    <div class="text-muted">
        <i class="fas fa-calendar"></i> <?= date('d/m/Y H:i') ?>
    </div>
</div>

<!-- Estadísticas Principales -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card card-stats">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Producción Hoy</h6>
                        <h3 class="mb-0"><?= number_format($produccionHoy['kg_producidos'] ?? 0, 1) ?> kg</h3>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-industry"></i>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p class="card-text text-muted">
                    <?= $produccionHoy['total'] ?? 0 ?> lotes terminados
                </p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card card-stats">
            <div class="card-header bg-success text-white">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Inventario</h6>
                        <h3 class="mb-0"><?= number_format($inventarioProductos['total_kg'] ?? 0, 1) ?> kg</h3>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-warehouse"></i>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p class="card-text text-muted">
                    Productos terminados
                </p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card card-stats">
            <div class="card-header bg-warning text-white">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Ventas del Mes</h6>
                        <h3 class="mb-0"><?= $this->formatMoney($ventasMes['total_ventas'] ?? 0) ?></h3>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p class="card-text text-muted">
                    <?= $ventasMes['total_ordenes'] ?? 0 ?> órdenes
                </p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card card-stats">
            <div class="card-header bg-danger text-white">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Próximos a Vencer</h6>
                        <h3 class="mb-0"><?= count($proximosVencer) ?></h3>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p class="card-text text-muted">
                    Próximos 7 días
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Lotes en Proceso -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-boxes"></i> Lotes en Proceso
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($lotesEnProceso)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Lote</th>
                                    <th>Producto</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lotesEnProceso as $lote): ?>
                                <tr>
                                    <td><strong><?= $this->escape($lote['numero_lote']) ?></strong></td>
                                    <td><?= $this->escape($lote['producto']) ?></td>
                                    <td>
                                        <?php
                                        $badgeClass = '';
                                        switch ($lote['estado']) {
                                            case 'programado':
                                                $badgeClass = 'bg-info';
                                                break;
                                            case 'en_proceso':
                                                $badgeClass = 'bg-warning';
                                                break;
                                            case 'terminado':
                                                $badgeClass = 'bg-success';
                                                break;
                                        }
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= ucfirst($lote['estado']) ?></span>
                                    </td>
                                    <td><?= $this->formatDate($lote['fecha_inicio']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="<?= $this->url('produccion/lotes') ?>" class="btn btn-outline-primary btn-sm">
                            Ver Todos los Lotes
                        </a>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-box-open fa-3x mb-3"></i>
                        <p>No hay lotes en proceso</p>
                        <a href="<?= $this->url('produccion/lotes/crear') ?>" class="btn btn-primary btn-sm">
                            Crear Nuevo Lote
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Productos Próximos a Vencer -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock"></i> Próximos a Vencer
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($proximosVencer)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Vencimiento</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($proximosVencer as $producto): ?>
                                <tr>
                                    <td><?= $this->escape($producto['nombre']) ?></td>
                                    <td><?= $this->formatNumber($producto['cantidad'], 1) ?> kg</td>
                                    <td>
                                        <?php
                                        $fechaVencimiento = new DateTime($producto['fecha_caducidad']);
                                        $hoy = new DateTime();
                                        $dias = $hoy->diff($fechaVencimiento)->days;
                                        
                                        $alertClass = '';
                                        if ($dias <= 1) {
                                            $alertClass = 'text-danger fw-bold';
                                        } elseif ($dias <= 3) {
                                            $alertClass = 'text-warning fw-bold';
                                        }
                                        ?>
                                        <span class="<?= $alertClass ?>">
                                            <?= $this->formatDate($producto['fecha_caducidad']) ?>
                                            (<?= $dias ?> días)
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="<?= $this->url('inventario') ?>" class="btn btn-outline-warning btn-sm">
                            Ver Inventario Completo
                        </a>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                        <p>No hay productos próximos a vencer</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Gráficas -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-line"></i> Producción Semanal
                </h5>
            </div>
            <div class="card-body">
                <canvas id="produccionChart" height="200"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie"></i> Productos por Tipo
                </h5>
            </div>
            <div class="card-body">
                <canvas id="productosChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
// Gráfica de Producción Semanal
const ctxProduccion = document.getElementById('produccionChart').getContext('2d');
new Chart(ctxProduccion, {
    type: 'line',
    data: {
        labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
        datasets: [{
            label: 'Kg Producidos',
            data: [120, 150, 180, 90, 200, 160, 140],
            borderColor: '#2E8B57',
            backgroundColor: 'rgba(46, 139, 87, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Gráfica de Productos por Tipo
const ctxProductos = document.getElementById('productosChart').getContext('2d');
new Chart(ctxProductos, {
    type: 'doughnut',
    data: {
        labels: ['Frescos', 'Semicurados', 'Curados'],
        datasets: [{
            data: [45, 30, 25],
            backgroundColor: ['#2E8B57', '#FFD700', '#FF6B6B'],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>