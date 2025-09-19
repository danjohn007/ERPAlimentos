<?php include_once VIEWS_PATH . "layouts/header.php"; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2><i class="fas fa-shopping-cart"></i> <?php echo $title; ?></h2>
            
            <!-- Resumen de Compras -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Órdenes Pendientes</h5>
                                    <h3><?php echo count($ordenes_pendientes); ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-file-alt fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>En Proceso</h5>
                                    <h3><?php echo count($ordenes_en_proceso); ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Por Recibir</h5>
                                    <h3><?php echo count($ordenes_por_recibir); ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-truck fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Proveedores</h5>
                                    <h3><?php echo count($proveedores_activos); ?></h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
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
                            <h5><i class="fas fa-bolt"></i> Acciones Rápidas</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 text-center mb-3">
                                    <a href="/compras/nueva_orden" class="btn btn-primary btn-lg btn-block">
                                        <i class="fas fa-plus"></i><br>
                                        Nueva Orden de Compra
                                    </a>
                                </div>
                                <div class="col-md-3 text-center mb-3">
                                    <a href="/compras/ordenes" class="btn btn-info btn-lg btn-block">
                                        <i class="fas fa-list"></i><br>
                                        Ver Todas las Órdenes
                                    </a>
                                </div>
                                <div class="col-md-3 text-center mb-3">
                                    <a href="/compras/recepcion" class="btn btn-success btn-lg btn-block">
                                        <i class="fas fa-inbox"></i><br>
                                        Recepción de Mercancía
                                    </a>
                                </div>
                                <div class="col-md-3 text-center mb-3">
                                    <a href="/compras/proveedores" class="btn btn-secondary btn-lg btn-block">
                                        <i class="fas fa-building"></i><br>
                                        Gestión de Proveedores
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Órdenes Recientes -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-file-alt"></i> Órdenes Pendientes</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($ordenes_pendientes)): ?>
                                <p class="text-muted">No hay órdenes pendientes.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Número</th>
                                                <th>Proveedor</th>
                                                <th>Total</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($ordenes_pendientes as $orden): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($orden['numero_orden']); ?></td>
                                                    <td><?php echo htmlspecialchars($orden['proveedor_nombre']); ?></td>
                                                    <td>$<?php echo number_format($orden['total'], 2); ?></td>
                                                    <td>
                                                        <a href="/compras/ver_orden/<?php echo $orden['id']; ?>" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
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
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-truck"></i> Por Recibir</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($ordenes_por_recibir)): ?>
                                <p class="text-muted">No hay órdenes por recibir.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Número</th>
                                                <th>Proveedor</th>
                                                <th>Fecha Esperada</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($ordenes_por_recibir as $orden): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($orden['numero_orden']); ?></td>
                                                    <td><?php echo htmlspecialchars($orden['proveedor_nombre']); ?></td>
                                                    <td><?php echo date('d/m/Y', strtotime($orden['fecha_entrega_esperada'])); ?></td>
                                                    <td>
                                                        <a href="/compras/ver_orden/<?php echo $orden['id']; ?>" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
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
    </div>
</div>

<?php include_once VIEWS_PATH . "layouts/footer.php"; ?>
