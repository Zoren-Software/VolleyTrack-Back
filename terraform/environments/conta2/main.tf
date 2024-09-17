# Provedor AWS para a conta de destino
provider "aws" {
    alias  = "conta2"
    region = "us-east-1"
    profile = "conta2"  # Usando o perfil "conta2" do AWS CLI
}

# Chamando o módulo de banco de dados para a conta 2 (destino)
module "rds_conta2" {
    source = "../../modules/db"  # Referenciando o módulo de DB
    providers = {
        aws = aws.conta2
    }

    db_identifier    = var.DB_IDENTIFIER
    db_name          = var.DB_NAME
    db_user          = var.DB_USERNAME
    db_password      = var.DB_PASSWORD
    db_instance_type = var.DB_INSTANCE_TYPE
}

# Chamando o módulo de Redis para a conta 2 (destino)
module "redis_conta2" {
    source = "../../modules/redis"  # Referenciando o módulo de Redis
    providers = {
        aws = aws.conta2
    }

    redis_cluster_id = var.REDIS_CLUSTER_ID
    redis_node_type  = var.REDIS_NODE_TYPE
}
