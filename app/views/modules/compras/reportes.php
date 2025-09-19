<?php include_once VIEWS_PATH . "layouts/header.php"; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-chart-bar"></i> <?php echo $title; ?></h2>
                <div>
                    <button class="btn btn-outline-primary" onclick="exportarReporte()">
                        <i class="fas fa-download"></i> Exportar Reporte
                    </button>
                    <a href="/compras" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
            
            <!-- Resumen del mes actual -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4>$<?php echo number_format($compras_mes['total_compras'] ?? 0, 2); ?></h4>
                                    <p class="mb-0">Total Compras - <?php echo date('F Y'); ?></p>
                                </div>
                                <i class="fas fa-dollar-sign fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4><?php echo $compras_mes['total_ordenes'] ?? 0; ?></h4>
                                    <p class="mb-0">Órdenes Generadas - <?php echo date('F Y'); ?></p>
                                </div>
                                <i class="fas fa-file-alt fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Filtros de periodo -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-filter"></i> Filtros de Periodo</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label for="fecha-desde" class="form-label">Fecha Desde</label>
                            <input type="date" class="form-control" id="fecha-desde" 
                                   value="<?php echo date('Y-m-01'); ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="fecha-hasta" class="form-label">Fecha Hasta</label>
                            <input type="date" class="form-control" id="fecha-hasta" 
                                   value="<?php echo date('Y-m-t'); ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="periodo-predefinido" class="form-label">Periodo Predefinido</label>
                            <select class="form-select" id="periodo-predefinido">
                                <option value="">Personalizado</option>
                                <option value="hoy">Hoy</option>
                                <option value="semana">Esta Semana</option>
                                <option value="mes" selected>Este Mes</option>
                                <option value="trimestre">Este Trimestre</option>
                                <option value="año">Este Año</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary" onclick="aplicarFiltrosPeriodo()">
                                <i class="fas fa-search"></i> Aplicar Filtros
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Gráfico de órdenes por estado -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-pie-chart"></i> Órdenes por Estado</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="estadosChart" width="400" height="400"></canvas>
                            
                            <div class="mt-3">
                                <div class="row text-center">
                                    <?php foreach ($ordenes_por_estado as $estado): ?>
                                        <div class="col">
                                            <h6 class="text-<?php echo getEstadoColor($estado['estado']); ?>">
                                                <?php echo ucfirst($estado['estado']); ?>
                                            </h6>
                                            <h4><?php echo $estado['total']; ?></h4>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Top 5 proveedores -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-trophy"></i> Top 5 Proveedores</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($top_proveedores)): ?>
                                <p class="text-muted text-center">No hay datos de proveedores disponibles</p>
                            <?php else: ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($top_proveedores as $index => $proveedor): ?>
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-primary rounded-pill me-2"><?php echo $index + 1; ?></span>
                                                    <div>
                                                        <h6 class="mb-0"><?php echo htmlspecialchars($proveedor['nombre']); ?></h6>
                                                        <small class="text-muted"><?php echo $proveedor['total_ordenes']; ?> órdenes</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <strong>$<?php echo number_format($proveedor['total_compras'], 2); ?></strong>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Análisis de tendencias -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-chart-line"></i> Tendencias de Compras</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="tendenciasChart" width="400" height="200"></canvas>
                            
                            <div class="row mt-4">
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <i class="fas fa-arrow-up text-success fa-2x"></i>
                                        <h6 class="mt-2">Crecimiento Mensual</h6>
                                        <h4 class="text-success">+15%</h4>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <i class="fas fa-calculator text-info fa-2x"></i>
                                        <h6 class="mt-2">Promedio por Orden</h6>
                                        <h4 class="text-info">$2,450</h4>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <i class="fas fa-clock text-warning fa-2x"></i>
                                        <h6 class="mt-2">Tiempo Promedio</h6>
                                        <h4 class="text-warning">3.5 días</h4>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <i class="fas fa-check-circle text-success fa-2x"></i>
                                        <h6 class="mt-2">Tasa de Cumplimiento</h6>
                                        <h4 class="text-success">94%</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Alertas y recomendaciones -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5><i class="fas fa-exclamation-triangle"></i> Alertas</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning" role="alert">
                                <i class="fas fa-exclamation-circle"></i>
                                <strong>Stock Bajo:</strong> 5 materias primas requieren reabastecimiento urgente.
                            </div>
                            <div class="alert alert-info" role="alert">
                                <i class="fas fa-info-circle"></i>
                                <strong>Órdenes Pendientes:</strong> 3 órdenes esperan confirmación del proveedor.
                            </div>
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle"></i>
                                <strong>Entregas a Tiempo:</strong> 8 órdenes llegaron según lo programado esta semana.
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5><i class="fas fa-lightbulb"></i> Recomendaciones</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <i class="fas fa-star text-warning"></i>
                                    <strong>Negociar mejores precios:</strong> El proveedor ABC Corp muestra un aumento del 12% en costos.
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-clock text-info"></i>
                                    <strong>Optimizar tiempos:</strong> Consolidar órdenes pequeñas puede reducir costos de envío.
                                </li>
                                <li class="mb-3">
                                    <i class="fas fa-chart-line text-success"></i>
                                    <strong>Planificación:</strong> Incrementar stock de materias estacionales antes del Q4.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Datos para el gráfico de estados
const estadosData = {
    labels: [<?php echo "'" . implode("','", array_column($ordenes_por_estado, 'estado')) . "'"; ?>],
    datasets: [{
        data: [<?php echo implode(',', array_column($ordenes_por_estado, 'total')); ?>],
        backgroundColor: [
            '#ffc107', // Borrador (amarillo)
            '#17a2b8', // Enviado (azul)
            '#28a745', // Confirmado (verde)
            '#fd7e14', // Recibido (naranja)
            '#dc3545'  // Cancelado (rojo)
        ]
    }]
};

// Configuración del gráfico de estados
const estadosConfig = {
    type: 'doughnut',
    data: estadosData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
};

// Renderizar gráfico de estados
new Chart(document.getElementById('estadosChart'), estadosConfig);

// Datos simulados para el gráfico de tendencias
const tendenciasData = {
    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
    datasets: [{
        label: 'Compras ($)',
        data: [12000, 15000, 13500, 18000, 20000, 22000],
        borderColor: '#007bff',
        backgroundColor: 'rgba(0, 123, 255, 0.1)',
        tension: 0.4
    }, {
        label: 'Número de Órdenes',
        data: [8, 12, 10, 14, 16, 18],
        borderColor: '#28a745',
        backgroundColor: 'rgba(40, 167, 69, 0.1)',
        tension: 0.4,
        yAxisID: 'y1'
    }]
};

// Configuración del gráfico de tendencias
const tendenciasConfig = {
    type: 'line',
    data: tendenciasData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: {
                    display: true,
                    text: 'Monto ($)'
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: {
                    display: true,
                    text: 'Cantidad de Órdenes'
                },
                grid: {
                    drawOnChartArea: false,
                }
            }
        },
        plugins: {
            legend: {
                position: 'top'
            }
        }
    }
};

// Renderizar gráfico de tendencias
new Chart(document.getElementById('tendenciasChart'), tendenciasConfig);

// Funciones para filtros de periodo
function aplicarFiltrosPeriodo() {
    const fechaDesde = document.getElementById('fecha-desde').value;
    const fechaHasta = document.getElementById('fecha-hasta').value;
    
    if (!fechaDesde || !fechaHasta) {
        alert('Por favor, seleccione ambas fechas.');
        return;
    }
    
    if (new Date(fechaDesde) > new Date(fechaHasta)) {
        alert('La fecha de inicio no puede ser mayor a la fecha final.');
        return;
    }
    
    // Aquí se harían las peticiones AJAX para actualizar los datos
    alert(`Aplicando filtros desde ${fechaDesde} hasta ${fechaHasta}`);
    // location.reload(); // Recargar con los nuevos parámetros
}

// Manejar periodos predefinidos
document.getElementById('periodo-predefinido').addEventListener('change', function() {
    const periodo = this.value;
    const hoy = new Date();
    let fechaDesde, fechaHasta;
    
    switch (periodo) {
        case 'hoy':
            fechaDesde = fechaHasta = hoy.toISOString().split('T')[0];
            break;
        case 'semana':
            fechaDesde = new Date(hoy.getTime() - 6 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
            fechaHasta = hoy.toISOString().split('T')[0];
            break;
        case 'mes':
            fechaDesde = new Date(hoy.getFullYear(), hoy.getMonth(), 1).toISOString().split('T')[0];
            fechaHasta = new Date(hoy.getFullYear(), hoy.getMonth() + 1, 0).toISOString().split('T')[0];
            break;
        case 'trimestre':
            const trimestre = Math.floor(hoy.getMonth() / 3);
            fechaDesde = new Date(hoy.getFullYear(), trimestre * 3, 1).toISOString().split('T')[0];
            fechaHasta = new Date(hoy.getFullYear(), (trimestre + 1) * 3, 0).toISOString().split('T')[0];
            break;
        case 'año':
            fechaDesde = new Date(hoy.getFullYear(), 0, 1).toISOString().split('T')[0];
            fechaHasta = new Date(hoy.getFullYear(), 11, 31).toISOString().split('T')[0];
            break;
        default:
            return;
    }
    
    document.getElementById('fecha-desde').value = fechaDesde;
    document.getElementById('fecha-hasta').value = fechaHasta;
});

function exportarReporte() {
    const fechaDesde = document.getElementById('fecha-desde').value;
    const fechaHasta = document.getElementById('fecha-hasta').value;
    
    // Simular exportación
    alert(`Exportando reporte de compras del ${fechaDesde} al ${fechaHasta}...`);
    
    // En una implementación real, se haría una petición al servidor
    // window.open(`/compras/exportar_reporte?desde=${fechaDesde}&hasta=${fechaHasta}`);
}
</script>

<?php 
function getEstadoColor($estado) {
    $colores = [
        'borrador' => 'warning',
        'enviado' => 'info',
        'confirmado' => 'success',
        'recibido' => 'primary',
        'cancelado' => 'danger'
    ];
    return $colores[$estado] ?? 'secondary';
}
?>

<?php include_once VIEWS_PATH . "layouts/footer.php"; ?>