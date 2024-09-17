output "rds_endpoint" {
    description = "Endpoint do RDS MySQL"
    value       = module.rds_conta2.db_endpoint
}

output "redis_endpoint" {
    description = "Endpoint do Redis"
    value       = module.redis_conta2.redis_endpoint
}
