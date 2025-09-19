<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-book"></i> <?= $title ?>
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?= $this->url('dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= $this->url('produccion') ?>">Producción</a></li>
                        <li class="breadcrumb-item active">Recetas</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <!-- Filtros y acciones -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="form-inline">
                        <div class="form-group mr-3">
                            <label for="buscar" class="sr-only">Buscar</label>
                            <input type="text" class="form-control" id="buscar" name="buscar" 
                                   placeholder="Buscar recetas..." value="<?= $_GET['buscar'] ?? '' ?>">
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <a href="<?= $this->url('produccion/recetas') ?>" class="btn btn-secondary">
                            <i class="fas fa-undo"></i> Limpiar
                        </a>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <button class="btn btn-success btn-lg" data-toggle="modal" data-target="#modalNuevaReceta">
                        <i class="fas fa-plus"></i> Nueva Receta
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Lista de recetas -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> Recetas de Producción
                        <span class="badge badge-primary ml-2"><?= count($recetas) ?></span>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($recetas)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-book fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No hay recetas registradas</h4>
                        <p class="text-muted">Comienza creando tu primera receta de producción</p>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modalNuevaReceta">
                            <i class="fas fa-plus"></i> Crear Primera Receta
                        </button>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Producto</th>
                                    <th>Rendimiento</th>
                                    <th>Tiempo Prep.</th>
                                    <th>Tiempo Mad.</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recetas as $receta): ?>
                                <tr>
                                    <td>
                                        <span class="badge badge-secondary"><?= htmlspecialchars($receta['codigo']) ?></span>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($receta['nombre']) ?></strong>
                                        <?php if ($receta['descripcion']): ?>
                                        <br><small class="text-muted"><?= htmlspecialchars(substr($receta['descripcion'], 0, 50)) ?>...</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($receta['producto_nombre']) ?>
                                        <br><small class="text-muted"><?= ucfirst(str_replace('_', ' ', $receta['producto_tipo'])) ?></small>
                                    </td>
                                    <td>
                                        <?= number_format($receta['rendimiento_kg_queso'], 2) ?> kg
                                        <br><small class="text-muted">por <?= number_format($receta['rendimiento_litros_leche'], 2) ?>L</small>
                                    </td>
                                    <td>
                                        <?= intval($receta['tiempo_preparacion'] / 60) ?>h <?= $receta['tiempo_preparacion'] % 60 ?>m
                                    </td>
                                    <td>
                                        <?php if ($receta['tiempo_maduracion']): ?>
                                            <?= $receta['tiempo_maduracion'] ?> días
                                        <?php else: ?>
                                            <span class="text-muted">No requiere</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($receta['estado'] === 'activo'): ?>
                                            <span class="badge badge-success">Activo</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-info" onclick="verReceta(<?= $receta['id'] ?>)" 
                                                    title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-warning" onclick="editarReceta(<?= $receta['id'] ?>)" 
                                                    title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="<?= $this->url('produccion/lotes/crear?receta=' . $receta['id']) ?>" 
                                               class="btn btn-success" title="Crear lote">
                                                <i class="fas fa-plus"></i>
                                            </a>
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

<!-- Modal Nueva Receta -->
<div class="modal fade" id="modalNuevaReceta" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus"></i> Nueva Receta de Producción
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form method="POST" action="<?= $this->url('produccion/recetas/crear') ?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="codigo">Código de Receta *</label>
                                <input type="text" class="form-control" id="codigo" name="codigo" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="producto_id">Producto *</label>
                                <select class="form-control" id="producto_id" name="producto_id" required>
                                    <option value="">Seleccionar producto</option>
                                    <?php foreach ($productos as $producto): ?>
                                    <option value="<?= $producto['id'] ?>">
                                        <?= htmlspecialchars($producto['nombre']) ?> (<?= $producto['codigo'] ?>)
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="nombre">Nombre de la Receta *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="descripcion">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="rendimiento_litros_leche">Litros de Leche *</label>
                                <input type="number" step="0.01" class="form-control" 
                                       id="rendimiento_litros_leche" name="rendimiento_litros_leche" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="rendimiento_kg_queso">Kilogramos de Queso *</label>
                                <input type="number" step="0.01" class="form-control" 
                                       id="rendimiento_kg_queso" name="rendimiento_kg_queso" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tiempo_preparacion">Tiempo Preparación (minutos) *</label>
                                <input type="number" class="form-control" 
                                       id="tiempo_preparacion" name="tiempo_preparacion" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tiempo_maduracion">Tiempo Maduración (días)</label>
                                <input type="number" class="form-control" 
                                       id="tiempo_maduracion" name="tiempo_maduracion">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="temperatura_proceso">Temperatura (°C)</label>
                                <input type="number" step="0.1" class="form-control" 
                                       id="temperatura_proceso" name="temperatura_proceso">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ph_optimo">pH Óptimo</label>
                                <input type="number" step="0.1" class="form-control" 
                                       id="ph_optimo" name="ph_optimo">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="humedad_maduracion">Humedad (%)</label>
                                <input type="number" step="0.01" class="form-control" 
                                       id="humedad_maduracion" name="humedad_maduracion">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="instrucciones">Instrucciones</label>
                        <textarea class="form-control" id="instrucciones" name="instrucciones" rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Receta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>