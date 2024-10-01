variable "DB_IDENTIFIER" {
  description = "Identificador do banco de dados"
  type        = string
  default     = "volleytrack"
}

variable "DB_NAME" {
  description = "Nome do banco de dados"
  type        = string
  default     = "volleytrack"
}

variable "DB_USERNAME" {
  description = "Nome de usuário do banco de dados"
  type        = string
  default     = "vapor"
}

variable "DB_PASSWORD" {
  description = "Senha do banco de dados"
  type        = string
}

variable "DB_INSTANCE_TYPE" {
  description = "Tipo de instância do banco de dados"
  type        = string
  default     = "db.t3.micro"
}

variable "REDIS_CLUSTER_ID" {
  description = "ID do cluster Redis"
  type        = string
  default     = "volleytrack-cache"
}

variable "REDIS_NODE_TYPE" {
  description = "Tipo de nó do Redis"
  type        = string
  default     = "cache.t2.micro"
}

variable "AWS_ACCESS_KEY_ID" {
  description = "AWS Access Key ID"
  type        = string
}

variable "AWS_SECRET_ACCESS_KEY" {
  description = "AWS Secret Access Key"
  type        = string
}



variable "vpc_id" {
  description = "ID da VPC para associar ao recurso"
  type        = string
  default     = "vpc-001000340dd5aff43"  # Substitua pelo valor correto da sua VPC
}

variable "db_subnet_group_name" {
  description = "Nome do grupo de subnets do RDS"
  type        = string
  default = "default"
}

# Route53 specific variables
variable "domain_name" {
  description = "Nome do domínio"
  type        = string
  default     = "volleytrack.com"
}

variable "mx_record_name" {
  description = "Nome do registro MX"
  type        = string
  default     = "volleytrack.com"
}

variable "mx_record_values" {
  description = "Valores do registro MX"
  type        = list(string)
  default = [
    "1 ASPMX.L.GOOGLE.COM.",
    "5 ALT1.ASPMX.L.GOOGLE.COM.",
    "5 ALT2.ASPMX.L.GOOGLE.COM.",
    "10 ALT3.ASPMX.L.GOOGLE.COM.",
    "10 ALT4.ASPMX.L.GOOGLE.COM."
  ]
}

variable "txt_amazonses_name" {
  description = "Nome do registro TXT para Amazon SES"
  type        = string
  default     = "_amazonses.volleytrack.com"
}

variable "txt_amazonses_value" {
  description = "Valor do registro TXT para Amazon SES"
  type        = string
  default     = "RDC+BlfmbNWzjVg4502br+ENOirMZWw6axqoH1TvbeY="
}

variable "alias_api_name" {
  description = "Nome do alias para o API"
  type        = string
  default     = "api.volleytrack.com"
}

variable "alias_api_dns_name" {
  description = "Nome DNS do CloudFront para o API"
  type        = string
  default     = "dksznjsen948n.cloudfront.net"
}

variable "alias_api_zone_id" {
  description = "ID da zona hospedada do CloudFront"
  type        = string
  default     = "Z2FDTNDATAQYW2"
}

variable "alias_graphql_name" {
  description = "Nome do alias para o GraphQL"
  type        = string
  default     = "graphql.volleytrack.com"
}

variable "alias_graphql_dns_name" {
  description = "Nome DNS do CloudFront para o GraphQL"
  type        = string
  default     = "d3f15q0bjg19xk.cloudfront.net"
}

variable "alias_graphql_zone_id" {
  description = "ID da zona hospedada do CloudFront"
  type        = string
  default     = "Z2FDTNDATAQYW2"
}

variable "cname_domainkey_1_name" {
  description = "Nome do registro CNAME do domainkey 1"
  type        = string
  default     = "7he6of326bqrfz3izdpiwndhfagbvzav._domainkey.volleytrack.com"
}

variable "cname_domainkey_1_value" {
  description = "Valor do registro CNAME do domainkey 1"
  type        = string
  default     = "7he6of326bqrfz3izdpiwndhfagbvzav.dkim.amazonses.com"
}

variable "cname_domainkey_2_name" {
  description = "Nome do registro CNAME do domainkey 2"
  type        = string
  default     = "azzkeot3qjccjdmn5h7e7qin6kzp2m4q._domainkey.volleytrack.com"
}

variable "cname_domainkey_2_value" {
  description = "Valor do registro CNAME do domainkey 2"
  type        = string
  default     = "azzkeot3qjccjdmn5h7e7qin6kzp2m4q.dkim.amazonses.com"
}

variable "cname_domainkey_3_name" {
  description = "Nome do registro CNAME do domainkey 3"
  type        = string
  default     = "knznbgxhetixi5nc73mt7hmg6o37wzq3._domainkey.volleytrack.com"
}

variable "cname_domainkey_3_value" {
  description = "Valor do registro CNAME do domainkey 3"
  type        = string
  default     = "knznbgxhetixi5nc73mt7hmg6o37wzq3.dkim.amazonses.com"
}

variable "cname_www_name" {
  description = "Nome do registro CNAME do www"
  type        = string
  default     = "www.volleytrack.com"
}

variable "cname_www_value" {
  description = "Valor do registro CNAME do www"
  type        = string
  default     = "cname.vercel-dns.com"
}

variable "vpc_security_group_ids" {
  description = "IDs do grupo de segurança do VPC"
  type        = list(string)
  default     = ["sg-0173d4c68a13c394d"]  # O ID de segurança que você já encontrou
}