<?php
/**
 * API de Usuários
 * CRUD completo de usuários
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Verifica autenticação
if (!isAuthenticated()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Não autenticado']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$userId = $_GET['id'] ?? null;
$currentUser = getCurrentUser();

/**
 * Resposta JSON padronizada
 */
function respond($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

/**
 * GET - Listar usuários ou buscar específico
 */
if ($method === 'GET') {
    if ($userId) {
        // Buscar usuário específico
        $stmt = $pdo->prepare("SELECT id, name, email, role, active, created_at, updated_at FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if (!$user) {
            respond(['success' => false, 'message' => 'Usuário não encontrado'], 404);
        }

        respond(['success' => true, 'user' => $user]);
    } else {
        // Listar todos os usuários
        $stmt = $pdo->query("SELECT id, name, email, role, active, created_at, updated_at FROM users ORDER BY created_at DESC");
        $users = $stmt->fetchAll();

        respond(['success' => true, 'users' => $users]);
    }
}

/**
 * POST - Criar novo usuário
 */
if ($method === 'POST') {
    // Apenas admins podem criar usuários
    if ($currentUser['role'] !== 'admin') {
        respond(['success' => false, 'message' => 'Permissão negada'], 403);
    }

    $input = json_decode(file_get_contents('php://input'), true);

    $name = $input['name'] ?? '';
    $email = $input['email'] ?? '';
    $password = $input['password'] ?? '';
    $role = $input['role'] ?? 'user';
    $active = $input['active'] ?? 1;

    // Validações
    if (empty($name) || empty($email) || empty($password)) {
        respond(['success' => false, 'message' => 'Nome, email e senha são obrigatórios'], 400);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        respond(['success' => false, 'message' => 'Email inválido'], 400);
    }

    // Verifica se email já existe
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        respond(['success' => false, 'message' => 'Email já cadastrado'], 400);
    }

    // Cria usuário
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, active) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $hashedPassword, $role, $active]);

    $newUserId = $pdo->lastInsertId();

    // Registra atividade
    $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, description, ip_address) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $currentUser['id'],
        'create_user',
        "Criou usuário: {$name}",
        $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    ]);

    respond([
        'success' => true,
        'message' => 'Usuário criado com sucesso',
        'user_id' => $newUserId
    ], 201);
}

/**
 * PUT - Atualizar usuário
 */
if ($method === 'PUT') {
    if (!$userId) {
        respond(['success' => false, 'message' => 'ID do usuário é obrigatório'], 400);
    }

    // Usuários só podem atualizar a si mesmos, admins podem atualizar qualquer um
    if ($currentUser['role'] !== 'admin' && $currentUser['id'] != $userId) {
        respond(['success' => false, 'message' => 'Permissão negada'], 403);
    }

    $input = json_decode(file_get_contents('php://input'), true);

    // Busca usuário atual
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if (!$user) {
        respond(['success' => false, 'message' => 'Usuário não encontrado'], 404);
    }

    // Campos que podem ser atualizados
    $name = $input['name'] ?? $user['name'];
    $email = $input['email'] ?? $user['email'];
    $role = $input['role'] ?? $user['role'];
    $active = isset($input['active']) ? $input['active'] : $user['active'];

    // Se senha foi fornecida, atualiza
    if (!empty($input['password'])) {
        $password = password_hash($input['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, password = ?, role = ?, active = ? WHERE id = ?");
        $stmt->execute([$name, $email, $password, $role, $active, $userId]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, role = ?, active = ? WHERE id = ?");
        $stmt->execute([$name, $email, $role, $active, $userId]);
    }

    // Registra atividade
    $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, description, ip_address) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $currentUser['id'],
        'update_user',
        "Atualizou usuário ID: {$userId}",
        $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    ]);

    respond(['success' => true, 'message' => 'Usuário atualizado com sucesso']);
}

/**
 * DELETE - Deletar usuário
 */
if ($method === 'DELETE') {
    // Apenas admins podem deletar
    if ($currentUser['role'] !== 'admin') {
        respond(['success' => false, 'message' => 'Permissão negada'], 403);
    }

    if (!$userId) {
        respond(['success' => false, 'message' => 'ID do usuário é obrigatório'], 400);
    }

    // Não pode deletar a si mesmo
    if ($currentUser['id'] == $userId) {
        respond(['success' => false, 'message' => 'Você não pode deletar sua própria conta'], 400);
    }

    // Verifica se usuário existe
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    if (!$stmt->fetch()) {
        respond(['success' => false, 'message' => 'Usuário não encontrado'], 404);
    }

    // Deleta usuário
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$userId]);

    // Registra atividade
    $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, description, ip_address) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $currentUser['id'],
        'delete_user',
        "Deletou usuário ID: {$userId}",
        $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    ]);

    respond(['success' => true, 'message' => 'Usuário deletado com sucesso']);
}

// Método não permitido
respond(['success' => false, 'message' => 'Método não permitido'], 405);
