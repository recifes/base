<?php
/**
 * API de Autenticação
 * Endpoints: login, logout, check
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

/**
 * Resposta JSON padronizada
 */
function respond($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

/**
 * Login
 */
if ($action === 'login' && $method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $email = $input['email'] ?? '';
    $password = $input['password'] ?? '';

    if (empty($email) || empty($password)) {
        respond(['success' => false, 'message' => 'Email e senha são obrigatórios'], 400);
    }

    // Busca usuário
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND active = 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        respond(['success' => false, 'message' => 'Credenciais inválidas'], 401);
    }

    // Faz login
    loginUser($user);

    // Registra atividade
    $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, description, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $user['id'],
        'api_login',
        'Login via API',
        $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
    ]);

    respond([
        'success' => true,
        'message' => 'Login realizado com sucesso',
        'user' => [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ]
    ]);
}

/**
 * Logout
 */
if ($action === 'logout' && $method === 'POST') {
    if (isAuthenticated()) {
        $userId = $_SESSION['user_id'];

        // Registra atividade
        $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, description, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $userId,
            'api_logout',
            'Logout via API',
            $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ]);

        logoutUser();
    }

    respond(['success' => true, 'message' => 'Logout realizado com sucesso']);
}

/**
 * Verificar sessão
 */
if ($action === 'check' && $method === 'GET') {
    if (isAuthenticated()) {
        $user = getCurrentUser();
        respond([
            'success' => true,
            'authenticated' => true,
            'user' => $user
        ]);
    } else {
        respond([
            'success' => true,
            'authenticated' => false
        ]);
    }
}

/**
 * Integração com Zimbros
 */
if ($action === 'zimbros-login' && $method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $email = $input['email'] ?? '';
    $password = $input['password'] ?? '';

    if (empty($email) || empty($password)) {
        respond(['success' => false, 'message' => 'Email e senha são obrigatórios'], 400);
    }

    $zimbrosUrl = getenv('ZIMBROS_API_URL');

    if (!$zimbrosUrl) {
        respond(['success' => false, 'message' => 'Integração Zimbros não configurada'], 500);
    }

    // Faz requisição para Zimbros
    $ch = curl_init($zimbrosUrl . '?path=auth&action=login');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['email' => $email, 'senha' => $password]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        $zimbrosData = json_decode($response, true);

        // Se login no Zimbros foi bem-sucedido, busca ou cria usuário local
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            // Cria usuário local se não existe
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, active) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $zimbrosData['name'] ?? $email,
                $email,
                password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT), // senha aleatória
                'user',
                1
            ]);

            $user = [
                'id' => $pdo->lastInsertId(),
                'name' => $zimbrosData['name'] ?? $email,
                'email' => $email,
                'role' => 'user'
            ];
        }

        loginUser($user);

        respond([
            'success' => true,
            'message' => 'Login Zimbros realizado com sucesso',
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role']
            ]
        ]);
    } else {
        respond(['success' => false, 'message' => 'Falha na autenticação Zimbros'], 401);
    }
}

// Ação inválida
respond(['success' => false, 'message' => 'Ação inválida'], 400);
