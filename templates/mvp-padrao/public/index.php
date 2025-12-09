<?php
/**
 * Dashboard Principal
 */

require_once __DIR__ . '/../includes/header.php';

// Requer autenticação
requireAuth();

// Busca estatísticas
require_once __DIR__ . '/../app/config/database.php';

$stats = [
    'total_users' => 0,
    'total_projects' => 0,
    'active_projects' => 0,
];

// Total de usuários
$stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE active = 1");
$stats['total_users'] = $stmt->fetchColumn();

// Total de projetos
$stmt = $pdo->query("SELECT COUNT(*) FROM projects");
$stats['total_projects'] = $stmt->fetchColumn();

// Projetos ativos
$stmt = $pdo->query("SELECT COUNT(*) FROM projects WHERE status = 'active'");
$stats['active_projects'] = $stmt->fetchColumn();

// Últimas atividades
$stmt = $pdo->prepare("
    SELECT al.*, u.name as user_name
    FROM activity_logs al
    LEFT JOIN users u ON al.user_id = u.id
    ORDER BY al.created_at DESC
    LIMIT 10
");
$stmt->execute();
$recentActivities = $stmt->fetchAll();
?>

<div class="row">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Usuários Ativos</p>
                            <h5 class="font-weight-bolder mb-0">
                                <?php echo $stats['total_users']; ?>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                            <i class="ni ni-single-02 text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Total de Projetos</p>
                            <h5 class="font-weight-bolder mb-0">
                                <?php echo $stats['total_projects']; ?>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                            <i class="ni ni-folder-17 text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Projetos Ativos</p>
                            <h5 class="font-weight-bolder mb-0">
                                <?php echo $stats['active_projects']; ?>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                            <i class="ni ni-check-bold text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Bem-vindo</p>
                            <h5 class="font-weight-bolder mb-0">
                                <?php echo htmlspecialchars($currentUser['name']); ?>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                            <i class="ni ni-badge text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-7 mb-lg-0 mb-4">
        <div class="card">
            <div class="card-header pb-0 p-3">
                <div class="d-flex justify-content-between">
                    <h6 class="mb-2">Atividades Recentes</h6>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="timeline timeline-one-side">
                    <?php foreach ($recentActivities as $activity): ?>
                    <div class="timeline-block mb-3">
                        <span class="timeline-step">
                            <i class="ni ni-bell-55 text-success text-gradient"></i>
                        </span>
                        <div class="timeline-content">
                            <h6 class="text-dark text-sm font-weight-bold mb-0">
                                <?php echo htmlspecialchars($activity['action']); ?>
                            </h6>
                            <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                                <?php echo htmlspecialchars($activity['description']); ?>
                            </p>
                            <p class="text-sm mt-3 mb-2">
                                <?php echo htmlspecialchars($activity['user_name'] ?? 'Sistema'); ?>
                                <br>
                                <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($activity['created_at'])); ?></small>
                            </p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-0">Informações do Sistema</h6>
            </div>
            <div class="card-body p-3">
                <ul class="list-group">
                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                        <div class="d-flex align-items-center">
                            <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                <i class="ni ni-settings text-white opacity-10"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark text-sm">Versão do Argon</h6>
                                <span class="text-xs"><?php echo getenv('BASE_ARGON_VERSION') ?: 'v1.0.0'; ?></span>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                        <div class="d-flex align-items-center">
                            <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                <i class="ni ni-cloud-download-95 text-white opacity-10"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark text-sm">Base CDN</h6>
                                <span class="text-xs"><?php echo getenv('BASE_CDN_URL') ?: 'https://base.hsn.com.br'; ?></span>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 border-radius-lg">
                        <div class="d-flex align-items-center">
                            <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                <i class="ni ni-world text-white opacity-10"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark text-sm">Ambiente</h6>
                                <span class="text-xs"><?php echo getenv('APP_ENV') ?: 'production'; ?></span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
