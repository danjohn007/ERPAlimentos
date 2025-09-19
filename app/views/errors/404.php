<div class="text-center py-5">
    <div class="error-icon mb-4">
        <i class="fas fa-search fa-5x text-warning"></i>
    </div>
    
    <h1 class="display-4">404</h1>
    <h2 class="mb-3"><?= $title ?? 'Página no encontrada' ?></h2>
    <p class="lead text-muted">
        <?= $message ?? 'La página que busca no existe o ha sido movida.' ?>
    </p>
    
    <hr class="my-4">
    
    <div class="d-flex justify-content-center gap-3">
        <a href="javascript:history.back()" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <a href="<?= $this->url('dashboard') ?>" class="btn btn-primary">
            <i class="fas fa-home"></i> Ir al Dashboard
        </a>
    </div>
    
    <div class="mt-4">
        <p class="text-muted">
            <small>
                Si cree que esto es un error, contacte al administrador del sistema.
            </small>
        </p>
    </div>
</div>