<?php include_once VIEWS_PATH . "layouts/header.php"; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-user-plus"></i> <?php echo $title; ?></h2>
                <a href="/ventas/clientes" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver a Clientes
                </a>
            </div>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="/ventas/nuevo_cliente">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-user"></i> Información del Cliente</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="codigo" class="form-label">Código *</label>
                                            <input type="text" class="form-control" id="codigo" name="codigo" 
                                                   value="CLI<?php echo date('ymd') . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT); ?>" required>
                                            <div class="form-text">Código único del cliente</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="tipo" class="form-label">Tipo de Cliente *</label>
                                            <select class="form-select" id="tipo" name="tipo" required>
                                                <option value="">Seleccionar tipo...</option>
                                                <option value="mayorista">Mayorista</option>
                                                <option value="minorista">Minorista</option>
                                                <option value="distribuidor">Distribuidor</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre o Razón Social *</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="contacto" class="form-label">Persona de Contacto</label>
                                            <input type="text" class="form-control" id="contacto" name="contacto">
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
                                            <input type="text" class="form-control" id="rfc" name="rfc" maxlength="13">
                                            <div class="form-text">RFC para facturación</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <textarea class="form-control" id="direccion" name="direccion" rows="3"></textarea>
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
                                    <label for="credito_limite" class="form-label">Límite de Crédito</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" id="credito_limite" 
                                               name="credito_limite" step="0.01" min="0" value="0">
                                    </div>
                                    <div class="form-text">Monto máximo de crédito autorizado</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="descuento_porcentaje" class="form-label">Descuento (%)</label>
                                    <input type="number" class="form-control" id="descuento_porcentaje" 
                                           name="descuento_porcentaje" step="0.01" min="0" max="100" value="0">
                                    <div class="form-text">Descuento por defecto en las ventas</div>
                                </div>
                                
                                <hr>
                                
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle"></i> Información</h6>
                                    <ul class="mb-0 small">
                                        <li>Los campos marcados con * son obligatorios</li>
                                        <li>El código debe ser único</li>
                                        <li>El límite de crédito se puede ajustar después</li>
                                    </ul>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fas fa-save"></i> Crear Cliente
                                    </button>
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
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generar código basado en el nombre
    const nombreInput = document.getElementById('nombre');
    const codigoInput = document.getElementById('codigo');
    
    nombreInput.addEventListener('blur', function() {
        if (this.value && !codigoInput.dataset.modified) {
            const nombre = this.value.trim();
            const palabras = nombre.split(' ');
            let codigo = 'CLI';
            
            if (palabras.length >= 2) {
                codigo += palabras[0].substring(0, 2).toUpperCase() + 
                         palabras[1].substring(0, 2).toUpperCase();
            } else {
                codigo += palabras[0].substring(0, 4).toUpperCase();
            }
            
            codigo += String(Date.now()).slice(-3);
            codigoInput.value = codigo;
        }
    });
    
    codigoInput.addEventListener('input', function() {
        this.dataset.modified = 'true';
    });
    
    // Validación del RFC
    const rfcInput = document.getElementById('rfc');
    rfcInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
});
</script>

<?php include_once VIEWS_PATH . "layouts/footer.php"; ?>