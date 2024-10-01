variable "db_identifier" {
  type        = string
  description = "Identificador único do banco de dados RDS"
}

variable "db_name" {
  type        = string
  description = "Nome do banco de dados"
}

variable "db_user" {
  type        = string
  description = "Usuário do banco de dados"
}

variable "db_password" {
  type        = string
  description = "Senha do banco de dados"
}

variable "db_instance_type" {
  type        = string
  description = "Tipo de instância para o banco de dados"
  default     = "db.t2.micro"  # Tamanho padrão da instância
}

variable "vpc_security_group_ids" {
  description = "IDs do grupo de segurança do VPC"
  type        = list(string)
}