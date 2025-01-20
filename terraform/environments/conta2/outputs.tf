output "rds_endpoint" {
  description = "Endpoint do RDS MySQL"
  value = (
    can(regex("^(.+?):[0-9]+$", module.rds_conta2.db_endpoint))
    ? regex("^(.+?):[0-9]+$", module.rds_conta2.db_endpoint)[0]
    : module.rds_conta2.db_endpoint
  )
}

output "redis_endpoint" {
    description = "Endpoint do Redis"
    value       = module.redis_conta2.redis_endpoint
}
