<?php include_once VIEWS_PATH . "layouts/header.php"; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-list"></i> <?php echo $title; ?></h2>
                <a href="/ventas/nueva_orden" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nueva Orden
                </a>
            </div>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <!-- Filtros -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label for="filtro-estado" class="form-label">Estado</label>
                            <select class="form-select" id="filtro-estado">
                                <option value="">Todos los estados</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="proceso">En Proceso</option>
                                <option value="enviado">Enviado</option>
                                <option value="entregado">Entregado</option>
                                <option value="cancelado">Cancelado</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filtro-cliente" class="form-label">Cliente</label>
                            <input type="text" class="form-control" id="filtro-cliente" placeholder="Buscar por cliente...">
                        </div>
                        <div class="col-md-2">
                            <label for="filtro-fecha-desde" class="form-label">Desde</label>
                            <input type="date" class="form-control" id="filtro-fecha-desde">
                        </div>
                        <div class="col-md-2">
                            <label for="filtro-fecha-hasta" class="form-label">Hasta</label>
                            <input type="date" class="form-control" id="filtro-fecha-hasta">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-secondary" onclick="limpiarFiltros()">
                                <i class="fas fa-times"></i> Limpiar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <?php if (empty($ordenes)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay órdenes de venta registradas</h5>
                            <p class="text-muted">Comienza creando tu primera orden de venta</p>
                            <a href="/ventas/nueva_orden" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Crear Primera Orden
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped" id="tabla-ordenes">
                                <thead>
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
                                    <?php foreach ($ordenes as $orden): ?>
                                        <tr data-estado="<?php echo $orden['estado']; ?>" data-cliente="<?php echo strtolower($orden['cliente_nombre']); ?>">
                                            <td>
                                                <strong><?php echo htmlspecialchars($orden['numero_orden']); ?></strong>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($orden['cliente_nombre']); ?>
                                                <?php if ($orden['cliente_tipo']): ?>
                                                    <br><small class="text-muted"><?php echo ucfirst($orden['cliente_tipo']); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($orden['fecha_orden'])); ?></td>
                                            <td>
                                                <?php if ($orden['fecha_entrega']): ?>
                                                    <?php echo date('d/m/Y', strtotime($orden['fecha_entrega'])); ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Por definir</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong>$<?php echo number_format($orden['total'] ?? 0, 2); ?></strong>
                                            </td>
                                            <td>
                                                <?php
                                                $estados = [
                                                    'pendiente' => '<span class="badge bg-warning text-dark">Pendiente</span>',
                                                    'proceso' => '<span class="badge bg-info">En Proceso</span>',
                                                    'enviado' => '<span class="badge bg-primary">Enviado</span>',
                                                    'entregado' => '<span class="badge bg-success">Entregado</span>',
                                                    'cancelado' => '<span class="badge bg-danger">Cancelado</span>'
                                                ];
                                                echo $estados[$orden['estado']] ?? '<span class="badge bg-secondary">' . ucfirst($orden['estado']) . '</span>';
                                                ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="/ventas/ver_orden/<?php echo $orden['id']; ?>" 
                                                       class="btn btn-outline-primary" title="Ver detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    <?php if ($orden['estado'] === 'pendiente'): ?>
                                                        <button class="btn btn-outline-warning" 
                                                                onclick="cambiarEstado(<?php echo $orden['id']; ?>, 'proceso')" 
                                                                title="Procesar">
                                                            <i class="fas fa-play"></i>
                                                        </button>
                                                        <a href="/ventas/editar_orden/<?php echo $orden['id']; ?>" 
                                                           class="btn btn-outline-secondary" title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($orden['estado'] === 'proceso'): ?>
                                                        <button class="btn btn-outline-primary" 
                                                                onclick="cambiarEstado(<?php echo $orden['id']; ?>, 'enviado')" 
                                                                title="Marcar como enviado">
                                                            <i class="fas fa-shipping-fast"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($orden['estado'] === 'enviado'): ?>
                                                        <button class="btn btn-outline-success" 
                                                                onclick="cambiarEstado(<?php echo $orden['id']; ?>, 'entregado')" 
                                                                title="Marcar como entregado">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (in_array($orden['estado'], ['pendiente', 'proceso'])): ?>
                                                        <button class="btn btn-outline-danger" 
                                                                onclick="cancelarOrden(<?php echo $orden['id']; ?>)" 
                                                                title="Cancelar">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($orden['estado'] === 'entregado'): ?>
                                                        <a href="/ventas/facturar/<?php echo $orden['id']; ?>" 
                                                           class="btn btn-outline-success" title="Facturar">
                                                            <i class="fas fa-file-invoice"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Resumen estadístico -->
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="card bg-warning text-dark">
                                    <div class="card-body text-center">
                                        <h5>Pendientes</h5>
                                        <h3 id="total-pendientes">0</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h5>En Proceso</h5>
                                        <h3 id="total-proceso">0</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h5>Entregadas</h5>
                                        <h3 id="total-entregadas">0</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h5>Total Ventas</h5>
                                        <h3 id="total-ventas">$0.00</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Funciones de filtrado
document.getElementById('filtro-estado').addEventListener('change', aplicarFiltros);
document.getElementById('filtro-cliente').addEventListener('input', aplicarFiltros);
document.getElementById('filtro-fecha-desde').addEventListener('change', aplicarFiltros);
document.getElementById('filtro-fecha-hasta').addEventListener('change', aplicarFiltros);

function aplicarFiltros() {
    const filtroEstado = document.getElementById('filtro-estado').value;
    const filtroCliente = document.getElementById('filtro-cliente').value.toLowerCase();
    const filtroFechaDesde = document.getElementById('filtro-fecha-desde').value;
    const filtroFechaHasta = document.getElementById('filtro-fecha-hasta').value;
    
    const filas = document.querySelectorAll('#tabla-ordenes tbody tr');
    let totales = {
        pendientes: 0,
        proceso: 0,
        entregadas: 0,
        ventas: 0
    };
    
    filas.forEach(fila => {
        let mostrar = true;
        
        // Filtro por estado
        if (filtroEstado && fila.dataset.estado !== filtroEstado) {
            mostrar = false;
        }
        
        // Filtro por cliente
        if (filtroCliente && !fila.dataset.cliente.includes(filtroCliente)) {
            mostrar = false;
        }
        
        // Filtros de fecha (implementación básica)
        // En una implementación completa, se haría del lado del servidor
        
        if (mostrar) {
            fila.style.display = '';
            
            // Calcular totales
            const estado = fila.dataset.estado;
            switch (estado) {
                case 'pendiente':
                    totales.pendientes++;
                    break;
                case 'proceso':
                    totales.proceso++;
                    break;
                case 'entregado':
                    totales.entregadas++;
                    break;
            }
            
            // Sumar ventas (obtener el valor del total de la fila)
            const totalTexto = fila.querySelector('td:nth-child(5)').textContent;
            const total = parseFloat(totalTexto.replace(/[$,]/g, '')) || 0;
            totales.ventas += total;
            
        } else {
            fila.style.display = 'none';
        }
    });
    
    // Actualizar tarjetas de resumen
    document.getElementById('total-pendientes').textContent = totales.pendientes;
    document.getElementById('total-proceso').textContent = totales.proceso;
    document.getElementById('total-entregadas').textContent = totales.entregadas;
    document.getElementById('total-ventas').textContent = '$' + totales.ventas.toFixed(2);
}

function limpiarFiltros() {
    document.getElementById('filtro-estado').value = '';
    document.getElementById('filtro-cliente').value = '';
    document.getElementById('filtro-fecha-desde').value = '';
    document.getElementById('filtro-fecha-hasta').value = '';
    aplicarFiltros();
}

function cambiarEstado(ordenId, nuevoEstado) {
    const estados = {
        'proceso': '¿Está seguro de procesar esta orden?',
        'enviado': '¿Confirma que la orden ha sido enviada?',
        'entregado': '¿Confirma que la orden ha sido entregada?'
    };
    
    if (confirm(estados[nuevoEstado] || '¿Está seguro de cambiar el estado?')) {
        // Aquí se haría la petición AJAX al servidor
        fetch('/ventas/cambiar_estado_orden', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                orden_id: ordenId,
                estado: nuevoEstado
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error al cambiar el estado: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión');
        });
    }
}

function cancelarOrden(ordenId) {
    const motivo = prompt('Ingrese el motivo de cancelación:');
    if (motivo) {
        fetch('/ventas/cancelar_orden', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                orden_id: ordenId,
                motivo: motivo
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error al cancelar la orden: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión');
        });
    }
}

// Calcular totales iniciales al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    aplicarFiltros();
});
</script>

<?php include_once VIEWS_PATH . "layouts/footer.php"; ?>
