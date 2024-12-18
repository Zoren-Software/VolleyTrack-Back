terraform {
    required_providers {
        aws = {
        source  = "hashicorp/aws"
        version = ">= 5.0"
        }
    }
}

# Definindo um cluster Redis
resource "aws_elasticache_cluster" "redis_cluster" {
    cluster_id           = var.redis_cluster_id
    engine               = "redis"
    engine_version       = "6.x"
    node_type            = "cache.t2.micro"
    num_cache_nodes      = 1
    parameter_group_name = "default.redis6.x"
}
