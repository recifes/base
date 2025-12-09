# 🚀 MVP Padrão HSN

Template base para iniciar novos projetos no ecossistema HSN.

## 📋 Características

- ✅ Estrutura MVC organizada
- ✅ Autenticação integrada com Formi Zimbros
- ✅ Sistema de sessões seguro
- ✅ API RESTful pronta
- ✅ Integração com Base HSN (CDN)
- ✅ Banco de dados configurado (users, settings, projects)
- ✅ Dashboard Argon pré-configurado

## 📂 Estrutura do Projeto

```
mvp-padrao/
├── public/              # Arquivos públicos (front-end)
│   ├── index.php       # Página inicial / Dashboard
│   ├── login.php       # Tela de login
│   ├── logout.php      # Logout
│   └── assets/         # CSS/JS customizados (opcional)
│
├── app/
│   ├── config/         # Configurações
│   │   ├── database.php
│   │   └── session.php
│   │
│   ├── api/            # Endpoints da API
│   │   ├── auth.php
│   │   └── users.php
│   │
│   ├── controllers/    # Controladores
│   │   └── AuthController.php
│   │
│   └── models/         # Models
│       └── User.php
│
├── database/
│   ├── schema.sql      # Estrutura do banco
│   └── seeds.sql       # Dados iniciais
│
├── includes/           # Arquivos compartilhados
│   ├── header.php
│   └── footer.php
│
└── .env.example        # Variáveis de ambiente

```

## 🚀 Instalação Rápida

### 1. Clone o template

```bash
cp -r templates/mvp-padrao /caminho/do/seu/projeto
cd /caminho/do/seu/projeto
```

### 2. Configure o banco de dados

```bash
# Copie o arquivo de exemplo
cp .env.example .env

# Edite com suas credenciais
nano .env
```

### 3. Importe o banco de dados

```bash
mysql -u seu_usuario -p seu_banco < database/schema.sql
mysql -u seu_usuario -p seu_banco < database/seeds.sql
```

### 4. Configure permissões

```bash
chmod 644 app/config/*.php
chmod 755 public/
```

### 5. Acesse o projeto

```
http://localhost/seu-projeto/public/
```

**Credenciais padrão:**
- Email: `admin@hsn.com.br`
- Senha: `123456`

## 🔧 Configuração

### Banco de Dados (.env)

```env
DB_HOST=localhost
DB_NAME=seu_banco
DB_USER=seu_usuario
DB_PASS=sua_senha

# Zimbros API
ZIMBROS_API_URL=https://zimbros.hsn.com.br/api.php
ZIMBROS_API_KEY=sua_chave_api

# Base HSN CDN
BASE_CDN_URL=https://base.hsn.com.br
```

### Sessão

Por padrão, as sessões duram 24 horas e são gerenciadas em `app/config/session.php`.

## 📚 Uso da API

### Autenticação

```javascript
// Login
POST /api/auth.php?action=login
{
  "email": "user@example.com",
  "password": "senha123"
}

// Logout
POST /api/auth.php?action=logout

// Verificar sessão
GET /api/auth.php?action=check
```

### Usuários

```javascript
// Listar usuários
GET /api/users.php

// Buscar usuário específico
GET /api/users.php?id=1

// Criar usuário
POST /api/users.php
{
  "name": "João Silva",
  "email": "joao@example.com",
  "password": "senha123"
}

// Atualizar usuário
PUT /api/users.php?id=1
{
  "name": "João Silva Atualizado"
}

// Deletar usuário
DELETE /api/users.php?id=1
```

## 🎨 Personalização

### Adicionar nova página

1. Crie o arquivo em `/public/minha-pagina.php`
2. Inclua o header e footer:

```php
<?php
require_once '../includes/header.php';
?>

<div class="container">
  <h1>Minha Página</h1>
</div>

<?php
require_once '../includes/footer.php';
?>
```

### Adicionar novo endpoint de API

1. Crie o arquivo em `/app/api/meu-endpoint.php`
2. Use o padrão:

```php
<?php
require_once '../config/database.php';
require_once '../config/session.php';

header('Content-Type: application/json');

// Verificar autenticação
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Não autenticado']);
    exit;
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'listar':
        // Sua lógica aqui
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Ação inválida']);
}
?>
```

## 🔐 Segurança

- ✅ Senhas hasheadas com `password_hash()`
- ✅ Proteção contra SQL Injection (PDO prepared statements)
- ✅ Validação de sessão em todas as páginas protegidas
- ✅ Headers de segurança configurados
- ✅ CSRF protection (implementar conforme necessidade)

## 📦 Dependências

- PHP 7.4+
- MySQL 5.7+ ou MariaDB 10.3+
- Apache/Nginx com mod_rewrite
- Base HSN CDN (base.hsn.com.br)

## 🆘 Troubleshooting

### Erro de conexão com banco

- Verifique as credenciais no `.env`
- Certifique-se que o MySQL está rodando
- Verifique as permissões do usuário

### Sessão não persiste

- Verifique permissões da pasta de sessão
- Confirme que `session.php` está sendo incluído
- Verifique configurações de cookie no navegador

### Assets não carregam

- Verifique se `base.hsn.com.br` está acessível
- Confirme a URL no `.env`
- Verifique console do navegador para erros

## 📞 Suporte

Documentação completa: [base.hsn.com.br](https://base.hsn.com.br)
