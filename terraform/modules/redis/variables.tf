variable "redis_cluster_id" {
  type        = string
  description = "ID único do cluster Redis"
}

variable "redis_node_type" {
  type        = string
  description = "Tipo de instância do Redis"
  default     = "cache.t2.micro"  # Tamanho padrão da instância
}