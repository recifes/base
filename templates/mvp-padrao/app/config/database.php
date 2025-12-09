<?php
/**
 * Configuração de Banco de Dados
 * Conexão usando PDO com suporte a .env
 */

// Carrega variáveis de ambiente
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        if (!array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
            putenv("$name=$value");
        }
    }
}

// Carrega .env do diretório raiz
loadEnv(__DIR__ . '/../../.env');

// Configurações do banco
$dbConfig = [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'name' => getenv('DB_NAME') ?: 'hsn_projeto',
    'user' => getenv('DB_USER') ?: 'root',
    'pass' => getenv('DB_PASS') ?: '',
    'charset' => 'utf8mb4'
];

// Cria conexão PDO
try {
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['name']};charset={$dbConfig['charset']}";

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['pass'], $options);

} catch (PDOException $e) {
    // Em produção, não exiba detalhes do erro
    if (getenv('APP_ENV') === 'development') {
        die('Erro de conexão com o banco de dados: ' . $e->getMessage());
    } else {
        die('Erro de conexão com o banco de dados. Contate o administrador.');
    }
}

// Retorna a conexão
return $pdo;
