# modules/route53/variables.tf

variable "domain_name" {
  description = "Nome do domínio principal"
  type        = string
}

variable "a_record_name" {
  description = "Nome do registro A"
  type        = string
}

variable "a_record_value" {
  description = "Valor do registro A (IP)"
  type        = string
}

variable "mx_record_name" {
  description = "Nome do registro MX"
  type        = string
}

variable "mx_record_values" {
  description = "Valores dos registros MX"
  type        = list(string)
}

variable "ns_record_name" {
  description = "Nome do registro NS"
  type        = string
}

variable "ns_record_values" {
  description = "Valores dos registros NS"
  type        = list(string)
}

variable "soa_record_name" {
  description = "Nome do registro SOA"
  type        = string
}

variable "soa_record_value" {
  description = "Valor do registro SOA"
  type        = string
}

variable "txt_amazonses_name" {
  description = "Nome do registro TXT (_amazonses)"
  type        = string
}

variable "txt_amazonses_value" {
  description = "Valor do registro TXT (_amazonses)"
  type        = string
}

variable "cname_domainkey_1_name" {
  description = "Nome do registro CNAME DomainKey 1"
  type        = string
}

variable "cname_domainkey_1_value" {
  description = "Valor do registro CNAME DomainKey 1"
  type        = string
}

variable "cname_domainkey_2_name" {
  description = "Nome do registro CNAME DomainKey 2"
  type        = string
}

variable "cname_domainkey_2_value" {
  description = "Valor do registro CNAME DomainKey 2"
  type        = string
}

variable "cname_domainkey_3_name" {
  description = "Nome do registro CNAME DomainKey 3"
  type        = string
}

variable "cname_domainkey_3_value" {
  description = "Valor do registro CNAME DomainKey 3"
  type        = string
}

variable "alias_api_name" {
  description = "Nome do registro Alias para API"
  type        = string
}

variable "alias_api_dns_name" {
  description = "DNS do CloudFront para o Alias da API"
  type        = string
}

variable "alias_api_zone_id" {
  description = "ID da zona do CloudFront para o Alias da API"
  type        = string
}

variable "alias_graphql_name" {
  description = "Nome do registro Alias para GraphQL"
  type        = string
}

variable "alias_graphql_dns_name" {
  description = "DNS do CloudFront para o Alias GraphQL"
  type        = string
}

variable "alias_graphql_zone_id" {
  description = "ID da zona do CloudFront para o Alias GraphQL"
  type        = string
}

variable "cname_www_name" {
  description = "Nome do registro CNAME para www"
  type        = string
}

variable "cname_www_value" {
  description = "Valor do registro CNAME para www"
  type        = string
}
