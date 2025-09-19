<?php include_once VIEWS_PATH . "layouts/header.php"; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-plus"></i> <?php echo $title; ?></h2>
                <a href="/compras/ordenes" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver a Órdenes
                </a>
            </div>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="/compras/nueva_orden">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-file-alt"></i> Información de la Orden</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="proveedor_id" class="form-label">Proveedor *</label>
                                            <select class="form-select" id="proveedor_id" name="proveedor_id" required>
                                                <option value="">Seleccionar proveedor...</option>
                                                <?php foreach ($proveedores as $proveedor): ?>
                                                    <option value="<?php echo $proveedor['id']; ?>">
                                                        <?php echo htmlspecialchars($proveedor['nombre']); ?> 
                                                        (<?php echo htmlspecialchars($proveedor['tipo']); ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="fecha_entrega_esperada" class="form-label">Fecha de Entrega Esperada</label>
                                            <input type="date" class="form-control" id="fecha_entrega_esperada" 
                                                   name="fecha_entrega_esperada" value="<?php echo date('Y-m-d', strtotime('+3 days')); ?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="observaciones" class="form-label">Observaciones</label>
                                    <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Detalles de la Orden -->
                        <div class="card mt-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5><i class="fas fa-list"></i> Detalles de la Orden</h5>
                                <button type="button" class="btn btn-sm btn-primary" onclick="agregarDetalle()">
                                    <i class="fas fa-plus"></i> Agregar Ítem
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table" id="tabla-detalles">
                                        <thead>
                                            <tr>
                                                <th>Materia Prima</th>
                                                <th>Cantidad</th>
                                                <th>Precio Unit.</th>
                                                <th>Descuento</th>
                                                <th>Subtotal</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="detalles-tbody">
                                            <!-- Los detalles se agregarán dinámicamente -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                                <td><strong id="total-orden">$0.00</strong></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Nota:</strong> Agregue al menos un ítem para poder crear la orden de compra.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-calculator"></i> Resumen</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-6">Subtotal:</div>
                                    <div class="col-6 text-end" id="resumen-subtotal">$0.00</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6">IVA (16%):</div>
                                    <div class="col-6 text-end" id="resumen-iva">$0.00</div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-6"><strong>Total:</strong></div>
                                    <div class="col-6 text-end"><strong id="resumen-total">$0.00</strong></div>
                                </div>
                                
                                <hr>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fas fa-save"></i> Crear Orden
                                    </button>
                                </div>
                                
                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i>
                                        La orden se creará en estado "Borrador" y podrá ser modificada antes de enviarla.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Template para nueva fila de detalle -->
<template id="detalle-template">
    <tr>
        <td>
            <select class="form-select materia-prima-select" name="detalles[][materia_prima_id]" required>
                <option value="">Seleccionar...</option>
                <?php foreach ($materias_primas as $materia): ?>
                    <option value="<?php echo $materia['id']; ?>" data-precio="<?php echo $materia['costo_unitario']; ?>">
                        <?php echo htmlspecialchars($materia['nombre']); ?> (<?php echo htmlspecialchars($materia['unidad_medida']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </td>
        <td>
            <input type="number" class="form-control cantidad-input" name="detalles[][cantidad]" 
                   step="0.01" min="0.01" required>
        </td>
        <td>
            <input type="number" class="form-control precio-input" name="detalles[][precio_unitario]" 
                   step="0.01" min="0.01" required>
        </td>
        <td>
            <input type="number" class="form-control descuento-input" name="detalles[][descuento]" 
                   step="0.01" min="0" value="0">
        </td>
        <td>
            <span class="subtotal-detalle">$0.00</span>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-danger" onclick="eliminarDetalle(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
</template>

<script>
let detalleIndex = 0;

function agregarDetalle() {
    const template = document.getElementById('detalle-template');
    const tbody = document.getElementById('detalles-tbody');
    const clone = template.content.cloneNode(true);
    
    tbody.appendChild(clone);
    
    // Agregar event listeners para cálculos
    const fila = tbody.lastElementChild;
    const materiaSelect = fila.querySelector('.materia-prima-select');
    const cantidadInput = fila.querySelector('.cantidad-input');
    const precioInput = fila.querySelector('.precio-input');
    const descuentoInput = fila.querySelector('.descuento-input');
    
    materiaSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.dataset.precio) {
            precioInput.value = selectedOption.dataset.precio;
            calcularSubtotalDetalle(fila);
        }
    });
    
    cantidadInput.addEventListener('input', () => calcularSubtotalDetalle(fila));
    precioInput.addEventListener('input', () => calcularSubtotalDetalle(fila));
    descuentoInput.addEventListener('input', () => calcularSubtotalDetalle(fila));
    
    detalleIndex++;
}

function eliminarDetalle(boton) {
    const fila = boton.closest('tr');
    fila.remove();
    calcularTotalOrden();
}

function calcularSubtotalDetalle(fila) {
    const cantidad = parseFloat(fila.querySelector('.cantidad-input').value) || 0;
    const precio = parseFloat(fila.querySelector('.precio-input').value) || 0;
    const descuento = parseFloat(fila.querySelector('.descuento-input').value) || 0;
    
    const subtotal = (cantidad * precio) - descuento;
    fila.querySelector('.subtotal-detalle').textContent = '$' + subtotal.toFixed(2);
    
    calcularTotalOrden();
}

function calcularTotalOrden() {
    let subtotal = 0;
    
    document.querySelectorAll('.subtotal-detalle').forEach(elemento => {
        const valor = parseFloat(elemento.textContent.replace('$', '')) || 0;
        subtotal += valor;
    });
    
    const iva = subtotal * 0.16;
    const total = subtotal + iva;
    
    document.getElementById('resumen-subtotal').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('resumen-iva').textContent = '$' + iva.toFixed(2);
    document.getElementById('resumen-total').textContent = '$' + total.toFixed(2);
    document.getElementById('total-orden').textContent = '$' + total.toFixed(2);
}

// Agregar primera fila al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    agregarDetalle();
});
</script>

<?php include_once VIEWS_PATH . "layouts/footer.php"; ?>