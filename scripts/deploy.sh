#!/bin/bash

###############################################################################
# Script de Deploy - Base HSN
# Sincroniza arquivos do repositório com o servidor de produção
###############################################################################

set -e

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configurações (edite conforme necessário)
REMOTE_USER="${DEPLOY_USER:-root}"
REMOTE_HOST="${DEPLOY_HOST:-base.hsn.com.br}"
REMOTE_PATH="${DEPLOY_PATH:-/var/www/base.hsn.com.br}"
LOCAL_PATH="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  Base HSN - Deploy Script${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Verifica se rsync está instalado
if ! command -v rsync &> /dev/null; then
    echo -e "${RED}❌ rsync não está instalado${NC}"
    echo "Instale com: sudo apt-get install rsync"
    exit 1
fi

echo -e "${YELLOW}📦 Origem:${NC} $LOCAL_PATH"
echo -e "${YELLOW}🎯 Destino:${NC} $REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH"
echo ""

# Confirmação
read -p "Deseja continuar com o deploy? (y/n) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo -e "${YELLOW}Deploy cancelado${NC}"
    exit 0
fi

echo ""
echo -e "${GREEN}🚀 Iniciando deploy...${NC}"

# Rsync com exclusões
rsync -avz --delete \
    --exclude='.git' \
    --exclude='.env' \
    --exclude='node_modules' \
    --exclude='*.log' \
    --exclude='.DS_Store' \
    --exclude='Thumbs.db' \
    "$LOCAL_PATH/" \
    "$REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH"

if [ $? -eq 0 ]; then
    echo ""
    echo -e "${GREEN}✅ Deploy concluído com sucesso!${NC}"
    echo ""
    echo "Próximos passos:"
    echo "1. Verifique se os arquivos foram atualizados em $REMOTE_HOST"
    echo "2. Teste as funcionalidades principais"
    echo "3. Monitore os logs de erro"
else
    echo ""
    echo -e "${RED}❌ Erro durante o deploy${NC}"
    exit 1
fi
