<?php
/**
 * Página de Login
 */

require_once __DIR__ . '/../app/config/session.php';

// Se já está autenticado, redireciona para dashboard
if (isAuthenticated()) {
    header('Location: index.php');
    exit;
}

$error = '';

// Processa o formulário de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Por favor, preencha todos os campos.';
    } else {
        require_once __DIR__ . '/../app/config/database.php';

        // Busca usuário no banco
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND active = 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Login bem-sucedido
            loginUser($user);

            // Registra log de atividade
            $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, description, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $user['id'],
                'login',
                'Login realizado com sucesso',
                $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
            ]);

            header('Location: index.php');
            exit;
        } else {
            $error = 'Email ou senha inválidos.';
        }
    }
}

$baseCdnUrl = getenv('BASE_CDN_URL') ?: 'https://base.hsn.com.br';
$argonVersion = getenv('BASE_ARGON_VERSION') ?: 'v1.0.0';
$appName = getenv('APP_NAME') ?: 'MVP HSN';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo $appName; ?></title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="<?php echo $baseCdnUrl; ?>/argon/versions/<?php echo $argonVersion; ?>/css/nucleo-icons.css">

    <!-- Argon CSS -->
    <link rel="stylesheet" href="<?php echo $baseCdnUrl; ?>/argon/versions/<?php echo $argonVersion; ?>/css/argon-dashboard.min.css">
</head>
<body class="bg-gradient-primary">

    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                <nav class="navbar navbar-expand-lg blur blur-rounded top-0 z-index-3 shadow position-absolute my-3 py-2 start-0 end-0 mx-4">
                    <div class="container-fluid">
                        <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3" href="/">
                            <?php echo $appName; ?>
                        </a>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <main class="main-content mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                            <div class="card card-plain">
                                <div class="card-header pb-0 text-start">
                                    <h4 class="font-weight-bolder">Entrar</h4>
                                    <p class="mb-0">Digite seu email e senha para entrar</p>
                                </div>
                                <div class="card-body">
                                    <?php if ($error): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <span class="alert-text"><?php echo htmlspecialchars($error); ?></span>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <?php endif; ?>

                                    <form method="POST" role="form">
                                        <div class="mb-3">
                                            <input type="email" name="email" class="form-control form-control-lg" placeholder="Email" aria-label="Email" required>
                                        </div>
                                        <div class="mb-3">
                                            <input type="password" name="password" class="form-control form-control-lg" placeholder="Senha" aria-label="Password" required>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Entrar</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-4 text-sm mx-auto">
                                        Credenciais padrão:
                                        <br><strong>admin@hsn.com.br</strong> / <strong>123456</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
                            <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden">
                                <span class="mask bg-gradient-primary opacity-6"></span>
                                <h4 class="mt-5 text-white font-weight-bolder position-relative">Grupo HSN</h4>
                                <p class="text-white position-relative">Ecossistema integrado de soluções</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Core JS -->
    <script src="<?php echo $baseCdnUrl; ?>/argon/versions/<?php echo $argonVersion; ?>/js/core/popper.min.js"></script>
    <script src="<?php echo $baseCdnUrl; ?>/argon/versions/<?php echo $argonVersion; ?>/js/core/bootstrap.min.js"></script>
    <script src="<?php echo $baseCdnUrl; ?>/argon/versions/<?php echo $argonVersion; ?>/js/argon-dashboard.min.js"></script>

</body>
</html>
