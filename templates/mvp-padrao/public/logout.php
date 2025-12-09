<?php
/**
 * Logout
 */

require_once __DIR__ . '/../app/config/session.php';

// Registra log de logout se estiver autenticado
if (isAuthenticated()) {
    require_once __DIR__ . '/../app/config/database.php';

    $userId = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, description, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $userId,
        'logout',
        'Logout realizado',
        $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
    ]);
}

// Faz logout
logoutUser();

// Redireciona para login
header('Location: login.php');
exit;
