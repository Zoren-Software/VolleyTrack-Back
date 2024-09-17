output "db_endpoint" {
    description = "Endpoint do banco de dados RDS"
    value       = aws_db_instance.rds_mysql.endpoint
}