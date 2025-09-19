<?php include_once VIEWS_PATH . "layouts/header.php"; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-file-alt"></i> <?php echo $title; ?></h2>
                <a href="/compras/nueva_orden" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nueva Orden
                </a>
            </div>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-body">
                    <?php if (empty($ordenes)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay órdenes de compra registradas</h5>
                            <p class="text-muted">Comienza creando tu primera orden de compra</p>
                            <a href="/compras/nueva_orden" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Crear Primera Orden
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Número de Orden</th>
                                        <th>Proveedor</th>
                                        <th>Fecha Orden</th>
                                        <th>Fecha Entrega</th>
                                        <th>Estado</th>
                                        <th>Total</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ordenes as $orden): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($orden['numero_orden']); ?></strong>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($orden['proveedor_nombre']); ?><br>
                                                <small class="text-muted"><?php echo htmlspecialchars($orden['proveedor_tipo']); ?></small>
                                            </td>
                                            <td><?php echo date('d/m/Y', strtotime($orden['fecha_orden'])); ?></td>
                                            <td>
                                                <?php if ($orden['fecha_entrega_esperada']): ?>
                                                    <?php echo date('d/m/Y', strtotime($orden['fecha_entrega_esperada'])); ?>
                                                <?php else: ?>
                                                    <span class="text-muted">No definida</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                $estadoClass = [
                                                    'borrador' => 'secondary',
                                                    'enviado' => 'warning', 
                                                    'confirmado' => 'info',
                                                    'recibido' => 'success',
                                                    'cancelado' => 'danger'
                                                ];
                                                $class = $estadoClass[$orden['estado']] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?php echo $class; ?>">
                                                    <?php echo ucfirst($orden['estado']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <strong>$<?php echo number_format($orden['total'], 2); ?></strong>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="/compras/ver_orden/<?php echo $orden['id']; ?>" 
                                                       class="btn btn-sm btn-outline-primary" title="Ver">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if ($orden['estado'] === 'borrador'): ?>
                                                        <button class="btn btn-sm btn-outline-warning" title="Enviar">
                                                            <i class="fas fa-paper-plane"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    <?php if ($orden['estado'] === 'confirmado'): ?>
                                                        <button class="btn btn-sm btn-outline-success" title="Recibir">
                                                            <i class="fas fa-check"></i>
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

<?php include_once VIEWS_PATH . "layouts/footer.php"; ?>
