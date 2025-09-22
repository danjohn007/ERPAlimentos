<?php include_once VIEWS_PATH . "layouts/header.php"; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-boxes"></i> <?= $title ?>
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
                        <li class="breadcrumb-item active">
                            <span class="btn btn-success btn-sm disabled">
                                <i class="fas fa-boxes"></i> Lotes
                            </span>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <!-- Estadísticas de Lotes -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <?php 
                            $programados = array_filter($lotes, function($l) { return $l['estado'] === 'programado'; });
                            ?>
                            <h4 class="card-title"><?= count($programados) ?></h4>
                            <p class="card-text">Programados</p>
                        </div>
                        <i class="fas fa-calendar-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <?php 
                            $enProceso = array_filter($lotes, function($l) { return $l['estado'] === 'en_proceso'; });
                            ?>
                            <h4 class="card-title"><?= count($enProceso) ?></h4>
                            <p class="card-text">En Proceso</p>
                        </div>
                        <i class="fas fa-cogs fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <?php 
                            $maduracion = array_filter($lotes, function($l) { return $l['estado'] === 'maduracion'; });
                            ?>
                            <h4 class="card-title"><?= count($maduracion) ?></h4>
                            <p class="card-text">Madurando</p>
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
                            <?php 
                            $terminados = array_filter($lotes, function($l) { return $l['estado'] === 'terminado'; });
                            ?>
                            <h4 class="card-title"><?= count($terminados) ?></h4>
                            <p class="card-text">Terminados</p>
                        </div>
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Acciones Rápidas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt"></i> Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body action-buttons">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="<?= $this->url('produccion/lotes/crear') ?>" class="btn btn-primary btn-block">
                                <i class="fas fa-plus"></i> Nuevo Lote
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-info btn-block" onclick="filtrarPorEstado('en_proceso')">
                                <i class="fas fa-filter"></i> Ver En Proceso
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-warning btn-block" onclick="filtrarPorEstado('maduracion')">
                                <i class="fas fa-clock"></i> Ver Madurando
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-success btn-block" onclick="filtrarPorEstado('terminado')">
                                <i class="fas fa-check"></i> Ver Terminados
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-search"></i> Filtros y Búsqueda
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="filtro-estado">Estado</label>
                            <select class="form-control" id="filtro-estado" onchange="aplicarFiltros()">
                                <option value="">Todos los estados</option>
                                <option value="programado">Programado</option>
                                <option value="en_proceso">En Proceso</option>
                                <option value="maduracion">Madurando</option>
                                <option value="terminado">Terminado</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filtro-producto">Producto</label>
                            <select class="form-control" id="filtro-producto" onchange="aplicarFiltros()">
                                <option value="">Todos los productos</option>
                                <?php 
                                $productos = array_unique(array_column($lotes, 'producto'));
                                foreach ($productos as $producto): 
                                ?>
                                    <option value="<?= htmlspecialchars($producto) ?>"><?= htmlspecialchars($producto) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filtro-fecha">Fecha</label>
                            <input type="date" class="form-control" id="filtro-fecha" onchange="aplicarFiltros()">
                        </div>
                        <div class="col-md-3">
                            <label for="buscar-lote">Buscar</label>
                            <input type="text" class="form-control" id="buscar-lote" 
                                   placeholder="Número de lote..." onkeyup="aplicarFiltros()">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Lista de Lotes -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> Lista de Lotes
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($lotes)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-boxes fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No hay lotes registrados</h4>
                        <p class="text-muted">Comience creando su primer lote de producción</p>
                        <a href="<?= $this->url('produccion/lotes/crear') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Crear Primer Lote
                        </a>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tabla-lotes">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Número Lote</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Estado</th>
                                    <th>Fecha Inicio</th>
                                    <th>Responsable</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lotes as $lote): ?>
                                <tr data-estado="<?= $lote['estado'] ?>" 
                                    data-producto="<?= strtolower($lote['producto']) ?>"
                                    data-fecha="<?= $lote['fecha_inicio'] ?>"
                                    data-numero="<?= strtolower($lote['numero_lote']) ?>">
                                    <td>
                                        <strong><?= htmlspecialchars($lote['numero_lote']) ?></strong>
                                    </td>
                                    <td><?= htmlspecialchars($lote['producto'] ?? 'Sin producto') ?></td>
                                    <td><?= number_format($lote['cantidad_planificada'] ?? 0, 1) ?> kg</td>
                                    <td>
                                        <?php 
                                        $estados = [
                                            'programado' => '<span class="badge badge-info">Programado</span>',
                                            'en_proceso' => '<span class="badge badge-warning">En Proceso</span>',
                                            'maduracion' => '<span class="badge badge-primary">Madurando</span>',
                                            'terminado' => '<span class="badge badge-success">Terminado</span>',
                                            'cancelado' => '<span class="badge badge-danger">Cancelado</span>'
                                        ];
                                        echo $estados[$lote['estado']] ?? '<span class="badge badge-secondary">Desconocido</span>';
                                        ?>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($lote['fecha_inicio'])) ?></td>
                                    <td><?= htmlspecialchars($lote['responsable'] ?? 'Sin asignar') ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= $this->url('produccion/lotes/ver/' . $lote['id']) ?>" 
                                               class="btn btn-info" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button class="btn btn-warning" 
                                                    onclick="editarLote(<?= $lote['id'] ?>)" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <?php if ($lote['estado'] === 'programado'): ?>
                                                <button class="btn btn-success" 
                                                        onclick="iniciarLote(<?= $lote['id'] ?>)" title="Iniciar">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                            <?php endif; ?>
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
// Funciones para la gestión de lotes
function aplicarFiltros() {
    const estado = document.getElementById('filtro-estado').value.toLowerCase();
    const producto = document.getElementById('filtro-producto').value.toLowerCase();
    const fecha = document.getElementById('filtro-fecha').value;
    const busqueda = document.getElementById('buscar-lote').value.toLowerCase();
    
    const filas = document.querySelectorAll('#tabla-lotes tbody tr');
    
    filas.forEach(fila => {
        const estadoFila = fila.getAttribute('data-estado');
        const productoFila = fila.getAttribute('data-producto');
        const fechaFila = fila.getAttribute('data-fecha');
        const numeroFila = fila.getAttribute('data-numero');
        
        let mostrar = true;
        
        if (estado && estadoFila !== estado) mostrar = false;
        if (producto && !productoFila.includes(producto)) mostrar = false;
        if (fecha && !fechaFila.includes(fecha)) mostrar = false;
        if (busqueda && !numeroFila.includes(busqueda)) mostrar = false;
        
        fila.style.display = mostrar ? '' : 'none';
    });
}

function filtrarPorEstado(estado) {
    document.getElementById('filtro-estado').value = estado;
    aplicarFiltros();
}

function editarLote(id) {
    alert(`Editar lote ID: ${id}\n\nEsta funcionalidad permitirá:\n• Modificar cantidad\n• Cambiar responsable\n• Actualizar observaciones\n\n(En desarrollo)`);
}

function iniciarLote(id) {
    if (confirm('¿Está seguro de que desea iniciar este lote de producción?')) {
        alert(`Lote ${id} iniciado\n\nEsta funcionalidad:\n• Cambiará el estado a "En Proceso"\n• Registrará hora de inicio\n• Enviará notificaciones\n\n(En desarrollo)`);
    }
}
</script>

<?php include_once VIEWS_PATH . "layouts/footer.php"; ?>