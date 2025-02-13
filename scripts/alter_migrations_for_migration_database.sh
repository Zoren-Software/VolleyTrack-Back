#!/bin/bash

# Script: alter_migrations_for_migration_database.sh
# Descrição: Renomeia todas as migrations que possuem um prefixo específico para a próxima versão de migração.
# Autor: @CeruttiMaicon
# Data: 12/02/2025
# Versão: 1.3

# Define o diretório da pasta migrations (caminho relativo)
MIGRATIONS_DIR="$(cd "$(dirname "$0")/../database/migrations/tenant/base" && pwd)"

# Obtém a data atual no formato YYYY_MM_DD
CURRENT_DATE=$(date +%Y_%m_%d)

# Prefixo dos arquivos que devem ser alterados
PREFIX="2025_02_12_000002_m"

# Encontra a maior versão `_mX` atualmente presente
LAST_VERSION=$(ls "$MIGRATIONS_DIR"/*.php | grep -oP '_m\d+' | sed 's/_m//' | sort -nr | head -n 1)

# Se nenhuma versão for encontrada, começa da versão 2 (já que estamos partindo do _m1)
if [ -z "$LAST_VERSION" ]; then
    NEW_VERSION=2
else
    NEW_VERSION=$((LAST_VERSION + 1))
fi

# Loop por todos os arquivos de migração que começam com o prefixo definido
for FILE in "$MIGRATIONS_DIR"/"${PREFIX}"*.php; do
    # Verifica se o arquivo existe (evita erro se nenhum arquivo for encontrado)
    [ -e "$FILE" ] || continue

    # Extrai o nome do arquivo sem o caminho
    FILENAME=$(basename "$FILE")

    # Remove o prefixo antigo para extrair apenas o nome da migração
    NAME_PART=$(echo "$FILENAME" | sed -E "s/^${PREFIX}[0-9]+_//")

    # Constrói o novo nome com a nova versão
    NEW_FILENAME="${CURRENT_DATE}_000002_m${NEW_VERSION}_${NAME_PART}"

    # Evita renomear arquivos para o mesmo nome
    if [ "$FILENAME" != "$NEW_FILENAME" ]; then
        mv "$FILE" "$MIGRATIONS_DIR/$NEW_FILENAME"
        echo "Renomeado: $FILENAME -> $NEW_FILENAME"
    else
        echo "Ignorado (já no formato esperado): $FILENAME"
    fi
done
