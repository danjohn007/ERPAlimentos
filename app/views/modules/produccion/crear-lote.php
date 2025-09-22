<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-plus"></i> <?= $title ?>
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 custom-breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?= $this->url('dashboard') ?>" class="btn btn-outline-primary btn-sm breadcrumb-btn">
                                <i class="fas fa-home"></i> Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?= $this->url('produccion') ?>" class="btn btn-outline-info btn-sm breadcrumb-btn">
                                <i class="fas fa-industry"></i> Producción
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="<?= $this->url('produccion/lotes') ?>" class="btn btn-outline-secondary btn-sm breadcrumb-btn">
                                <i class="fas fa-boxes"></i> Lotes
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <span class="btn btn-success btn-sm disabled">
                                <i class="fas fa-plus"></i> Crear Lote
                            </span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-form"></i> Información del Lote de Producción
                    </h5>
                </div>
                <form method="POST" action="<?= $this->url('produccion/lotes/crear') ?>">
                    <div class="card-body">
                        <?php if (isset($_SESSION['flash_message'])): ?>
                        <div class="alert alert-<?= $_SESSION['flash_type'] ?? 'info' ?> alert-dismissible fade show">
                            <?= htmlspecialchars($_SESSION['flash_message']) ?>
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                        <?php 
                        unset($_SESSION['flash_message']);
                        unset($_SESSION['flash_type']);
                        endif; 
                        ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="receta_id">Receta de Producción *</label>
                                    <select class="form-control" id="receta_id" name="receta_id" required onchange="cargarDatosReceta()">
                                        <option value="">Seleccionar receta</option>
                                        <?php foreach ($recetas as $receta): ?>
                                        <option value="<?= $receta['id'] ?>" 
                                                data-producto="<?= htmlspecialchars($receta['producto_nombre']) ?>"
                                                data-rendimiento-leche="<?= $receta['rendimiento_litros_leche'] ?>"
                                                data-rendimiento-queso="<?= $receta['rendimiento_kg_queso'] ?>"
                                                data-tiempo-prep="<?= $receta['tiempo_preparacion'] ?>"
                                                data-tiempo-mad="<?= $receta['tiempo_maduracion'] ?? 0 ?>"
                                                data-temperatura="<?= $receta['temperatura_proceso'] ?? '' ?>"
                                                data-ph="<?= $receta['ph_optimo'] ?? '' ?>"
                                                <?= (isset($_GET['receta']) && $_GET['receta'] == $receta['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($receta['codigo']) ?> - <?= htmlspecialchars($receta['nombre']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="producto_info">Producto a Elaborar</label>
                                    <input type="text" class="form-control" id="producto_info" readonly 
                                           placeholder="Selecciona una receta">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_inicio">Fecha y Hora de Inicio *</label>
                                    <input type="datetime-local" class="form-control" id="fecha_inicio" 
                                           name="fecha_inicio" value="<?= date('Y-m-d\TH:i') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cantidad_programada">Cantidad Programada (kg) *</label>
                                    <input type="number" step="0.01" class="form-control" 
                                           id="cantidad_programada" name="cantidad_programada" required
                                           onchange="calcularLitrosLeche()">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="litros_leche_estimados">Litros de Leche Estimados</label>
                                    <input type="number" step="0.01" class="form-control" 
                                           id="litros_leche_estimados" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tiempo_estimado">Tiempo Estimado de Proceso</label>
                                    <input type="text" class="form-control" id="tiempo_estimado" readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="operador_id">Operador Asignado</label>
                                    <select class="form-control" id="operador_id" name="operador_id">
                                        <option value="">Sin asignar</option>
                                        <?php foreach ($operadores as $operador): ?>
                                        <option value="<?= $operador['id'] ?>">
                                            <?= htmlspecialchars($operador['nombre'] . ' ' . $operador['apellidos']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="supervisor_id">Supervisor Asignado</label>
                                    <select class="form-control" id="supervisor_id" name="supervisor_id">
                                        <option value="">Sin asignar</option>
                                        <?php foreach ($supervisores as $supervisor): ?>
                                        <option value="<?= $supervisor['id'] ?>">
                                            <?= htmlspecialchars($supervisor['nombre'] . ' ' . $supervisor['apellidos']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" 
                                      rows="3" placeholder="Observaciones especiales para este lote..."></textarea>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="<?= $this->url('produccion/lotes') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Crear Lote de Producción
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Información de la receta seleccionada -->
            <div class="card" id="info-receta" style="display: none;">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i> Información de la Receta
                    </h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-6">Rendimiento:</dt>
                        <dd class="col-sm-6" id="info-rendimiento"></dd>
                        
                        <dt class="col-sm-6">Preparación:</dt>
                        <dd class="col-sm-6" id="info-tiempo-prep"></dd>
                        
                        <dt class="col-sm-6">Maduración:</dt>
                        <dd class="col-sm-6" id="info-tiempo-mad"></dd>
                        
                        <dt class="col-sm-6">Temperatura:</dt>
                        <dd class="col-sm-6" id="info-temperatura"></dd>
                        
                        <dt class="col-sm-6">pH Óptimo:</dt>
                        <dd class="col-sm-6" id="info-ph"></dd>
                    </dl>
                </div>
            </div>
            
            <!-- Consejos de producción -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-lightbulb"></i> Consejos de Producción
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Verifica la temperatura de la leche antes de comenzar
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Asegúrate de tener todos los insumos disponibles
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Confirma que el equipo esté limpio y calibrado
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success"></i>
                            Documenta cada paso del proceso
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function cargarDatosReceta() {
    const select = document.getElementById('receta_id');
    const option = select.options[select.selectedIndex];
    
    if (option.value) {
        // Mostrar información de la receta
        document.getElementById('info-receta').style.display = 'block';
        document.getElementById('producto_info').value = option.getAttribute('data-producto');
        
        // Cargar datos en el panel de información
        const rendimientoLeche = parseFloat(option.getAttribute('data-rendimiento-leche'));
        const rendimientoQueso = parseFloat(option.getAttribute('data-rendimiento-queso'));
        
        document.getElementById('info-rendimiento').textContent = 
            `${rendimientoQueso} kg por ${rendimientoLeche} L`;
        
        const tiempoPrep = parseInt(option.getAttribute('data-tiempo-prep'));
        const horas = Math.floor(tiempoPrep / 60);
        const minutos = tiempoPrep % 60;
        document.getElementById('info-tiempo-prep').textContent = 
            `${horas}h ${minutos}m`;
        
        const tiempoMad = parseInt(option.getAttribute('data-tiempo-mad'));
        document.getElementById('info-tiempo-mad').textContent = 
            tiempoMad > 0 ? `${tiempoMad} días` : 'No requiere';
        
        const temperatura = option.getAttribute('data-temperatura');
        document.getElementById('info-temperatura').textContent = 
            temperatura ? `${temperatura}°C` : 'No especificada';
        
        const ph = option.getAttribute('data-ph');
        document.getElementById('info-ph').textContent = 
            ph ? ph : 'No especificado';
        
        // Calcular litros de leche si hay cantidad programada
        calcularLitrosLeche();
        
        // Mostrar tiempo estimado
        document.getElementById('tiempo_estimado').value = `${horas}h ${minutos}m`;
        
    } else {
        document.getElementById('info-receta').style.display = 'none';
        document.getElementById('producto_info').value = '';
        document.getElementById('litros_leche_estimados').value = '';
        document.getElementById('tiempo_estimado').value = '';
    }
}

function calcularLitrosLeche() {
    const select = document.getElementById('receta_id');
    const option = select.options[select.selectedIndex];
    const cantidadProgramada = parseFloat(document.getElementById('cantidad_programada').value);
    
    if (option.value && cantidadProgramada > 0) {
        const rendimientoLeche = parseFloat(option.getAttribute('data-rendimiento-leche'));
        const rendimientoQueso = parseFloat(option.getAttribute('data-rendimiento-queso'));
        
        const litrosNecesarios = (cantidadProgramada * rendimientoLeche) / rendimientoQueso;
        document.getElementById('litros_leche_estimados').value = litrosNecesarios.toFixed(2);
    } else {
        document.getElementById('litros_leche_estimados').value = '';
    }
}

// Cargar datos si hay una receta preseleccionada
document.addEventListener('DOMContentLoaded', function() {
    const recetaSelect = document.getElementById('receta_id');
    if (recetaSelect.value) {
        cargarDatosReceta();
    }
});
</script>