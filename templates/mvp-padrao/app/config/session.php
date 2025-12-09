<?php
/**
 * Configuração de Sessão
 * Gerenciamento seguro de sessões
 */

// Carrega configurações de ambiente
require_once __DIR__ . '/database.php';

// Configurações de sessão
$sessionConfig = [
    'name' => getenv('SESSION_NAME') ?: 'HSN_SESSION',
    'lifetime' => (int)(getenv('SESSION_LIFETIME') ?: 86400), // 24 horas
    'path' => '/',
    'domain' => '',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax'
];

// Configurar cookie de sessão
session_set_cookie_params([
    'lifetime' => $sessionConfig['lifetime'],
    'path' => $sessionConfig['path'],
    'domain' => $sessionConfig['domain'],
    'secure' => $sessionConfig['secure'],
    'httponly' => $sessionConfig['httponly'],
    'samesite' => $sessionConfig['samesite']
]);

// Define nome da sessão
session_name($sessionConfig['name']);

// Inicia sessão se ainda não foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica se o usuário está autenticado
 * @return bool
 */
function isAuthenticated() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Requer autenticação - redireciona para login se não autenticado
 * @param string $redirectUrl URL para redirecionar se não autenticado
 */
function requireAuth($redirectUrl = '/public/login.php') {
    if (!isAuthenticated()) {
        header('Location: ' . $redirectUrl);
        exit;
    }
}

/**
 * Obtém dados do usuário atual da sessão
 * @return array|null
 */
function getCurrentUser() {
    if (!isAuthenticated()) {
        return null;
    }

    return [
        'id' => $_SESSION['user_id'] ?? null,
        'name' => $_SESSION['user_name'] ?? null,
        'email' => $_SESSION['user_email'] ?? null,
        'role' => $_SESSION['user_role'] ?? 'user'
    ];
}

/**
 * Faz login do usuário
 * @param array $user Dados do usuário
 */
function loginUser($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'] ?? 'user';
    $_SESSION['login_time'] = time();

    // Regenera ID da sessão para segurança
    session_regenerate_id(true);
}

/**
 * Faz logout do usuário
 */
function logoutUser() {
    // Limpa todas as variáveis de sessão
    $_SESSION = [];

    // Deleta o cookie de sessão
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    // Destroi a sessão
    session_destroy();
}

/**
 * Verifica timeout de sessão
 * @param int $maxLifetime Tempo máximo em segundos
 * @return bool True se a sessão expirou
 */
function checkSessionTimeout($maxLifetime = null) {
    if ($maxLifetime === null) {
        $maxLifetime = $GLOBALS['sessionConfig']['lifetime'];
    }

    if (isset($_SESSION['login_time'])) {
        if (time() - $_SESSION['login_time'] > $maxLifetime) {
            logoutUser();
            return true;
        }
    }

    return false;
}
