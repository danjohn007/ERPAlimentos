<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-wheat-awn"></i> <?= $title ?>
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?= $this->url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Materias Primas</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <!-- Alertas -->
    <?php if (!empty($stock_bajo) || !empty($proximas_vencer)): ?>
    <div class="row mb-4">
        <?php if (!empty($stock_bajo)): ?>
        <div class="col-md-6">
            <div class="alert alert-warning alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>¡Atención!</strong> Hay <?= count($stock_bajo) ?> materias primas con stock bajo.
                <a href="#stock-bajo" class="alert-link">Ver detalles</a>
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($proximas_vencer)): ?>
        <div class="col-md-6">
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-clock"></i>
                <strong>¡Urgente!</strong> Hay <?= count($proximas_vencer) ?> productos próximos a vencer.
                <a href="#proximas-vencer" class="alert-link">Ver detalles</a>
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
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title"><?= count($materias_primas) ?></h4>
                            <p class="card-text">Materias Primas</p>
                        </div>
                        <i class="fas fa-boxes fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title"><?= count($stock_bajo) ?></h4>
                            <p class="card-text">Stock Bajo</p>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title"><?= count($proximas_vencer) ?></h4>
                            <p class="card-text">Próximas a Vencer</p>
                        </div>
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title"><?= count($proveedores_certificados) ?></h4>
                            <p class="card-text">Proveedores Certificados</p>
                        </div>
                        <i class="fas fa-certificate fa-2x"></i>
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
                            <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#modalNuevaMateriaPrima">
                                <i class="fas fa-plus"></i> Nueva Materia Prima
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?= $this->url('materias-primas/proveedores') ?>" class="btn btn-info btn-block">
                                <i class="fas fa-truck"></i> Gestionar Proveedores
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?= $this->url('materias-primas/inventario') ?>" class="btn btn-secondary btn-block">
                                <i class="fas fa-warehouse"></i> Ver Inventario
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-success btn-block" data-toggle="modal" data-target="#modalEntradaInventario">
                                <i class="fas fa-arrow-down"></i> Registrar Entrada
                            </button>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-outline-warning btn-block" onclick="performInventoryAdjustment()">
                                <i class="fas fa-balance-scale"></i> Ajuste Inventario
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-outline-danger btn-block" onclick="checkExpirationAlerts()">
                                <i class="fas fa-clock"></i> Alertas Vencimiento
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-outline-info btn-block" onclick="evaluateSuppliers()">
                                <i class="fas fa-star"></i> Evaluar Proveedores
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?= $this->url('compras/nueva_orden') ?>" class="btn btn-outline-primary btn-block">
                                <i class="fas fa-shopping-cart"></i> Nueva Orden Compra
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Lista de materias primas -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> Materias Primas Registradas
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($materias_primas)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-wheat-awn fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No hay materias primas registradas</h4>
                        <p class="text-muted">Comienza registrando tus primeras materias primas</p>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modalNuevaMateriaPrima">
                            <i class="fas fa-plus"></i> Registrar Primera Materia Prima
                        </button>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Stock Actual</th>
                                    <th>Stock Mínimo</th>
                                    <th>Costo Unitario</th>
                                    <th>Proveedor</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($materias_primas as $mp): ?>
                                <tr class="<?= $mp['stock_actual'] <= $mp['stock_minimo'] ? 'table-warning' : '' ?>">
                                    <td>
                                        <span class="badge badge-secondary"><?= htmlspecialchars($mp['codigo']) ?></span>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($mp['nombre']) ?></strong>
                                        <?php if ($mp['requiere_refrigeracion']): ?>
                                        <br><small class="text-info"><i class="fas fa-snowflake"></i> Refrigeración</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $tipos = [
                                            'leche_vaca' => '<span class="badge badge-primary">Leche Vaca</span>',
                                            'leche_cabra' => '<span class="badge badge-info">Leche Cabra</span>',
                                            'cuajo' => '<span class="badge badge-warning">Cuajo</span>',
                                            'sal' => '<span class="badge badge-secondary">Sal</span>',
                                            'cultivo_lactico' => '<span class="badge badge-success">Cultivo</span>',
                                            'empaque' => '<span class="badge badge-dark">Empaque</span>'
                                        ];
                                        echo $tipos[$mp['tipo']] ?? $mp['tipo'];
                                        ?>
                                    </td>
                                    <td>
                                        <?= number_format($mp['stock_actual'], 2) ?> <?= $mp['unidad_medida'] ?>
                                        <?php if ($mp['stock_actual'] <= $mp['stock_minimo']): ?>
                                        <br><small class="text-danger"><i class="fas fa-exclamation-triangle"></i> Stock bajo</small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= number_format($mp['stock_minimo'], 2) ?> <?= $mp['unidad_medida'] ?></td>
                                    <td><?= CURRENCY_SYMBOL . number_format($mp['costo_unitario'], 4) ?></td>
                                    <td>
                                        <?php if ($mp['proveedor_nombre']): ?>
                                            <?= htmlspecialchars($mp['proveedor_nombre']) ?>
                                            <?php if ($mp['certificaciones']): ?>
                                            <br><small class="text-success"><i class="fas fa-certificate"></i> Certificado</small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">Sin asignar</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($mp['estado'] === 'activo'): ?>
                                            <span class="badge badge-success">Activo</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-info" onclick="verMateriaPrima(<?= $mp['id'] ?>)" 
                                                    title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-warning" onclick="editarMateriaPrima(<?= $mp['id'] ?>)" 
                                                    title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-success" onclick="entradaInventario(<?= $mp['id'] ?>)" 
                                                    title="Registrar entrada">
                                                <i class="fas fa-arrow-down"></i>
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
// Funciones para acciones rápidas de materias primas
function performInventoryAdjustment() {
    const materiasConStock = <?= json_encode($materias_primas) ?>;
    let options = '<option value="">Seleccionar materia prima...</option>';
    
    materiasConStock.forEach(mp => {
        options += `<option value="${mp.id}">${mp.nombre} - Stock actual: ${mp.stock_actual} ${mp.unidad_medida}</option>`;
    });
    
    const html = `
        <div class="modal fade" id="modalAjusteInventario" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajuste de Inventario</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formAjusteInventario">
                            <div class="form-group">
                                <label>Materia Prima</label>
                                <select class="form-control" id="materiaPrimaAjuste" required>
                                    ${options}
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nueva Cantidad</label>
                                <input type="number" class="form-control" id="nuevaCantidad" 
                                       step="0.01" min="0" required>
                            </div>
                            <div class="form-group">
                                <label>Motivo del Ajuste</label>
                                <select class="form-control" id="motivoAjuste" required>
                                    <option value="">Seleccionar motivo...</option>
                                    <option value="merma">Merma</option>
                                    <option value="vencimiento">Vencimiento</option>
                                    <option value="conteo_fisico">Conteo Físico</option>
                                    <option value="dano">Daño</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Observaciones</label>
                                <textarea class="form-control" id="observacionesAjuste" rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-warning" onclick="procesarAjusteInventario()">
                            <i class="fas fa-balance-scale"></i> Realizar Ajuste
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    $('#modalAjusteInventario').remove();
    $('body').append(html);
    $('#modalAjusteInventario').modal('show');
}

function procesarAjusteInventario() {
    const materiaPrimaId = document.getElementById('materiaPrimaAjuste').value;
    const nuevaCantidad = document.getElementById('nuevaCantidad').value;
    const motivo = document.getElementById('motivoAjuste').value;
    const observaciones = document.getElementById('observacionesAjuste').value;
    
    if (!materiaPrimaId || !nuevaCantidad || !motivo) {
        alert('Por favor complete todos los campos requeridos.');
        return;
    }
    
    // Simulate AJAX call
    alert(`Ajuste procesado:\nMateria Prima ID: ${materiaPrimaId}\nNueva cantidad: ${nuevaCantidad}\nMotivo: ${motivo}\n\nEn implementación real, esto enviaría los datos al servidor.`);
    $('#modalAjusteInventario').modal('hide');
}

function checkExpirationAlerts() {
    const proximasVencer = <?= json_encode($proximas_vencer) ?>;
    
    if (proximasVencer.length === 0) {
        alert('✓ No hay materias primas próximas a vencer.');
        return;
    }
    
    let message = 'Materias primas próximas a vencer:\n\n';
    proximasVencer.forEach(mp => {
        const diasRestantes = Math.ceil((new Date(mp.fecha_vencimiento) - new Date()) / (1000 * 60 * 60 * 24));
        message += `• ${mp.nombre}: ${diasRestantes} días restantes\n`;
    });
    
    message += '\n¿Desea ir al inventario para tomar acciones?';
    if (confirm(message)) {
        window.location.href = '/materias-primas/inventario';
    }
}

function evaluateSuppliers() {
    const proveedoresCertificados = <?= json_encode($proveedores_certificados) ?>;
    
    let message = 'Evaluación rápida de proveedores:\n\n';
    message += `Proveedores certificados: ${proveedoresCertificados.length}\n`;
    
    // Get materials with low stock and their suppliers
    const stockBajo = <?= json_encode($stock_bajo) ?>;
    const proveedoresConProblemas = new Set();
    
    stockBajo.forEach(mp => {
        if (mp.proveedor_nombre) {
            proveedoresConProblemas.add(mp.proveedor_nombre);
        }
    });
    
    if (proveedoresConProblemas.size > 0) {
        message += `Proveedores con materias primas en stock bajo: ${proveedoresConProblemas.size}\n`;
        message += `Proveedores: ${Array.from(proveedoresConProblemas).join(', ')}\n\n`;
        message += '¿Desea ir al módulo de proveedores para revisar?';
        
        if (confirm(message)) {
            window.location.href = '/materias-primas/proveedores';
        }
    } else {
        message += '\n✓ Todos los proveedores mantienen stock adecuado.';
        alert(message);
    }
}

// Existing functions for the modals and other functionality would go here...
function verMateriaPrima(id) {
    alert(`Ver detalles de materia prima ID: ${id}`);
}

function editarMateriaPrima(id) {
    alert(`Editar materia prima ID: ${id}`);
}

function entradaInventario(id) {
    alert(`Registrar entrada para materia prima ID: ${id}`);
}
</script>
