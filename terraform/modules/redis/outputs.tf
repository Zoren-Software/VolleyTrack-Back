output "redis_endpoint" {
    description = "Endpoint do cluster Redis"
    value       = aws_elasticache_cluster.redis_cluster.cache_nodes[0].address
}