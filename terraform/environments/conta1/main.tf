# Provedor AWS para a conta de origem (conta 1)
provider "aws" {
    alias  = "conta1"
    region = "us-east-1"
    profile = "conta1"  # Usando o perfil "conta1" do AWS CLI
}

# (Opcional) - Aqui você pode definir algum recurso existente para gerenciar ou consultar
# Por exemplo, listar uma instância RDS existente
data "aws_db_instance" "existing_rds" {
    db_instance_identifier = var.EXISTING_RDS_IDENTIFIER
}

# # (Opcional) - Listar instâncias do Redis existentes
# data "aws_elasticache_cluster" "existing_redis" {
#     cluster_id = var.EXISTING_REDIS_CLUSTER_ID
# }

# Consultando um ElastiCache Replication Group (Redis)
data "aws_elasticache_replication_group" "existing_redis" {
  replication_group_id = var.EXISTING_REDIS_CLUSTER_ID
}