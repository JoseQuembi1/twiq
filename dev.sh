#!/bin/bash

# Instalar dependências
composer install

# Executar testes
composer test

# Verificar estilo
composer format

# Análise estática
composer analyse

# Gerar documentação
php artisan vendor:publish --tag=twiq-docs --force