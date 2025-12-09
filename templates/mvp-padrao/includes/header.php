<?php
/**
 * Header Padrão HSN
 * Inclui CSS do Argon Dashboard e configurações globais
 */

require_once __DIR__ . '/../app/config/session.php';

// Carrega configurações
$baseCdnUrl = getenv('BASE_CDN_URL') ?: 'https://base.hsn.com.br';
$argonVersion = getenv('BASE_ARGON_VERSION') ?: 'v1.0.0';
$appName = getenv('APP_NAME') ?: 'MVP HSN';

// Pega usuário atual se autenticado
$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?php echo $appName; ?></title>

    <!-- Favicon -->
    <link rel="icon" href="<?php echo $baseCdnUrl; ?>/argon/img/brand/favicon.png" type="image/png">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="<?php echo $baseCdnUrl; ?>/argon/versions/<?php echo $argonVersion; ?>/css/nucleo-icons.css">
    <link rel="stylesheet" href="<?php echo $baseCdnUrl; ?>/argon/versions/<?php echo $argonVersion; ?>/css/nucleo-svg.css">

    <!-- Argon CSS -->
    <link rel="stylesheet" href="<?php echo $baseCdnUrl; ?>/argon/versions/<?php echo $argonVersion; ?>/css/argon-dashboard.min.css">

    <!-- Custom CSS (opcional) -->
    <link rel="stylesheet" href="/assets/css/custom.css">
</head>
<body class="g-sidenav-show bg-gray-100">

    <?php if ($currentUser): ?>
    <!-- Sidebar -->
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-white" id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href="/public/index.php">
                <span class="ms-1 font-weight-bold"><?php echo $appName; ?></span>
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="/public/index.php">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
                <!-- Adicione mais itens do menu aqui -->
            </ul>
        </div>
    </aside>
    <?php endif; ?>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

        <?php if ($currentUser): ?>
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <h6 class="font-weight-bolder mb-0">Dashboard</h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                        <!-- Busca ou outros elementos -->
                    </div>
                    <ul class="navbar-nav justify-content-end">
                        <li class="nav-item dropdown pe-2 d-flex align-items-center">
                            <a href="#" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-user me-sm-1"></i>
                                <span class="d-sm-inline d-none"><?php echo htmlspecialchars($currentUser['name']); ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                                <li>
                                    <a class="dropdown-item border-radius-md" href="/public/logout.php">
                                        <div class="d-flex py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="text-sm font-weight-normal mb-1">
                                                    <span class="font-weight-bold">Sair</span>
                                                </h6>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <?php endif; ?>

        <div class="container-fluid py-4">
