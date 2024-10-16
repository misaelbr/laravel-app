#!/bin/bash

# Iniciar o Laravel Sail em modo detached
echo "Iniciando Laravel Sail em modo detached..."
./vendor/bin/sail up -d

# Aguardar alguns segundos para garantir que os containers estejam prontos
echo "Aguardando os containers estarem prontos..."
sleep 10

# Iniciar o scheduler
echo "Iniciando o scheduler..."
./vendor/bin/sail artisan schedule:work &

# Iniciar o queue worker
echo "Iniciando o gerenciamento de filas..."
./vendor/bin/sail artisan queue:work &

# Iniciar o PNPM dev
echo "Iniciando o PNPM dev..."
./vendor/bin/sail exec -T laravel.test pnpm dev &

echo "Todos os serviços foram iniciados com sucesso."
