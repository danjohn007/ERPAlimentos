<?php include_once VIEWS_PATH . "layouts/header.php"; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-boxes"></i> <?php echo $title; ?></h2>
                <div>
                    <button class="btn btn-outline-primary" onclick="exportarInventario()">
                        <i class="fas fa-download"></i> Exportar
                    </button>
                    <a href="/compras" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
            
            <!-- Filtros y búsqueda -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label for="filtro-tipo" class="form-label">Tipo de Materia Prima</label>
                            <select class="form-select" id="filtro-tipo">
                                <option value="">Todos los tipos</option>
                                <option value="lacteos">Lácteos</option>
                                <option value="aditivos">Aditivos</option>
                                <option value="conservantes">Conservantes</option>
                                <option value="empaques">Empaques</option>
                                <option value="otros">Otros</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filtro-estado" class="form-label">Estado de Inventario</label>
                            <select class="form-select" id="filtro-estado">
                                <option value="">Todos los estados</option>
                                <option value="bajo">Stock Bajo</option>
                                <option value="normal">Stock Normal</option>
                                <option value="alto">Stock Alto</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="buscar-materia" class="form-label">Buscar</label>
                            <input type="text" class="form-control" id="buscar-materia" 
                                   placeholder="Buscar por nombre o código...">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-secondary" onclick="limpiarFiltros()">
                                <i class="fas fa-times"></i> Limpiar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Alertas de stock -->
            <div class="row mb-4" id="alertas-container">
                <div class="col-md-4">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                            <h5>Stock Crítico</h5>
                            <h3 id="count-critico">0</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-dark">
                        <div class="card-body text-center">
                            <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                            <h5>Stock Bajo</h5>
                            <h3 id="count-bajo">0</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <h5>Stock Normal</h5>
                            <h3 id="count-normal">0</h3>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <?php if (empty($inventario)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-boxes fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay materias primas registradas</h5>
                            <p class="text-muted">El inventario está vacío</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped" id="tabla-inventario">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Materia Prima</th>
                                        <th>Tipo</th>
                                        <th>Proveedor Principal</th>
                                        <th>Stock Actual</th>
                                        <th>Stock Mínimo</th>
                                        <th>Stock Máximo</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($inventario as $item): ?>
                                        <tr data-tipo="<?php echo $item['tipo']; ?>" 
                                            data-estado="<?php echo $item['estado_inventario']; ?>"
                                            data-nombre="<?php echo strtolower($item['nombre']); ?>">
                                            <td>
                                                <code><?php echo htmlspecialchars($item['codigo']); ?></code>
                                            </td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($item['nombre']); ?></strong>
                                                <?php if ($item['descripcion']): ?>
                                                    <br><small class="text-muted"><?php echo htmlspecialchars($item['descripcion']); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                $tipoColors = [
                                                    'lacteos' => 'primary',
                                                    'aditivos' => 'info',
                                                    'conservantes' => 'warning',
                                                    'empaques' => 'secondary',
                                                    'otros' => 'dark'
                                                ];
                                                $color = $tipoColors[$item['tipo']] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?php echo $color; ?>">
                                                    <?php echo ucfirst($item['tipo']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($item['proveedor_nombre']): ?>
                                                    <?php echo htmlspecialchars($item['proveedor_nombre']); ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Sin asignar</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong class="<?php echo $item['estado_inventario'] === 'bajo' ? 'text-danger' : ($item['estado_inventario'] === 'alto' ? 'text-warning' : 'text-success'); ?>">
                                                    <?php echo number_format($item['stock_actual'], 2); ?>
                                                </strong>
                                                <small class="text-muted"> <?php echo $item['unidad_medida'] ?? 'unidad'; ?></small>
                                            </td>
                                            <td>
                                                <?php echo number_format($item['stock_minimo'], 2); ?>
                                                <small class="text-muted"> <?php echo $item['unidad_medida'] ?? 'unidad'; ?></small>
                                            </td>
                                            <td>
                                                <?php echo number_format($item['stock_maximo'], 2); ?>
                                                <small class="text-muted"> <?php echo $item['unidad_medida'] ?? 'unidad'; ?></small>
                                            </td>
                                            <td>
                                                <?php
                                                $estadoClasses = [
                                                    'bajo' => 'bg-danger',
                                                    'normal' => 'bg-success',
                                                    'alto' => 'bg-warning text-dark'
                                                ];
                                                $estadoTextos = [
                                                    'bajo' => 'Stock Bajo',
                                                    'normal' => 'Normal',
                                                    'alto' => 'Stock Alto'
                                                ];
                                                $class = $estadoClasses[$item['estado_inventario']] ?? 'bg-secondary';
                                                $texto = $estadoTextos[$item['estado_inventario']] ?? 'Desconocido';
                                                ?>
                                                <span class="badge <?php echo $class; ?>">
                                                    <?php echo $texto; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary" 
                                                            onclick="verHistorial(<?php echo $item['id']; ?>)" 
                                                            title="Ver historial">
                                                        <i class="fas fa-history"></i>
                                                    </button>
                                                    
                                                    <?php if ($item['estado_inventario'] === 'bajo'): ?>
                                                        <button class="btn btn-outline-warning" 
                                                                onclick="generarOrdenCompra(<?php echo $item['id']; ?>)" 
                                                                title="Generar orden de compra">
                                                            <i class="fas fa-shopping-cart"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    
                                                    <button class="btn btn-outline-success" 
                                                            onclick="ajustarInventario(<?php echo $item['id']; ?>)" 
                                                            title="Ajustar inventario">
                                                        <i class="fas fa-edit"></i>
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
</div>

<script>
// Variables para contadores
let contadores = {
    critico: 0,
    bajo: 0,
    normal: 0
};

// Aplicar filtros
function aplicarFiltros() {
    const filtroTipo = document.getElementById('filtro-tipo').value;
    const filtroEstado = document.getElementById('filtro-estado').value;
    const busqueda = document.getElementById('buscar-materia').value.toLowerCase();
    
    const filas = document.querySelectorAll('#tabla-inventario tbody tr');
    
    // Resetear contadores
    contadores = { critico: 0, bajo: 0, normal: 0 };
    
    filas.forEach(fila => {
        let mostrar = true;
        
        // Filtro por tipo
        if (filtroTipo && fila.dataset.tipo !== filtroTipo) {
            mostrar = false;
        }
        
        // Filtro por estado
        if (filtroEstado && fila.dataset.estado !== filtroEstado) {
            mostrar = false;
        }
        
        // Filtro por búsqueda
        if (busqueda && !fila.dataset.nombre.includes(busqueda)) {
            const codigo = fila.querySelector('code').textContent.toLowerCase();
            if (!codigo.includes(busqueda)) {
                mostrar = false;
            }
        }
        
        if (mostrar) {
            fila.style.display = '';
            
            // Contar estados visibles
            const estado = fila.dataset.estado;
            if (estado === 'bajo') {
                const stockActual = parseFloat(fila.querySelector('td:nth-child(5) strong').textContent.replace(/[,]/g, ''));
                const stockMinimo = parseFloat(fila.querySelector('td:nth-child(6)').textContent.replace(/[,]/g, ''));
                
                if (stockActual < stockMinimo * 0.5) { // Stock crítico: menos del 50% del mínimo
                    contadores.critico++;
                } else {
                    contadores.bajo++;
                }
            } else if (estado === 'normal') {
                contadores.normal++;
            }
        } else {
            fila.style.display = 'none';
        }
    });
    
    // Actualizar contadores en las tarjetas
    document.getElementById('count-critico').textContent = contadores.critico;
    document.getElementById('count-bajo').textContent = contadores.bajo;
    document.getElementById('count-normal').textContent = contadores.normal;
}

function limpiarFiltros() {
    document.getElementById('filtro-tipo').value = '';
    document.getElementById('filtro-estado').value = '';
    document.getElementById('buscar-materia').value = '';
    aplicarFiltros();
}

function verHistorial(materiaId) {
    alert(`Ver historial de movimientos para la materia prima ID: ${materiaId}`);
    // Aquí se abriría un modal o se redirigiría a una página de historial
}

function generarOrdenCompra(materiaId) {
    if (confirm('¿Desea generar una orden de compra para esta materia prima?')) {
        window.location.href = `/compras/nueva_orden?materia_prima_id=${materiaId}`;
    }
}

function ajustarInventario(materiaId) {
    const cantidad = prompt('Ingrese la nueva cantidad en stock:');
    if (cantidad !== null && !isNaN(cantidad) && parseFloat(cantidad) >= 0) {
        // Aquí se haría la petición AJAX para ajustar el inventario
        alert(`Inventario ajustado a ${cantidad} unidades para la materia prima ID: ${materiaId}`);
        location.reload();
    } else if (cantidad !== null) {
        alert('Por favor, ingrese una cantidad válida.');
    }
}

function exportarInventario() {
    // Generar datos para exportar
    const filas = document.querySelectorAll('#tabla-inventario tbody tr:not([style*="display: none"])');
    let csv = 'Código,Materia Prima,Tipo,Proveedor,Stock Actual,Stock Mínimo,Stock Máximo,Estado\n';
    
    filas.forEach(fila => {
        const celdas = fila.querySelectorAll('td');
        const datos = [
            celdas[0].textContent.trim(),
            celdas[1].textContent.trim().replace(/\n/g, ' '),
            celdas[2].textContent.trim(),
            celdas[3].textContent.trim(),
            celdas[4].textContent.trim().replace(/\n/g, ' '),
            celdas[5].textContent.trim().replace(/\n/g, ' '),
            celdas[6].textContent.trim().replace(/\n/g, ' '),
            celdas[7].textContent.trim()
        ];
        csv += datos.map(d => `"${d}"`).join(',') + '\n';
    });
    
    // Crear y descargar archivo
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `inventario_materias_primas_${new Date().toISOString().split('T')[0]}.csv`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}

// Event listeners
document.getElementById('filtro-tipo').addEventListener('change', aplicarFiltros);
document.getElementById('filtro-estado').addEventListener('change', aplicarFiltros);
document.getElementById('buscar-materia').addEventListener('input', aplicarFiltros);

// Aplicar filtros iniciales al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    aplicarFiltros();
});
</script>

<?php include_once VIEWS_PATH . "layouts/footer.php"; ?>