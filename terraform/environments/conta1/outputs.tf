# Saída para o endpoint do banco de dados RDS existente na conta 1
output "existing_rds_endpoint" {
    description = "Endpoint do RDS MySQL existente na conta 1"
    value       = data.aws_db_instance.existing_rds.endpoint
}

output "existing_redis_endpoint" {
  description = "Endpoint do Redis existente na conta 1"
  value       = data.aws_elasticache_replication_group.existing_redis.primary_endpoint_address
}