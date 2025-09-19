<?php include_once VIEWS_PATH . "layouts/header.php"; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-user-plus"></i> <?php echo $title; ?></h2>
                <a href="/compras/proveedores" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver a Proveedores
                </a>
            </div>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="/compras/nuevo_proveedor">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-building"></i> Información del Proveedor</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="codigo" class="form-label">Código *</label>
                                            <input type="text" class="form-control" id="codigo" name="codigo" 
                                                   value="PROV<?php echo date('ymd') . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT); ?>" required>
                                            <div class="form-text">Código único del proveedor</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="nombre" class="form-label">Nombre/Razón Social *</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="contacto" class="form-label">Contacto Principal</label>
                                            <input type="text" class="form-control" id="contacto" name="contacto" 
                                                   placeholder="Nombre del representante">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="telefono" class="form-label">Teléfono</label>
                                            <input type="tel" class="form-control" id="telefono" name="telefono">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="rfc" class="form-label">RFC</label>
                                            <input type="text" class="form-control" id="rfc" name="rfc" 
                                                   placeholder="Registro Federal de Contribuyentes">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <textarea class="form-control" id="direccion" name="direccion" rows="3" 
                                              placeholder="Dirección completa del proveedor"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-credit-card"></i> Condiciones Comerciales</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="terminos_pago" class="form-label">Términos de Pago</label>
                                    <select class="form-select" id="terminos_pago" name="terminos_pago">
                                        <option value="contado">Contado</option>
                                        <option value="credito">Crédito</option>
                                        <option value="mixto">Mixto</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3" id="dias-credito-container" style="display: none;">
                                    <label for="dias_credito" class="form-label">Días de Crédito</label>
                                    <input type="number" class="form-control" id="dias_credito" name="dias_credito" 
                                           min="1" max="120" value="30">
                                    <div class="form-text">Días permitidos para pago a crédito</div>
                                </div>
                                
                                <hr>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fas fa-save"></i> Registrar Proveedor
                                    </button>
                                </div>
                                
                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i>
                                        El proveedor se registrará en estado "Activo" y podrá recibir órdenes de compra inmediatamente.
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Información adicional -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6><i class="fas fa-info-circle"></i> Información Adicional</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Categorías de Productos</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="cat_lacteos" name="categorias[]" value="lacteos">
                                        <label class="form-check-label" for="cat_lacteos">
                                            Lácteos
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="cat_insumos" name="categorias[]" value="insumos">
                                        <label class="form-check-label" for="cat_insumos">
                                            Insumos Químicos
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="cat_empaques" name="categorias[]" value="empaques">
                                        <label class="form-check-label" for="cat_empaques">
                                            Empaques y Envases
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="cat_servicios" name="categorias[]" value="servicios">
                                        <label class="form-check-label" for="cat_servicios">
                                            Servicios
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="certificado" name="certificado" value="1">
                                        <label class="form-check-label" for="certificado">
                                            <strong>Proveedor Certificado</strong>
                                        </label>
                                    </div>
                                    <small class="text-muted">
                                        Marcar si el proveedor cuenta con certificaciones de calidad
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

<script>
// Mostrar/ocultar campo de días de crédito según el término de pago seleccionado
document.getElementById('terminos_pago').addEventListener('change', function() {
    const diasCreditoContainer = document.getElementById('dias-credito-container');
    if (this.value === 'credito' || this.value === 'mixto') {
        diasCreditoContainer.style.display = 'block';
    } else {
        diasCreditoContainer.style.display = 'none';
    }
});

// Validación de formulario
document.querySelector('form').addEventListener('submit', function(e) {
    const nombre = document.getElementById('nombre').value.trim();
    const codigo = document.getElementById('codigo').value.trim();
    
    if (!nombre || !codigo) {
        e.preventDefault();
        alert('Por favor, complete los campos requeridos (Código y Nombre).');
        return false;
    }
    
    // Validación de RFC (formato básico)
    const rfc = document.getElementById('rfc').value.trim();
    if (rfc && rfc.length < 12) {
        e.preventDefault();
        alert('El RFC debe tener al menos 12 caracteres.');
        return false;
    }
    
    return true;
});
</script>

<?php include_once VIEWS_PATH . "layouts/footer.php"; ?>