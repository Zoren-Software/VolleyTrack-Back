#!/bin/bash

# Script: alter_migrations_for_migration_database.sh
# Descrição: Este script é pra ser utilizado para renomear todas as datas das migrations para o formato de data atual e com sufixo m*(versão + 1).
# Autor: @CeruttiMaicon
# Data: 12/02/2025
# Versão: 1.0

# Define the directory containing the migration files
MIGRATIONS_DIR="/home/maicon/Projects/VoleiClub/database/migrations/tenant/base"

# Get the current date in the format YYYY_MM_DD
CURRENT_DATE=$(date +%Y_%m_%d)

# Loop through all migration files in the directory
for FILE in "$MIGRATIONS_DIR"/*.php; do
    # Extract the filename without the path
    FILENAME=$(basename "$FILE")
    
    # Extract the time part of the filename (after the date)
    TIME_PART=$(echo "$FILENAME" | cut -d'_' -f4-)
    
    # Construct the new filename with the current date and the original time part
    NEW_FILENAME="${CURRENT_DATE}_${TIME_PART}"
    
    # Rename the file
    mv "$FILE" "$MIGRATIONS_DIR/$NEW_FILENAME"
done