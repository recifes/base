#!/bin/bash

###############################################################################
# Health Check Script
# Verifica integridade dos arquivos e estrutura do repositório
###############################################################################

set -e

# Cores
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(dirname "$SCRIPT_DIR")"

ERRORS=0
WARNINGS=0

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  Health Check - Base HSN${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Verifica estrutura de pastas essenciais
echo "🔍 Verificando estrutura de pastas..."
REQUIRED_DIRS=(
    "argon"
    "argon/versions"
    "templates"
    "templates/mvp-padrao"
    "scripts"
)

for dir in "${REQUIRED_DIRS[@]}"; do
    if [ -d "$PROJECT_ROOT/$dir" ]; then
        echo -e "  ${GREEN}✓${NC} $dir"
    else
        echo -e "  ${RED}✗${NC} $dir ${RED}(não encontrado)${NC}"
        ((ERRORS++))
    fi
done

echo ""

# Verifica arquivos essenciais
echo "📄 Verificando arquivos essenciais..."
REQUIRED_FILES=(
    "README.md"
    "LICENSE"
    "CHANGELOG.md"
    ".gitignore"
    "argon/VERSION"
    "argon/LATEST_VERSION"
)

for file in "${REQUIRED_FILES[@]}"; do
    if [ -f "$PROJECT_ROOT/$file" ]; then
        echo -e "  ${GREEN}✓${NC} $file"
    else
        echo -e "  ${RED}✗${NC} $file ${RED}(não encontrado)${NC}"
        ((ERRORS++))
    fi
done

echo ""

# Verifica versões do Argon
echo "🔖 Verificando versões do Argon..."
CURRENT_VERSION=$(cat "$PROJECT_ROOT/argon/VERSION" 2>/dev/null || echo "unknown")
LATEST_VERSION=$(cat "$PROJECT_ROOT/argon/LATEST_VERSION" 2>/dev/null || echo "unknown")

echo -e "  Versão atual: ${YELLOW}$CURRENT_VERSION${NC}"
echo -e "  Latest: ${YELLOW}$LATEST_VERSION${NC}"

if [ "$CURRENT_VERSION" != "unknown" ] && [ "$LATEST_VERSION" != "unknown" ]; then
    if [ "v$CURRENT_VERSION" = "$LATEST_VERSION" ]; then
        echo -e "  ${GREEN}✓${NC} Versões sincronizadas"
    else
        echo -e "  ${YELLOW}⚠${NC} Versões não sincronizadas"
        ((WARNINGS++))
    fi
fi

echo ""

# Verifica templates
echo "📦 Verificando templates..."
TEMPLATE_FILES=(
    "templates/mvp-padrao/README.md"
    "templates/mvp-padrao/.env.example"
    "templates/mvp-padrao/database/schema.sql"
    "templates/mvp-padrao/public/index.php"
    "templates/mvp-padrao/public/login.php"
)

for file in "${TEMPLATE_FILES[@]}"; do
    if [ -f "$PROJECT_ROOT/$file" ]; then
        echo -e "  ${GREEN}✓${NC} $file"
    else
        echo -e "  ${YELLOW}⚠${NC} $file ${YELLOW}(não encontrado)${NC}"
        ((WARNINGS++))
    fi
done

echo ""

# Verifica permissões dos scripts
echo "🔐 Verificando permissões dos scripts..."
for script in "$PROJECT_ROOT"/scripts/*.sh; do
    if [ -f "$script" ]; then
        FILENAME=$(basename "$script")
        if [ -x "$script" ]; then
            echo -e "  ${GREEN}✓${NC} $FILENAME (executável)"
        else
            echo -e "  ${YELLOW}⚠${NC} $FILENAME ${YELLOW}(não executável)${NC}"
            echo "     Execute: chmod +x $script"
            ((WARNINGS++))
        fi
    fi
done

echo ""
echo -e "${GREEN}========================================${NC}"

# Sumário
if [ $ERRORS -eq 0 ] && [ $WARNINGS -eq 0 ]; then
    echo -e "${GREEN}✅ Tudo OK! Nenhum problema encontrado.${NC}"
elif [ $ERRORS -eq 0 ]; then
    echo -e "${YELLOW}⚠ $WARNINGS aviso(s) encontrado(s)${NC}"
else
    echo -e "${RED}❌ $ERRORS erro(s) e $WARNINGS aviso(s) encontrado(s)${NC}"
    exit 1
fi

echo -e "${GREEN}========================================${NC}"
