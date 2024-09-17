# Credenciais AWS para Conta 1 (Origem)
variable "AWS_ACCESS_KEY_ID_CONTA1" {
    type        = string
    description = "Chave de acesso AWS para a conta 1 (origem)"
}

variable "AWS_SECRET_ACCESS_KEY_CONTA1" {
    type        = string
    description = "Chave secreta AWS para a conta 1 (origem)"
}

# Identificador da instância RDS existente na conta 1
variable "EXISTING_RDS_IDENTIFIER" {
    type        = string
    description = "Identificador do RDS existente na conta 1"
    default = "volleytrack"
}

# Identificador do cluster Redis existente na conta 1
variable "EXISTING_REDIS_CLUSTER_ID" {
    type        = string
    description = "Identificador do Redis existente na conta 1"
    default = "volleytrack-cache"
}