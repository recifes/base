# 📦 Base HSN

O **base.hsn.com.br** é o repositório central de estilos, scripts e componentes usados em todos os projetos do ecossistema HSN.

Ele funciona como uma **CDN privada**, garantindo que alterações de estilo e comportamento sejam aplicadas em todos os sistemas de forma unificada.

---

## 📚 Documentação

- [Introdução](#-base-hsn)
- [Estrutura](#-estrutura-do-repositório)
- [Uso do Argon](#-uso-do-argon)
- [MVP Padrão](#-mvp-padrão)
- [Integração Zimbros](#-integração-com-zimbros)
- [Guia de Estilo](#-guia-de-estilo)
- [Prompt ChatGPT](#-prompt-chatgpt)
- [Licença](#-licença)

---

## 📂 Estrutura do Repositório

```
/base.hsn.com.br
  /argon
    /css        (estilos Argon + argon-dashboard.css)
    /js         (bootstrap, argon, ui.js, helpers.js)
      /core     (bootstrap, popper)
      /plugins  (chartjs, scrollbar, notify, etc)
    /fonts      (Nucleo Icons)
    /img        (logos e imagens globais)
  /material     (futuro: Material UI)
  /tailwind     (futuro: Tailwind Components)
```

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
