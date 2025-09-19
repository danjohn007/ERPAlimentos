<div class="text-center py-5">
    <div class="error-icon mb-4">
        <i class="fas fa-exclamation-triangle fa-5x text-danger"></i>
    </div>
    
    <h1 class="display-4">403</h1>
    <h2 class="mb-3"><?= $title ?? 'Acceso Denegado' ?></h2>
    <p class="lead text-muted">
        <?= $message ?? 'No tiene permisos para acceder a esta página.' ?>
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
</div>