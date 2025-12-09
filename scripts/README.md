# 🔧 Scripts de Automação

Scripts utilitários para gerenciar o repositório Base HSN.

## 📜 Scripts Disponíveis

### 1. `deploy.sh`
Deploy automático para o servidor de produção.

**Uso:**
```bash
chmod +x scripts/deploy.sh
./scripts/deploy.sh
```

**Variáveis de ambiente:**
```bash
export DEPLOY_USER="root"
export DEPLOY_HOST="base.hsn.com.br"
export DEPLOY_PATH="/var/www/base.hsn.com.br"
./scripts/deploy.sh
```

**Características:**
- Sincronização via rsync
- Exclui arquivos desnecessários (.git, .env, node_modules)
- Confirmação antes de executar
- Backup automático (recomendado)

---

### 2. `version-bump.sh`
Incrementa versão do Argon e cria nova estrutura versionada.

**Uso:**
```bash
chmod +x scripts/version-bump.sh
./scripts/version-bump.sh
```

**Tipos de incremento:**
- **Patch**: 1.0.0 → 1.0.1 (correções)
- **Minor**: 1.0.0 → 1.1.0 (novas features compatíveis)
- **Major**: 1.0.0 → 2.0.0 (breaking changes)
- **Custom**: Define manualmente

**O que faz:**
1. Cria nova pasta `argon/versions/vX.Y.Z`
2. Copia arquivos da versão anterior
3. Gera README da nova versão
4. Atualiza `argon/VERSION`
5. Atualiza `argon/LATEST_VERSION`

---

### 3. `health-check.sh`
Verifica integridade do repositório.

**Uso:**
```bash
chmod +x scripts/health-check.sh
./scripts/health-check.sh
```

**O que verifica:**
- ✅ Estrutura de pastas essenciais
- ✅ Arquivos obrigatórios
- ✅ Sincronização de versões
- ✅ Templates MVP Padrão
- ✅ Permissões de scripts

**Exit codes:**
- `0`: Tudo OK
- `1`: Erros encontrados

---

## 🚀 Workflow Recomendado

### Atualizar Argon Dashboard

```bash
# 1. Incrementa versão
./scripts/version-bump.sh

# 2. Adiciona novos arquivos CSS/JS na pasta criada
cp -r /caminho/argon-novo/* argon/versions/vX.Y.Z/

# 3. Atualiza CHANGELOG.md
nano CHANGELOG.md

# 4. Verifica integridade
./scripts/health-check.sh

# 5. Commit
git add .
git commit -m "feat: Atualiza Argon Dashboard para vX.Y.Z"
git push

# 6. Deploy
./scripts/deploy.sh
```

---

### Deploy de Produção

```bash
# 1. Certifique-se que está na branch correta
git checkout main

# 2. Pull das últimas alterações
git pull origin main

# 3. Verifica integridade
./scripts/health-check.sh

# 4. Deploy
./scripts/deploy.sh
```

---

## ⚙️ Configuração

### SSH Keys (Recomendado para deploy)

Para evitar digitar senha a cada deploy:

```bash
# Gera chave SSH (se ainda não tem)
ssh-keygen -t rsa -b 4096

# Copia para servidor
ssh-copy-id user@base.hsn.com.br

# Testa conexão
ssh user@base.hsn.com.br
```

---

## 🔒 Segurança

- **Nunca** commite senhas ou chaves nos scripts
- Use variáveis de ambiente para credenciais
- Teste scripts primeiro em ambiente de desenvolvimento
- Faça backup antes de deploy em produção

---

## 📞 Suporte

Para problemas com os scripts, verifique:
1. Permissões de execução (`chmod +x`)
2. Dependências instaladas (rsync, bash)
3. Conectividade SSH (para deploy)
4. Logs de erro

Documentação completa: [base.hsn.com.br](https://base.hsn.com.br)
