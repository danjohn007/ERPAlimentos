<?php include_once VIEWS_PATH . "layouts/header.php"; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-plus"></i> <?php echo $title; ?></h2>
                <a href="/ventas/ordenes" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver a Órdenes
                </a>
            </div>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="/ventas/nueva_orden">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-file-invoice"></i> Información de la Orden</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="cliente_id" class="form-label">Cliente *</label>
                                            <select class="form-select" id="cliente_id" name="cliente_id" required>
                                                <option value="">Seleccionar cliente...</option>
                                                <?php foreach ($clientes as $cliente): ?>
                                                    <option value="<?php echo $cliente['id']; ?>" 
                                                            data-descuento="<?php echo $cliente['descuento_porcentaje']; ?>"
                                                            data-credito="<?php echo $cliente['credito_limite']; ?>">
                                                        <?php echo htmlspecialchars($cliente['nombre']); ?> 
                                                        (<?php echo htmlspecialchars($cliente['codigo']); ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="fecha_entrega" class="form-label">Fecha de Entrega</label>
                                            <input type="date" class="form-control" id="fecha_entrega" name="fecha_entrega" 
                                                   min="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="observaciones" class="form-label">Observaciones</label>
                                    <textarea class="form-control" id="observaciones" name="observaciones" rows="3" 
                                              placeholder="Instrucciones especiales, notas de entrega, etc."></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Detalles de la orden -->
                        <div class="card mt-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5><i class="fas fa-shopping-cart"></i> Productos de la Orden</h5>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="agregarDetalle()">
                                    <i class="fas fa-plus"></i> Agregar Producto
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm" id="tabla-detalles">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="30%">Producto</th>
                                                <th width="15%">Cantidad</th>
                                                <th width="15%">Precio Unit.</th>
                                                <th width="15%">Descuento</th>
                                                <th width="15%">Subtotal</th>
                                                <th width="10%">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="detalles-container">
                                            <!-- Los detalles se agregarán dinámicamente -->
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i>
                                        Los precios se cargan automáticamente según el precio de venta del producto.
                                    </small>
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
                                    <div class="col-6">Descuento:</div>
                                    <div class="col-6 text-end" id="resumen-descuento">$0.00</div>
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
                                        La orden se creará en estado "Pendiente" y podrá ser modificada antes de procesarla.
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Información del cliente -->
                        <div class="card mt-3" id="info-cliente" style="display: none;">
                            <div class="card-header">
                                <h6><i class="fas fa-user"></i> Información del Cliente</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <small class="text-muted">Límite de Crédito:</small><br>
                                    <span id="cliente-credito">$0.00</span>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">Descuento Aplicable:</small><br>
                                    <span id="cliente-descuento">0%</span>
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
            <select class="form-select producto-select" name="detalles[][producto_id]" required>
                <option value="">Seleccionar...</option>
                <?php foreach ($productos as $producto): ?>
                    <option value="<?php echo $producto['id']; ?>" 
                            data-precio="<?php echo $producto['precio_venta']; ?>"
                            data-unidad="<?php echo $producto['unidad_medida']; ?>">
                        <?php echo htmlspecialchars($producto['nombre']); ?> 
                        (<?php echo htmlspecialchars($producto['codigo']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </td>
        <td>
            <input type="number" class="form-control cantidad-input" name="detalles[][cantidad]" 
                   min="1" step="0.01" required>
        </td>
        <td>
            <input type="number" class="form-control precio-input" name="detalles[][precio_unitario]" 
                   min="0" step="0.01" required>
        </td>
        <td>
            <input type="number" class="form-control descuento-input" name="detalles[][descuento]" 
                   min="0" step="0.01" value="0">
        </td>
        <td>
            <span class="subtotal-display">$0.00</span>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarDetalle(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
</template>

<script>
let contadorDetalles = 0;
let descuentoCliente = 0;

function agregarDetalle() {
    const template = document.getElementById('detalle-template');
    const clone = template.content.cloneNode(true);
    const container = document.getElementById('detalles-container');
    
    contadorDetalles++;
    container.appendChild(clone);
    
    // Agregar event listeners a los nuevos elementos
    const fila = container.lastElementChild;
    const productoSelect = fila.querySelector('.producto-select');
    const cantidadInput = fila.querySelector('.cantidad-input');
    const precioInput = fila.querySelector('.precio-input');
    const descuentoInput = fila.querySelector('.descuento-input');
    
    productoSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        if (option.value) {
            precioInput.value = option.dataset.precio;
            calcularSubtotalFila(fila);
        }
    });
    
    [cantidadInput, precioInput, descuentoInput].forEach(input => {
        input.addEventListener('input', function() {
            calcularSubtotalFila(fila);
        });
    });
}

function eliminarDetalle(btn) {
    btn.closest('tr').remove();
    calcularTotales();
}

function calcularSubtotalFila(fila) {
    const cantidad = parseFloat(fila.querySelector('.cantidad-input').value) || 0;
    const precio = parseFloat(fila.querySelector('.precio-input').value) || 0;
    const descuento = parseFloat(fila.querySelector('.descuento-input').value) || 0;
    
    const subtotal = (cantidad * precio) - descuento;
    fila.querySelector('.subtotal-display').textContent = '$' + subtotal.toFixed(2);
    
    calcularTotales();
}

function calcularTotales() {
    let subtotal = 0;
    let descuentoTotal = 0;
    
    document.querySelectorAll('#detalles-container tr').forEach(fila => {
        const cantidad = parseFloat(fila.querySelector('.cantidad-input').value) || 0;
        const precio = parseFloat(fila.querySelector('.precio-input').value) || 0;
        const descuento = parseFloat(fila.querySelector('.descuento-input').value) || 0;
        
        subtotal += cantidad * precio;
        descuentoTotal += descuento;
    });
    
    // Aplicar descuento del cliente
    const descuentoCliente = subtotal * (descuentoCliente / 100);
    descuentoTotal += descuentoCliente;
    
    const subtotalFinal = subtotal - descuentoTotal;
    const iva = subtotalFinal * 0.16;
    const total = subtotalFinal + iva;
    
    document.getElementById('resumen-subtotal').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('resumen-descuento').textContent = '$' + descuentoTotal.toFixed(2);
    document.getElementById('resumen-iva').textContent = '$' + iva.toFixed(2);
    document.getElementById('resumen-total').textContent = '$' + total.toFixed(2);
}

// Event listener para selección de cliente
document.getElementById('cliente_id').addEventListener('change', function() {
    const option = this.options[this.selectedIndex];
    const infoCliente = document.getElementById('info-cliente');
    
    if (option.value) {
        const credito = parseFloat(option.dataset.credito) || 0;
        descuentoCliente = parseFloat(option.dataset.descuento) || 0;
        
        document.getElementById('cliente-credito').textContent = '$' + credito.toFixed(2);
        document.getElementById('cliente-descuento').textContent = descuentoCliente + '%';
        
        infoCliente.style.display = 'block';
        calcularTotales();
    } else {
        infoCliente.style.display = 'none';
        descuentoCliente = 0;
    }
});

// Agregar primera fila al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    agregarDetalle();
});
</script>

<?php include_once VIEWS_PATH . "layouts/footer.php"; ?>