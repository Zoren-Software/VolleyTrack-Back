output "db_endpoint" {
    description = "Endpoint do banco de dados RDS"
    value       = aws_db_instance.rds_mysql.endpoint
}

output "rds_username" {
  description = "Nome de usuário do banco de dados RDS"
  value       = aws_db_instance.rds_mysql.username
}