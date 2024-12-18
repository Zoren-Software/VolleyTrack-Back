variable "source_db_user" {
  type = string
}

variable "source_db_password" {
  type = string
}

variable "source_db_endpoint" {
  type = string
}

variable "source_db_name" {
  type = string
}

variable "target_db_user" {
  type = string
}

variable "target_db_password" {
  type = string
}

variable "target_db_endpoint" {
  type = string
}

variable "target_db_name" {
  type = string
}

# Subnets para o DMS usar
variable "subnet_ids" {
  description = "Lista de IDs das subnets que o DMS vai usar"
  type        = list(string)
}