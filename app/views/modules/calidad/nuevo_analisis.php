<?php include_once VIEWS_PATH . "layouts/header.php"; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-microscope"></i> <?php echo $title; ?></h2>
                <a href="/calidad/analisis" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver a Análisis
                </a>
            </div>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="/calidad/nuevo_analisis">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-flask"></i> Datos del Análisis</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="tipo" class="form-label">Tipo de Análisis *</label>
                                            <select class="form-select" id="tipo" name="tipo" required onchange="cambiarTipo()">
                                                <option value="">Seleccionar tipo...</option>
                                                <option value="materia_prima">Materia Prima</option>
                                                <option value="producto_terminado">Producto Terminado</option>
                                                <option value="proceso">Proceso (Lote)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="fecha_analisis" class="form-label">Fecha del Análisis *</label>
                                            <input type="date" class="form-control" id="fecha_analisis" 
                                                   name="fecha_analisis" value="<?php echo date('Y-m-d'); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <!-- Item - Materia Prima -->
                                    <div class="col-md-6" id="campo-materia-prima" style="display:none;">
                                        <div class="mb-3">
                                            <label for="materia_prima_id" class="form-label">Materia Prima *</label>
                                            <select class="form-select" id="materia_prima_id" name="item_id">
                                                <option value="">Seleccionar materia prima...</option>
                                                <?php foreach ($materias_primas as $materia): ?>
                                                    <option value="<?php echo $materia['id']; ?>">
                                                        <?php echo htmlspecialchars($materia['nombre']); ?> 
                                                        (<?php echo htmlspecialchars($materia['codigo']); ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <!-- Item - Producto -->
                                    <div class="col-md-6" id="campo-producto" style="display:none;">
                                        <div class="mb-3">
                                            <label for="producto_id" class="form-label">Producto *</label>
                                            <select class="form-select" id="producto_id" name="item_id">
                                                <option value="">Seleccionar producto...</option>
                                                <?php foreach ($productos as $producto): ?>
                                                    <option value="<?php echo $producto['id']; ?>">
                                                        <?php echo htmlspecialchars($producto['nombre']); ?> 
                                                        (<?php echo htmlspecialchars($producto['codigo']); ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <!-- Lote de Producción -->
                                    <div class="col-md-6" id="campo-lote">
                                        <div class="mb-3">
                                            <label for="lote_produccion_id" class="form-label">Lote de Producción</label>
                                            <select class="form-select" id="lote_produccion_id" name="lote_produccion_id">
                                                <option value="">Seleccionar lote (opcional)...</option>
                                                <?php foreach ($lotes_disponibles as $lote): ?>
                                                    <option value="<?php echo $lote['id']; ?>">
                                                        <?php echo htmlspecialchars($lote['numero_lote']); ?> - En Proceso
                                                    </option>
                                                <?php endforeach; ?>
                                                <?php foreach ($lotes_terminados as $lote): ?>
                                                    <option value="<?php echo $lote['id']; ?>">
                                                        <?php echo htmlspecialchars($lote['numero_lote']); ?> - Terminado
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Parámetros de Análisis -->
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h6><i class="fas fa-chart-line"></i> Parámetros Medidos</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="ph" class="form-label">pH</label>
                                                    <input type="number" class="form-control" id="ph" name="ph" 
                                                           step="0.1" min="0" max="14">
                                                    <div class="form-text">Valor entre 0 y 14</div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="humedad" class="form-label">Humedad (%)</label>
                                                    <input type="number" class="form-control" id="humedad" name="humedad" 
                                                           step="0.01" min="0" max="100">
                                                    <div class="form-text">Porcentaje de humedad</div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="temperatura" class="form-label">Temperatura (°C)</label>
                                                    <input type="number" class="form-control" id="temperatura" name="temperatura" 
                                                           step="0.1">
                                                    <div class="form-text">Temperatura al momento del análisis</div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="grasa" class="form-label">Grasa (%)</label>
                                                    <input type="number" class="form-control" id="grasa" name="grasa" 
                                                           step="0.01" min="0" max="100">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="proteina" class="form-label">Proteína (%)</label>
                                                    <input type="number" class="form-control" id="proteina" name="proteina" 
                                                           step="0.01" min="0" max="100">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="sal" class="form-label">Sal (%)</label>
                                                    <input type="number" class="form-control" id="sal" name="sal" 
                                                           step="0.01" min="0" max="100">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Análisis Microbiológico -->
                                <div class="mb-3">
                                    <label for="microbiologia" class="form-label">Análisis Microbiológico</label>
                                    <textarea class="form-control" id="microbiologia" name="microbiologia" rows="3"
                                              placeholder="Resultados del análisis microbiológico, coliformes, bacterias, etc."></textarea>
                                </div>
                                
                                <!-- Observaciones -->
                                <div class="mb-3">
                                    <label for="observaciones" class="form-label">Observaciones</label>
                                    <textarea class="form-control" id="observaciones" name="observaciones" rows="3"
                                              placeholder="Observaciones adicionales, defectos visuales, olores, textura, etc."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-clipboard-check"></i> Resultado del Análisis</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="resultado" class="form-label">Resultado *</label>
                                    <select class="form-select" id="resultado" name="resultado" required>
                                        <option value="">Seleccionar resultado...</option>
                                        <option value="conforme" class="text-success">✓ Conforme</option>
                                        <option value="no_conforme" class="text-danger">✗ No Conforme</option>
                                        <option value="requiere_revision" class="text-warning">⚠ Requiere Revisión</option>
                                    </select>
                                </div>
                                
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle"></i> Criterios de Evaluación</h6>
                                    <ul class="mb-0 small">
                                        <li><strong>Conforme:</strong> Cumple todos los estándares</li>
                                        <li><strong>No Conforme:</strong> No cumple uno o más criterios</li>
                                        <li><strong>Requiere Revisión:</strong> Valores límite o dudosos</li>
                                    </ul>
                                </div>
                                
                                <div class="mb-3">
                                    <h6>Parámetros de Referencia</h6>
                                    <div class="small">
                                        <strong>Queso Fresco:</strong><br>
                                        • pH: 5.0 - 6.0<br>
                                        • Humedad: 55-70%<br>
                                        • Grasa: 18-25%<br><br>
                                        
                                        <strong>Queso Curado:</strong><br>
                                        • pH: 5.2 - 5.8<br>
                                        • Humedad: 35-45%<br>
                                        • Grasa: 26-35%<br>
                                    </div>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fas fa-save"></i> Registrar Análisis
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
function cambiarTipo() {
    const tipo = document.getElementById('tipo').value;
    const materiaField = document.getElementById('campo-materia-prima');
    const productoField = document.getElementById('campo-producto');
    const loteField = document.getElementById('campo-lote');
    
    // Ocultar todos los campos
    materiaField.style.display = 'none';
    productoField.style.display = 'none';
    
    // Limpiar selecciones
    document.getElementById('materia_prima_id').required = false;
    document.getElementById('producto_id').required = false;
    
    // Mostrar campo según tipo
    switch(tipo) {
        case 'materia_prima':
            materiaField.style.display = 'block';
            document.getElementById('materia_prima_id').required = true;
            loteField.style.display = 'none';
            break;
        case 'producto_terminado':
            productoField.style.display = 'block';
            document.getElementById('producto_id').required = true;
            loteField.style.display = 'block';
            break;
        case 'proceso':
            loteField.style.display = 'block';
            document.getElementById('lote_produccion_id').required = true;
            // Para análisis de proceso, el item_id será el lote
            document.getElementById('lote_produccion_id').addEventListener('change', function() {
                // Copiar el valor del lote al campo item_id para proceso
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'item_id';
                hiddenInput.value = this.value;
                this.form.appendChild(hiddenInput);
            });
            break;
    }
}

// Auto-llenar parámetros según el producto seleccionado
document.getElementById('producto_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const productName = selectedOption.text.toLowerCase();
    
    // Sugerir valores típicos según el tipo de producto
    if (productName.includes('fresco')) {
        document.getElementById('ph').placeholder = '5.0 - 6.0';
        document.getElementById('humedad').placeholder = '55 - 70';
        document.getElementById('grasa').placeholder = '18 - 25';
    } else if (productName.includes('curado')) {
        document.getElementById('ph').placeholder = '5.2 - 5.8';
        document.getElementById('humedad').placeholder = '35 - 45';
        document.getElementById('grasa').placeholder = '26 - 35';
    }
});

// Validación del resultado según parámetros
document.getElementById('ph').addEventListener('input', validarParametros);
document.getElementById('humedad').addEventListener('input', validarParametros);
document.getElementById('grasa').addEventListener('input', validarParametros);

function validarParametros() {
    const ph = parseFloat(document.getElementById('ph').value);
    const humedad = parseFloat(document.getElementById('humedad').value);
    const grasa = parseFloat(document.getElementById('grasa').value);
    const resultadoSelect = document.getElementById('resultado');
    
    // Sugerir resultado basado en parámetros (ejemplo para queso fresco)
    if (ph && humedad && grasa) {
        if (ph >= 5.0 && ph <= 6.0 && humedad >= 55 && humedad <= 70 && grasa >= 18 && grasa <= 25) {
            resultadoSelect.value = 'conforme';
            resultadoSelect.className = 'form-select text-success';
        } else if (ph < 4.5 || ph > 7.0 || humedad < 40 || humedad > 80 || grasa < 10 || grasa > 40) {
            resultadoSelect.value = 'no_conforme';
            resultadoSelect.className = 'form-select text-danger';
        } else {
            resultadoSelect.value = 'requiere_revision';
            resultadoSelect.className = 'form-select text-warning';
        }
    }
}

// Pre-seleccionar lote si viene por parámetro
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const loteId = urlParams.get('lote');
    if (loteId) {
        document.getElementById('tipo').value = 'proceso';
        cambiarTipo();
        document.getElementById('lote_produccion_id').value = loteId;
    }
});
</script>

<?php include_once VIEWS_PATH . "layouts/footer.php"; ?>