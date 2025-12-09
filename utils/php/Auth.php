<?php
/**
 * Classe de Autenticação HSN
 * Utilitário para gerenciar autenticação em projetos do ecossistema
 */

namespace HSN\Utils;

class Auth
{
    private $pdo;
    private $zimbrosApiUrl;

    public function __construct($pdo, $zimbrosApiUrl = null)
    {
        $this->pdo = $pdo;
        $this->zimbrosApiUrl = $zimbrosApiUrl ?: getenv('ZIMBROS_API_URL');
    }

    /**
     * Autentica usuário local
     *
     * @param string $email
     * @param string $password
     * @return array|false Dados do usuário ou false
     */
    public function loginLocal($email, $password)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ? AND active = 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $this->startSession($user);
            $this->logActivity($user['id'], 'login', 'Login local realizado');
            return $user;
        }

        return false;
    }

    /**
     * Autentica via Formi Zimbros
     *
     * @param string $email
     * @param string $password
     * @return array|false
     */
    public function loginZimbros($email, $password)
    {
        if (!$this->zimbrosApiUrl) {
            throw new \Exception('URL da API Zimbros não configurada');
        }

        $ch = curl_init($this->zimbrosApiUrl . '?path=auth&action=login');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode(['email' => $email, 'senha' => $password]),
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_TIMEOUT => 10
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $data = json_decode($response, true);

            // Sincroniza ou cria usuário local
            $user = $this->syncZimbrosUser($email, $data);

            if ($user) {
                $this->startSession($user);
                $this->logActivity($user['id'], 'zimbros_login', 'Login via Zimbros');
                return $user;
            }
        }

        return false;
    }

    /**
     * Sincroniza usuário do Zimbros com banco local
     *
     * @param string $email
     * @param array $zimbrosData
     * @return array|false
     */
    private function syncZimbrosUser($email, $zimbrosData)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$user) {
            // Cria novo usuário
            $stmt = $this->pdo->prepare(
                "INSERT INTO users (name, email, password, role, active) VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $zimbrosData['name'] ?? $email,
                $email,
                password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT),
                'user',
                1
            ]);

            return [
                'id' => $this->pdo->lastInsertId(),
                'name' => $zimbrosData['name'] ?? $email,
                'email' => $email,
                'role' => 'user'
            ];
        }

        return $user;
    }

    /**
     * Inicia sessão para o usuário
     *
     * @param array $user
     */
    private function startSession($user)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'] ?? 'user';
        $_SESSION['login_time'] = time();

        session_regenerate_id(true);
    }

    /**
     * Faz logout do usuário
     */
    public function logout()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            if (isset($_SESSION['user_id'])) {
                $this->logActivity($_SESSION['user_id'], 'logout', 'Logout realizado');
            }

            $_SESSION = [];

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

            session_destroy();
        }
    }

    /**
     * Verifica se usuário está autenticado
     *
     * @return bool
     */
    public function isAuthenticated()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Obtém usuário atual
     *
     * @return array|null
     */
    public function getCurrentUser()
    {
        if (!$this->isAuthenticated()) {
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
     * Registra atividade do usuário
     *
     * @param int $userId
     * @param string $action
     * @param string $description
     */
    private function logActivity($userId, $action, $description)
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO activity_logs (user_id, action, description, ip_address, user_agent)
             VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->execute([
            $userId,
            $action,
            $description,
            $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ]);
    }

    /**
     * Verifica se usuário tem permissão
     *
     * @param string|array $roles Roles permitidas
     * @return bool
     */
    public function hasRole($roles)
    {
        $user = $this->getCurrentUser();

        if (!$user) {
            return false;
        }

        if (is_string($roles)) {
            $roles = [$roles];
        }

        return in_array($user['role'], $roles);
    }

    /**
     * Middleware para requerer autenticação
     *
     * @param string $redirectUrl
     */
    public function requireAuth($redirectUrl = '/public/login.php')
    {
        if (!$this->isAuthenticated()) {
            header('Location: ' . $redirectUrl);
            exit;
        }
    }

    /**
     * Middleware para requerer role específica
     *
     * @param string|array $roles
     * @param string $redirectUrl
     */
    public function requireRole($roles, $redirectUrl = '/public/index.php')
    {
        if (!$this->hasRole($roles)) {
            header('Location: ' . $redirectUrl);
            exit;
        }
    }
}
