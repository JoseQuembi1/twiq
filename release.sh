#!/bin/bash

# Verificar se versão foi fornecida
if [ -z "$1" ]
then
    echo "Por favor, forneça um número de versão (ex: 1.0.1)"
    exit 1
fi

VERSION=$1

# Atualizar versão no composer.json
sed -i '' "s/\"version\": \".*\"/\"version\": \"$VERSION\"/" composer.json

# Atualizar CHANGELOG.md
DATE=$(date +%Y-%m-%d)
echo "## [$VERSION] - $DATE

### Added
- 

### Changed
- 

### Fixed
- 

" | cat - CHANGELOG.md > temp && mv temp CHANGELOG.md

# Commit alterações
git add composer.json CHANGELOG.md
git commit -m "Release version $VERSION"

# Criar e push tag
git tag "v$VERSION"
git push origin main "v$VERSION"