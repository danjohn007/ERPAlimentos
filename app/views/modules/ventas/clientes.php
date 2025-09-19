<?php include_once VIEWS_PATH . "layouts/header.php"; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-users"></i> <?php echo $title; ?></h2>
                <a href="/ventas/nuevo_cliente" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Cliente
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
            
            <!-- Resumen de Clientes -->
            <div class="row mb-4">
                <?php
                $tiposClientes = ['mayorista' => 0, 'minorista' => 0, 'distribuidor' => 0];
                foreach ($clientes as $cliente) {
                    if (isset($tiposClientes[$cliente['tipo']])) {
                        $tiposClientes[$cliente['tipo']]++;
                    }
                }
                ?>
                
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h5>Total Clientes</h5>
                            <h3><?php echo count($clientes); ?></h3>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h5>Mayoristas</h5>
                            <h3><?php echo $tiposClientes['mayorista']; ?></h3>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h5>Minoristas</h5>
                            <h3><?php echo $tiposClientes['minorista']; ?></h3>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h5>Distribuidores</h5>
                            <h3><?php echo $tiposClientes['distribuidor']; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0"><i class="fas fa-list"></i> Lista de Clientes</h5>
                        </div>
                        <div class="col-auto">
                            <div class="input-group">
                                <input type="text" class="form-control" id="buscar-cliente" placeholder="Buscar cliente...">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($clientes)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay clientes registrados</h5>
                            <p class="text-muted">Comienza registrando tu primer cliente</p>
                            <a href="/ventas/nuevo_cliente" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Registrar Primer Cliente
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped" id="tabla-clientes">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Tipo</th>
                                        <th>Contacto</th>
                                        <th>Teléfono</th>
                                        <th>Límite de Crédito</th>
                                        <th>Descuento</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($clientes as $cliente): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($cliente['codigo']); ?></strong>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong><?php echo htmlspecialchars($cliente['nombre']); ?></strong>
                                                    <?php if ($cliente['rfc']): ?>
                                                        <br><small class="text-muted">RFC: <?php echo htmlspecialchars($cliente['rfc']); ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php
                                                $tipoClass = [
                                                    'mayorista' => 'primary',
                                                    'minorista' => 'info', 
                                                    'distribuidor' => 'warning'
                                                ];
                                                $class = $tipoClass[$cliente['tipo']] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?php echo $class; ?>">
                                                    <?php echo ucfirst($cliente['tipo']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($cliente['contacto']); ?>
                                                <?php if ($cliente['email']): ?>
                                                    <br><small class="text-muted"><?php echo htmlspecialchars($cliente['email']); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($cliente['telefono']); ?></td>
                                            <td>
                                                <strong>$<?php echo number_format($cliente['credito_limite'], 2); ?></strong>
                                            </td>
                                            <td>
                                                <?php if ($cliente['descuento_porcentaje'] > 0): ?>
                                                    <span class="badge bg-success"><?php echo $cliente['descuento_porcentaje']; ?>%</span>
                                                <?php else: ?>
                                                    <span class="text-muted">Sin descuento</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $cliente['estado'] === 'activo' ? 'success' : 'danger'; ?>">
                                                    <?php echo ucfirst($cliente['estado']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="/ventas/ver_cliente/<?php echo $cliente['id']; ?>" 
                                                       class="btn btn-sm btn-outline-primary" title="Ver">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="/ventas/editar_cliente/<?php echo $cliente['id']; ?>" 
                                                       class="btn btn-sm btn-outline-warning" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="/ventas/nueva_orden?cliente_id=<?php echo $cliente['id']; ?>" 
                                                       class="btn btn-sm btn-outline-success" title="Nueva Orden">
                                                        <i class="fas fa-shopping-cart"></i>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const buscarInput = document.getElementById('buscar-cliente');
    const tabla = document.getElementById('tabla-clientes');
    
    if (buscarInput && tabla) {
        buscarInput.addEventListener('keyup', function() {
            const filtro = this.value.toLowerCase();
            const filas = tabla.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            
            for (let i = 0; i < filas.length; i++) {
                const textoFila = filas[i].textContent.toLowerCase();
                if (textoFila.includes(filtro)) {
                    filas[i].style.display = '';
                } else {
                    filas[i].style.display = 'none';
                }
            }
        });
    }
});
</script>

<?php include_once VIEWS_PATH . "layouts/footer.php"; ?>
