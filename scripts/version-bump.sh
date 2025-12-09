#!/bin/bash

###############################################################################
# Script de Version Bump
# Incrementa a versão do Argon e cria nova pasta versionada
###############################################################################

set -e

# Cores
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(dirname "$SCRIPT_DIR")"
VERSION_FILE="$PROJECT_ROOT/argon/VERSION"
LATEST_FILE="$PROJECT_ROOT/argon/LATEST_VERSION"

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  Version Bump - Argon Dashboard${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Verifica se arquivo VERSION existe
if [ ! -f "$VERSION_FILE" ]; then
    echo -e "${RED}❌ Arquivo VERSION não encontrado${NC}"
    exit 1
fi

# Lê versão atual
CURRENT_VERSION=$(cat "$VERSION_FILE")
echo -e "${YELLOW}Versão atual:${NC} $CURRENT_VERSION"
echo ""

# Opções de bump
echo "Escolha o tipo de incremento:"
echo "1) Patch (1.0.0 -> 1.0.1)"
echo "2) Minor (1.0.0 -> 1.1.0)"
echo "3) Major (1.0.0 -> 2.0.0)"
echo "4) Custom"
echo ""
read -p "Opção: " BUMP_TYPE

# Parse da versão atual
IFS='.' read -r -a VERSION_PARTS <<< "$CURRENT_VERSION"
MAJOR="${VERSION_PARTS[0]}"
MINOR="${VERSION_PARTS[1]}"
PATCH="${VERSION_PARTS[2]}"

case $BUMP_TYPE in
    1)
        PATCH=$((PATCH + 1))
        ;;
    2)
        MINOR=$((MINOR + 1))
        PATCH=0
        ;;
    3)
        MAJOR=$((MAJOR + 1))
        MINOR=0
        PATCH=0
        ;;
    4)
        read -p "Digite a nova versão (ex: 2.0.0): " NEW_VERSION
        ;;
    *)
        echo -e "${RED}Opção inválida${NC}"
        exit 1
        ;;
esac

if [ -z "$NEW_VERSION" ]; then
    NEW_VERSION="$MAJOR.$MINOR.$PATCH"
fi

echo ""
echo -e "${YELLOW}Nova versão:${NC} $NEW_VERSION"
echo ""

# Confirmação
read -p "Confirmar criação da versão $NEW_VERSION? (y/n) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo -e "${YELLOW}Operação cancelada${NC}"
    exit 0
fi

# Cria nova pasta versionada
NEW_VERSION_DIR="$PROJECT_ROOT/argon/versions/v$NEW_VERSION"
echo ""
echo -e "${GREEN}📁 Criando estrutura para v$NEW_VERSION...${NC}"

mkdir -p "$NEW_VERSION_DIR"/{css,js/{core,plugins},fonts,img}

# Copia arquivos da versão anterior se existir
PREVIOUS_VERSION_DIR="$PROJECT_ROOT/argon/versions/v$CURRENT_VERSION"
if [ -d "$PREVIOUS_VERSION_DIR" ]; then
    echo -e "${YELLOW}📋 Copiando arquivos da versão anterior...${NC}"
    cp -r "$PREVIOUS_VERSION_DIR"/* "$NEW_VERSION_DIR/"
fi

# Cria README da nova versão
cat > "$NEW_VERSION_DIR/README.md" << EOF
# Argon Dashboard v$NEW_VERSION

## Changelog

### Adicionado
-

### Modificado
-

### Corrigido
-

## Data de Release
**$(date +%Y-%m-%d)**

## Compatibilidade
- Bootstrap 4.6+
- jQuery 3.x
- Navegadores modernos (Chrome, Firefox, Safari, Edge)
EOF

# Atualiza arquivo VERSION
echo "$NEW_VERSION" > "$VERSION_FILE"

# Atualiza LATEST_VERSION
echo "v$NEW_VERSION" > "$LATEST_FILE"

echo ""
echo -e "${GREEN}✅ Versão v$NEW_VERSION criada com sucesso!${NC}"
echo ""
echo "Próximos passos:"
echo "1. Adicione os arquivos CSS/JS em: $NEW_VERSION_DIR"
echo "2. Atualize o README.md da versão com o changelog"
echo "3. Atualize o CHANGELOG.md principal"
echo "4. Commit e push das alterações"
