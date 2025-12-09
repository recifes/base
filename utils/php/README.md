# 🔧 Utilitários PHP - Base HSN

Classes utilitárias reutilizáveis para projetos do ecossistema HSN.

## 📦 Classes Disponíveis

### 1. `Auth.php` - Autenticação

Gerencia autenticação local e integração com Formi Zimbros.

**Uso:**
```php
<?php
require_once 'utils/php/Auth.php';
use HSN\Utils\Auth;

$auth = new Auth($pdo);

// Login local
$user = $auth->loginLocal('user@example.com', 'senha123');

// Login via Zimbros
$user = $auth->loginZimbros('user@example.com', 'senha123');

// Verificar autenticação
if ($auth->isAuthenticated()) {
    $currentUser = $auth->getCurrentUser();
}

// Logout
$auth->logout();

// Middleware
$auth->requireAuth(); // Redireciona se não autenticado
$auth->requireRole('admin'); // Requer role específica
```

---

### 2. `Response.php` - Respostas de API

Padroniza respostas JSON de APIs.

**Uso:**
```php
<?php
require_once 'utils/php/Response.php';
use HSN\Utils\Response;

// Sucesso
Response::success(['user' => $userData]);

// Erro
Response::error('Mensagem de erro', 400);

// Respostas específicas
Response::unauthorized();
Response::forbidden();
Response::notFound();
Response::validationError(['email' => 'Email inválido']);
Response::serverError();

// CORS
Response::setCORS('*', ['GET', 'POST'], ['Content-Type']);
```

---

### 3. `Validator.php` - Validação de Dados

Valida dados de formulários e requisições.

**Uso:**
```php
<?php
require_once 'utils/php/Validator.php';
use HSN\Utils\Validator;

$data = $_POST;

// Validação encadeada
$validator = new Validator($data);
$validator
    ->required('name')
    ->required('email')
    ->email('email')
    ->min('password', 6)
    ->match('password', 'password_confirmation');

if ($validator->fails()) {
    $errors = $validator->errors();
    // Trate os erros
}

// Validação rápida
$errors = Validator::make($data, [
    'name' => 'required',
    'email' => 'required|email',
    'password' => 'required|min:6',
    'age' => 'numeric'
]);

if (!empty($errors)) {
    Response::validationError($errors);
}
```

**Regras disponíveis:**
- `required` - Campo obrigatório
- `email` - Email válido
- `min:N` - Mínimo de N caracteres
- `max:N` - Máximo de N caracteres
- `numeric` - Deve ser número
- `in:a,b,c` - Deve estar em lista de valores
- `url` - URL válida
- `match:field` - Deve corresponder a outro campo
- `regex:pattern` - Padrão regex customizado

---

## 🚀 Instalação em Projeto

### Método 1: Cópia Direta

```bash
cp -r /caminho/base-hsn/utils/php /seu-projeto/utils/
```

### Método 2: Autoload (Recomendado)

```php
<?php
// composer.json
{
    "autoload": {
        "psr-4": {
            "HSN\\Utils\\": "utils/php/"
        }
    }
}
```

```bash
composer dump-autoload
```

### Método 3: Include Manual

```php
<?php
require_once __DIR__ . '/utils/php/Auth.php';
require_once __DIR__ . '/utils/php/Response.php';
require_once __DIR__ . '/utils/php/Validator.php';
```

---

## 💡 Exemplos Práticos

### API de Login Completa

```php
<?php
require_once 'config/database.php';
require_once 'utils/php/Auth.php';
require_once 'utils/php/Response.php';
require_once 'utils/php/Validator.php';

use HSN\Utils\{Auth, Response, Validator};

Response::setCORS();

$input = json_decode(file_get_contents('php://input'), true);

// Valida entrada
$errors = Validator::make($input, [
    'email' => 'required|email',
    'password' => 'required'
]);

if (!empty($errors)) {
    Response::validationError($errors);
}

// Autentica
$auth = new Auth($pdo);
$user = $auth->loginLocal($input['email'], $input['password']);

if ($user) {
    Response::success([
        'user' => $user,
        'message' => 'Login realizado com sucesso'
    ]);
} else {
    Response::error('Credenciais inválidas', 401);
}
```

### Endpoint Protegido

```php
<?php
require_once 'config/database.php';
require_once 'utils/php/Auth.php';
require_once 'utils/php/Response.php';

use HSN\Utils\{Auth, Response};

Response::setCORS();

$auth = new Auth($pdo);

// Requer autenticação
if (!$auth->isAuthenticated()) {
    Response::unauthorized();
}

// Requer role de admin
if (!$auth->hasRole('admin')) {
    Response::forbidden();
}

// Lógica da API
$data = ['message' => 'Acesso autorizado'];
Response::success($data);
```

---

## 📘 Documentação Completa

Para mais informações sobre o ecossistema HSN:
- Base HSN: [base.hsn.com.br](https://base.hsn.com.br)
- Formi Zimbros: [zimbros.hsn.com.br](https://zimbros.hsn.com.br)
