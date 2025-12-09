# 📦 Base HSN

O **base.hsn.com.br** é o repositório central de estilos, scripts e componentes usados em todos os projetos do ecossistema HSN.

Ele funciona como uma **CDN privada**, garantindo que alterações de estilo e comportamento sejam aplicadas em todos os sistemas de forma unificada.

---

## 📚 Documentação

- [Introdução](#-base-hsn)
- [Estrutura](#-estrutura-do-repositório)
- [Versionamento](#-sistema-de-versionamento)
- [Uso do Argon](#-uso-do-argon)
- [Templates](#-templates-prontos)
- [MVP Padrão](#-mvp-padrão)
- [Scripts de Automação](#-scripts-de-automação)
- [Utilitários](#-utilitários)
- [Integração Zimbros](#-integração-com-zimbros)
- [Guia de Estilo](#-guia-de-estilo)
- [Prompt ChatGPT](#-prompt-chatgpt)
- [Licença](#-licença)

---

## 📂 Estrutura do Repositório

```
/base.hsn.com.br
  /argon
    /versions
      /v1.0.0   (versão específica)
        /css
        /js
        /fonts
        /img
    VERSION     (versão atual)
    LATEST_VERSION

  /templates
    /mvp-padrao (template completo PHP/MySQL)

  /scripts
    deploy.sh
    version-bump.sh
    health-check.sh

  /utils
    /php        (Auth, Response, Validator)
    /js         (api-client, notifications, form-validator, storage)

  /material     (futuro: Material UI)
  /tailwind     (futuro: Tailwind Components)
```

---

## 🔖 Sistema de Versionamento

O Argon Dashboard agora possui **versionamento completo**, permitindo que você:

- ✅ Use versões específicas (URLs fixas que nunca mudam)
- ✅ Use sempre a versão mais recente (URL `latest`)
- ✅ Faça rollback facilmente em caso de problemas
- ✅ Teste novas versões antes de atualizar produção

### Usando Versão Específica

```html
<!-- Versão fixa - nunca muda -->
<link rel="stylesheet" href="https://base.hsn.com.br/argon/versions/v1.0.0/css/argon-dashboard.min.css">
<script src="https://base.hsn.com.br/argon/versions/v1.0.0/js/argon-dashboard.min.js"></script>
```

### Usando Última Versão

```html
<!-- Sempre usa a versão mais recente -->
<link rel="stylesheet" href="https://base.hsn.com.br/argon/latest/css/argon-dashboard.min.css">
<script src="https://base.hsn.com.br/argon/latest/js/argon-dashboard.min.js"></script>
```

### Incrementar Versão

```bash
cd /caminho/base-hsn
./scripts/version-bump.sh
```

Consulte `argon/versions/v*/README.md` para changelog de cada versão.

---

## 🎨 Uso do Argon

O **Argon Dashboard** é o padrão inicial. Para incluir em qualquer projeto:

### CSS

```html
<!-- CSS -->
<link rel="stylesheet" href="https://base.hsn.com.br/argon/css/argon-dashboard.css">
<link rel="stylesheet" href="https://base.hsn.com.br/argon/css/argon-dashboard.css.map">
<link rel="stylesheet" href="https://base.hsn.com.br/argon/css/argon-dashboard.min.css">
<link rel="stylesheet" href="https://base.hsn.com.br/argon/css/nucleo-icons.css">
<link rel="stylesheet" href="https://base.hsn.com.br/argon/css/nucleo-svg.css">
```

### JavaScript

```html
<!-- JS -->
<script src="https://base.hsn.com.br/argon/js/argon-dashboard.min.js"></script>
<script src="https://base.hsn.com.br/argon/js/argon-dashboard.js"></script>
<script src="https://base.hsn.com.br/argon/js/argon-dashboard.js.map"></script>
<script src="https://base.hsn.com.br/argon/js/core/bootstrap.bundle.min.js"></script>
<script src="https://base.hsn.com.br/argon/js/core/bootstrap.min.js"></script>
<script src="https://base.hsn.com.br/argon/js/core/popper.min.js"></script>
```

### Plugins

```html
<!-- Plugin JS -->
<script src="https://base.hsn.com.br/argon/js/plugins/bootstrap-notify.js"></script>
<script src="https://base.hsn.com.br/argon/js/plugins/Chart.extension.js"></script>
<script src="https://base.hsn.com.br/argon/js/plugins/chartjs.min.js"></script>
<script src="https://base.hsn.com.br/argon/js/plugins/perfect-scrollbar.min.js"></script>
<script src="https://base.hsn.com.br/argon/js/plugins/smooth-scrollbar.min.js"></script>
```

---

## 📦 Templates Prontos

### MVP Padrão (`templates/mvp-padrao`)

Template completo e funcional para iniciar novos projetos rapidamente.

**Recursos incluídos:**
- ✅ Estrutura MVC organizada
- ✅ Autenticação completa (local + Zimbros)
- ✅ Sistema de sessões seguro
- ✅ API RESTful (auth, users)
- ✅ Dashboard funcional com Argon
- ✅ Banco de dados estruturado
- ✅ Activity logs
- ✅ Validação de dados

**Instalação rápida:**

```bash
# Clone o template
cp -r templates/mvp-padrao /caminho/seu-projeto

# Configure o .env
cp .env.example .env
nano .env

# Importe o banco
mysql -u user -p database < database/schema.sql
mysql -u user -p database < database/seeds.sql

# Acesse
http://localhost/seu-projeto/public/
```

**Credenciais padrão:**
- Email: `admin@hsn.com.br`
- Senha: `123456`

📖 [Documentação completa](templates/mvp-padrao/README.md)

---

## 🚀 MVP Padrão

O **MVP Padrão** é um esqueleto PHP/MySQL pronto para ser clonado em novos projetos. Ele contém:

- ✅ Estrutura de pastas organizada (`public`, `app`, `config`, `api`, `includes`)
- ✅ Banco inicial com `users`, `settings` e `projects`
- ✅ Login e autenticação via sessão
- ✅ Integração direta com o `base.hsn.com.br`

### Exemplo de uso no `index.php`:

```html
<link rel="stylesheet" href="https://base.hsn.com.br/argon/css/argon-dashboard.css">
<link rel="stylesheet" href="https://base.hsn.com.br/argon/css/argon-dashboard.css.map">
<link rel="stylesheet" href="https://base.hsn.com.br/argon/css/argon-dashboard.min.css">

<script src="https://base.hsn.com.br/argon/js/argon-dashboard.min.js"></script>
<script src="https://base.hsn.com.br/argon/js/argon-dashboard.js"></script>
<script src="https://base.hsn.com.br/argon/js/argon-dashboard.js.map"></script>
```

---

## 🔧 Scripts de Automação

Scripts bash para gerenciar o repositório de forma automatizada.

### `deploy.sh` - Deploy Automático

Sincroniza arquivos com servidor de produção via rsync.

```bash
chmod +x scripts/deploy.sh
./scripts/deploy.sh
```

### `version-bump.sh` - Incrementar Versão

Cria nova versão do Argon automaticamente.

```bash
./scripts/version-bump.sh
# Escolha: patch (1.0.1), minor (1.1.0), major (2.0.0)
```

### `health-check.sh` - Verificar Integridade

Valida estrutura e arquivos do repositório.

```bash
./scripts/health-check.sh
```

📖 [Documentação completa](scripts/README.md)

---

## 🔧 Utilitários

### Utilitários PHP (`utils/php/`)

Classes reutilizáveis para projetos PHP:

**Auth.php** - Autenticação local e Zimbros
```php
$auth = new HSN\Utils\Auth($pdo);
$user = $auth->loginLocal('user@example.com', 'senha');
$auth->requireAuth(); // middleware
```

**Response.php** - Respostas de API padronizadas
```php
HSN\Utils\Response::success(['data' => $data]);
HSN\Utils\Response::error('Mensagem', 400);
HSN\Utils\Response::validationError($errors);
```

**Validator.php** - Validação de dados
```php
$errors = HSN\Utils\Validator::make($data, [
  'email' => 'required|email',
  'password' => 'required|min:6'
]);
```

📖 [Documentação PHP](utils/php/README.md)

### Utilitários JavaScript (`utils/js/`)

Bibliotecas client-side para o ecossistema:

**api-client.js** - Cliente HTTP
```javascript
const api = new HSNApiClient();
await api.login('user@example.com', 'senha');
const users = await api.get('/api/users.php');
```

**notifications.js** - Sistema de notificações
```javascript
notify.success('Operação realizada!');
notify.error('Erro ao processar');
notify.apiError(error); // formata erros de API
```

**form-validator.js** - Validação de formulários
```javascript
const validator = new HSNFormValidator('#formId');
validator.required('email');
validator.email('email');
if (validator.validate()) { /* enviar */ }
```

**storage.js** - LocalStorage/SessionStorage
```javascript
storage.set('user', { name: 'João' });
storage.setWithExpiry('token', 'abc', 300); // 5min
const user = storage.get('user');
```

📖 [Documentação JavaScript](utils/js/README.md)

---

## 🔗 Integração com Zimbros

Todos os projetos utilizam o **Formi Zimbros** como hub de autenticação. A API deve ser chamada no login:

### Endpoint de Login

```
POST https://zimbros.hsn.com.br/api.php?path=auth&action=login
{ email, senha }
```

### Exemplo de implementação em PHP:

```php
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($senha, $user['password'])) {
  $_SESSION['user_id'] = $user['id'];
}
```

---

## 🎨 Guia de Estilo

Os estilos são definidos em `argon-dashboard.css`. Exemplo de variáveis globais:

```css
:root {
  --color-primary: #5e72e4;
  --color-secondary: #f4f5f7;
  --font-main: 'Montserrat', sans-serif;
  --border-radius: 8px;
}
```

**Para alterar o tema de todos os projetos, basta mudar aqui.**

---

## 🤖 Prompt ChatGPT

Use o prompt abaixo sempre que iniciar um novo projeto baseado no MVP Padrão:

```
Estou iniciando um novo projeto dentro do ecossistema HSN.
Este projeto deve usar o MVP Padrão já integrado ao base.hsn.com.br/argon e ao Formi Zimbros.

Preciso que você me ajude a:

1. Criar as tabelas específicas deste projeto no MySQL (além das padrão users/settings/projects).
2. Criar os endpoints da API relacionados.
3. Criar as telas iniciais (HTML/PHP) com os assets vindos do base.hsn.com.br.
4. Seguir o padrão de janelas do FormiOS.
5. Documentar o que foi feito e os próximos passos.

Nome do projeto: [NOME]
Objetivo: [DESCRIÇÃO CURTA]
Funcionalidade principal inicial: [DESCRIÇÃO]
```

---

## 📜 Licença

O **Argon Dashboard** é distribuído sob a licença MIT.

```
MIT License

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

Mais detalhes: [Creative Tim License](https://www.creative-tim.com/license)

---

## 🔧 Grupo HSN

Desenvolvido e mantido pelo **Grupo HSN** para padronização de projetos do ecossistema.
