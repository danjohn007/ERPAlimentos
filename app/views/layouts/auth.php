<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - ' : '' ?><?= APP_NAME ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #2E8B57 0%, #3CB371 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .auth-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        
        .auth-header {
            background: linear-gradient(135deg, #2E8B57 0%, #3CB371 100%);
            color: white;
            text-align: center;
            padding: 30px;
        }
        
        .auth-header h2 {
            margin: 0;
            font-weight: 300;
        }
        
        .cheese-icon {
            font-size: 3rem;
            color: #FFD700;
            margin-bottom: 10px;
        }
        
        .form-control:focus {
            border-color: #2E8B57;
            box-shadow: 0 0 0 0.2rem rgba(46, 139, 87, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #2E8B57 0%, #3CB371 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #246447 0%, #339966 100%);
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="auth-card">
                    <div class="auth-header">
                        <i class="fas fa-cheese cheese-icon"></i>
                        <h2><?= APP_NAME ?></h2>
                        <p class="mb-0">Sistema de Gestión de Fábrica de Quesos</p>
                    </div>
                    
                    <div class="card-body p-4">
                        <!-- Flash Messages -->
                        <?= $this->flash() ?>
                        
                        <!-- Content -->
                        <?= $content ?>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <p class="text-white-50">
                        <small>
                            &copy; <?= date('Y') ?> <?= APP_NAME ?> v<?= APP_VERSION ?>
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>