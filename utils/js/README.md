# 🔧 Utilitários JavaScript - Base HSN

Bibliotecas JavaScript reutilizáveis para projetos do ecossistema HSN.

## 📦 Utilitários Disponíveis

### 1. `api-client.js` - Cliente de API

Cliente HTTP para fazer requisições a APIs do ecossistema.

**Uso:**
```javascript
// Crie uma instância
const api = new HSNApiClient('/app');

// GET request
const users = await api.get('/api/users.php');

// POST request
const result = await api.post('/api/users.php', {
  name: 'João Silva',
  email: 'joao@example.com'
});

// PUT request
await api.put('/api/users.php?id=1', { name: 'João Atualizado' });

// DELETE request
await api.delete('/api/users.php?id=1');

// Login
await api.login('user@example.com', 'senha123');

// Login via Zimbros
await api.loginZimbros('user@example.com', 'senha123');

// Logout
await api.logout();

// Verificar sessão
const session = await api.checkSession();

// Headers customizados
api.setHeader('Authorization', 'Bearer token');
```

---

### 2. `notifications.js` - Notificações

Sistema de notificações usando Argon Dashboard.

**Uso:**
```javascript
// Notificação de sucesso
notify.success('Operação realizada com sucesso!');

// Notificação de erro
notify.error('Erro ao processar requisição');

// Notificação de aviso
notify.warning('Atenção: Esta ação não pode ser desfeita');

// Notificação de informação
notify.info('Novo recurso disponível!');

// Notificação customizada
notify.show('Mensagem customizada', {
  type: 'success',
  delay: 5000,
  placement: { from: 'bottom', align: 'center' }
});

// Erro de API formatado
try {
  await api.post('/endpoint', data);
} catch (error) {
  notify.apiError(error);
}
```

---

### 3. `form-validator.js` - Validação de Formulários

Validação client-side de formulários.

**Uso:**
```javascript
// HTML
// <form id="loginForm">
//   <input type="email" name="email" required>
//   <input type="password" name="password" required>
// </form>

const validator = new HSNFormValidator('#loginForm');

// Validação customizada
validator.validate = function() {
  this.errors = {};
  this.clearErrors();

  this.required('email', 'Email é obrigatório');
  this.email('email', 'Email inválido');
  this.required('password', 'Senha é obrigatória');
  this.minLength('password', 6, 'Senha deve ter no mínimo 6 caracteres');

  return Object.keys(this.errors).length === 0;
};

// Obter dados do formulário
const formData = validator.getData();
console.log(formData);

// Validação manual
if (validator.validate()) {
  console.log('Formulário válido!');
} else {
  validator.showErrors();
}

// Validação de correspondência
validator.match('password', 'password_confirmation', 'Senhas não correspondem');

// Validação com regex
validator.pattern('phone', /^\d{11}$/, 'Telefone deve ter 11 dígitos');

// Validação customizada
validator.custom('age', (value) => value >= 18, 'Deve ser maior de 18 anos');
```

---

### 4. `storage.js` - Gerenciamento de Storage

Utilitário para localStorage e sessionStorage.

**Uso:**
```javascript
// LocalStorage (persiste após fechar navegador)
storage.set('user', { name: 'João', id: 1 });
const user = storage.get('user');

// SessionStorage (limpa ao fechar navegador)
session.set('temp_data', { foo: 'bar' });
const data = session.get('temp_data');

// Valor com expiração (5 minutos)
storage.setWithExpiry('token', 'abc123', 300);
const token = storage.getWithExpiry('token');

// Verificar se existe
if (storage.has('user')) {
  console.log('Usuário está salvo');
}

// Remover item
storage.remove('user');

// Limpar tudo
storage.clear();

// Trabalhar com arrays
storage.push('favorites', { id: 1, name: 'Item 1' });
storage.pull('favorites', item => item.id === 1);

// Incrementar/decrementar
storage.set('counter', 0);
storage.increment('counter'); // 1
storage.increment('counter', 5); // 6
storage.decrement('counter'); // 5

// Listar todas as keys
const keys = storage.keys();
```

---

## 🚀 Instalação em Projeto

### Método 1: CDN Base HSN (Recomendado)

```html
<!-- Após carregar Argon Dashboard -->
<script src="https://base.hsn.com.br/utils/js/api-client.js"></script>
<script src="https://base.hsn.com.br/utils/js/notifications.js"></script>
<script src="https://base.hsn.com.br/utils/js/form-validator.js"></script>
<script src="https://base.hsn.com.br/utils/js/storage.js"></script>
```

### Método 2: Cópia Local

```bash
cp -r /caminho/base-hsn/utils/js /seu-projeto/assets/js/utils/
```

```html
<script src="/assets/js/utils/api-client.js"></script>
<script src="/assets/js/utils/notifications.js"></script>
<script src="/assets/js/utils/form-validator.js"></script>
<script src="/assets/js/utils/storage.js"></script>
```

---

## 💡 Exemplo Completo: Formulário de Login

```html
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://base.hsn.com.br/argon/latest/css/argon-dashboard.min.css">
</head>
<body>

<form id="loginForm">
  <input type="email" name="email" class="form-control" placeholder="Email">
  <input type="password" name="password" class="form-control" placeholder="Senha">
  <button type="submit" class="btn btn-primary">Entrar</button>
</form>

<script src="https://base.hsn.com.br/argon/latest/js/argon-dashboard.min.js"></script>
<script src="https://base.hsn.com.br/utils/js/api-client.js"></script>
<script src="https://base.hsn.com.br/utils/js/notifications.js"></script>
<script src="https://base.hsn.com.br/utils/js/form-validator.js"></script>
<script src="https://base.hsn.com.br/utils/js/storage.js"></script>

<script>
const api = new HSNApiClient();
const validator = new HSNFormValidator('#loginForm');

validator.validate = function() {
  this.errors = {};
  this.clearErrors();
  this.required('email');
  this.email('email');
  this.required('password');
  this.minLength('password', 6);
  return Object.keys(this.errors).length === 0;
};

document.getElementById('loginForm').addEventListener('submit', async (e) => {
  e.preventDefault();

  if (!validator.validate()) {
    validator.showErrors();
    return;
  }

  const data = validator.getData();

  try {
    const result = await api.login(data.email, data.password);
    storage.set('user', result.user);
    notify.success('Login realizado com sucesso!');
    setTimeout(() => window.location = '/dashboard.php', 1500);
  } catch (error) {
    notify.apiError(error);
  }
});
</script>

</body>
</html>
```

---

## 📘 Documentação Completa

Para mais informações sobre o ecossistema HSN:
- Base HSN: [base.hsn.com.br](https://base.hsn.com.br)
- Argon Dashboard: [base.hsn.com.br/argon](https://base.hsn.com.br/argon)
