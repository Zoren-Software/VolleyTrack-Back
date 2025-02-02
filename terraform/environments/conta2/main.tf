# Provedor AWS para a conta de destino
provider "aws" {
    alias  = "conta2"
    region = "us-east-1"
    profile = "conta2"  # Usando o perfil "conta2" do AWS CLI
}

# Chamando o módulo de banco de dados para a conta 2 (destino)

# Módulo de Route 53 para a conta 2
module "route53_volleytrack" {
  source = "../../modules/route53"

  providers = {
    aws = aws.conta2
  }

  domain_name         = "volleytrack.com"
  a_record_name       = "volleytrack.com"
  a_record_value      = "76.76.21.21"

  mx_record_name      = "volleytrack.com"
  mx_record_values    = [
    "1 ASPMX.L.GOOGLE.COM.",
    "5 ALT1.ASPMX.L.GOOGLE.COM.",
    "5 ALT2.ASPMX.L.GOOGLE.COM.",
    "10 ALT3.ASPMX.L.GOOGLE.COM.",
    "10 ALT4.ASPMX.L.GOOGLE.COM.",
    "15 na3v4dn3hmjv3errujt4guwwkym3eecft6dw2cuvl74nnrkeclta.mx-verification.google.com."
  ]

  ns_record_name      = "volleytrack.com"
  ns_record_values    = [
    "ns-1283.awsdns-32.org.",
    "ns-1558.awsdns-02.co.uk.",
    "ns-745.awsdns-29.net.",
    "ns-85.awsdns-10.com."
  ]

  soa_record_name     = "volleytrack.com"
  soa_record_value    = "ns-85.awsdns-10.com. awsdns-hostmaster.amazon.com. 1 7200 900 1209600 86400"

  txt_amazonses_name  = "_amazonses.volleytrack.com"
  txt_amazonses_value = "RDC+BlfmbNWzjVg4502br+ENOirMZWw6axqoH1TvbeY="

  cname_domainkey_1_name  = "7he6of326bqrfz3izdpiwndhfagbvzav._domainkey.volleytrack.com"
  cname_domainkey_1_value = "7he6of326bqrfz3izdpiwndhfagbvzav.dkim.amazonses.com"

  cname_domainkey_2_name  = "azzkeot3qjccjdmn5h7e7qin6kzp2m4q._domainkey.volleytrack.com"
  cname_domainkey_2_value = "azzkeot3qjccjdmn5h7e7qin6kzp2m4q.dkim.amazonses.com"

  cname_domainkey_3_name  = "knznbgxhetixi5nc73mt7hmg6o37wzq3._domainkey.volleytrack.com"
  cname_domainkey_3_value = "knznbgxhetixi5nc73mt7hmg6o37wzq3.dkim.amazonses.com"

  alias_api_name       = "api.volleytrack.com"
  alias_api_dns_name   = "dksznjsen948n.cloudfront.net."
  alias_api_zone_id    = "Z2FDTNDATAQYW2"

  alias_graphql_name   = "graphql.volleytrack.com"
  alias_graphql_dns_name = "d3f15q0bjg19xk.cloudfront.net."
  alias_graphql_zone_id  = "Z2FDTNDATAQYW2"

  cname_www_name       = "www.volleytrack.com"
  cname_www_value      = "cname.vercel-dns.com"
}

module "dms" {
  source = "../../modules/dms"

  # IDs das subnets que o DMS vai usar

  subnet_ids = [
    "subnet-032b5415e1de01d56",
    "subnet-01cb994894ed750d8",
    "subnet-038b1f11685754835",
    "subnet-077960d6c70f4ea56",
    "subnet-0d2df59a7e9c52a0b",
    "subnet-0db53ded8006fa562"
  ] # Substitua com seus IDs reais de subnet

  source_db_user      = var.source_db_user
  source_db_password  = var.source_db_password
  source_db_endpoint  = var.source_db_endpoint
  source_db_name      = var.DB_NAME

  target_db_user      = var.target_db_user
  target_db_password  = var.target_db_password
  target_db_endpoint  = var.target_db_endpoint
  target_db_name      = var.DB_NAME
}