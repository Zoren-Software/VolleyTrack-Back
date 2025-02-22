terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = ">= 5.0"
    }
  }
}

provider "aws" {
  alias  = "conta2"
  region = "us-east-1"
}

# Recurso para a zona hospedada no Route 53
resource "aws_route53_zone" "primary_zone" {
  name = var.domain_name
}

# Registro A
resource "aws_route53_record" "a_record" {
  zone_id = aws_route53_zone.primary_zone.zone_id
  name    = "volleytrack.com"
  type    = "A"
  ttl     = 60
  records = ["76.76.21.21"]
}

# Registro MX
resource "aws_route53_record" "mx_record" {
  zone_id = aws_route53_zone.primary_zone.zone_id
  name    = var.mx_record_name
  type    = "MX"
  ttl     = 300
  records = var.mx_record_values
}

# ==========================================================
# Registros NS e SOA
# ==========================================================
# Os registros NS (Nameserver) e SOA (Start of Authority) são
# criados automaticamente pelo AWS Route 53 quando uma nova
# zona hospedada é provisionada. Portanto, não é necessário 
# (nem permitido) recriar esses registros manualmente no Terraform.
#
# Caso precise editar esses registros, faça diretamente no
# console do AWS Route 53 ou via API, pois eles já são gerados
# no momento da criação da zona.
#
# Mantido comentado para referência futura.
# ==========================================================

# Registro NS - Criado automaticamente, não precisa ser definido aqui
# resource "aws_route53_record" "ns_record" {
#   zone_id = aws_route53_zone.primary_zone.zone_id
#   name    = var.domain_name
#   type    = "NS"
#   ttl     = 60
#   records = var.ns_record_values
# }

# Registro SOA - Criado automaticamente, não precisa ser definido aqui
# resource "aws_route53_record" "soa_record" {
#   zone_id = aws_route53_zone.primary_zone.zone_id
#   name    = var.domain_name
#   type    = "SOA"
#   ttl     = 900
#   records = [var.soa_record_value]
# }

# Registro TXT (_amazonses)
resource "aws_route53_record" "txt_amazonses" {
  zone_id = aws_route53_zone.primary_zone.zone_id
  name    = var.txt_amazonses_name
  type    = "TXT"
  ttl     = 60
  records = [var.txt_amazonses_value]
}

# Registro CNAME (_domainkey)
resource "aws_route53_record" "cname_domainkey_1" {
  zone_id = aws_route53_zone.primary_zone.zone_id
  name    = var.cname_domainkey_1_name
  type    = "CNAME"
  ttl     = 60
  records = [var.cname_domainkey_1_value]
}

resource "aws_route53_record" "cname_domainkey_2" {
  zone_id = aws_route53_zone.primary_zone.zone_id
  name    = var.cname_domainkey_2_name
  type    = "CNAME"
  ttl     = 60
  records = [var.cname_domainkey_2_value]
}

resource "aws_route53_record" "cname_domainkey_3" {
  zone_id = aws_route53_zone.primary_zone.zone_id
  name    = var.cname_domainkey_3_name
  type    = "CNAME"
  ttl     = 60
  records = [var.cname_domainkey_3_value]
}

# Registro Alias A (CloudFront)
resource "aws_route53_record" "cloudfront_alias_api" {
  zone_id = aws_route53_zone.primary_zone.zone_id
  name    = var.alias_api_name
  type    = "A"
  alias {
    name                   = var.alias_api_dns_name
    zone_id                = var.alias_api_zone_id
    evaluate_target_health = false
  }
}

# Alias A para graphql.volleytrack.com
resource "aws_route53_record" "cloudfront_alias_graphql" {
  zone_id = aws_route53_zone.primary_zone.zone_id
  name    = var.alias_graphql_name
  type    = "A"
  alias {
    name                   = var.alias_graphql_dns_name
    zone_id                = var.alias_graphql_zone_id
    evaluate_target_health = false
  }
}

# Registro CNAME (www.volleytrack.com)
resource "aws_route53_record" "cname_www" {
  zone_id = aws_route53_zone.primary_zone.zone_id
  name    = var.cname_www_name
  type    = "CNAME"
  ttl     = 60
  records = [var.cname_www_value]
}

# Registro coringa A
resource "aws_route53_record" "wildcard_a_record" {
  zone_id = aws_route53_zone.primary_zone.zone_id
  name    = "*.volleytrack.com"
  type    = "A"
  ttl     = 60
  records = ["76.76.21.21"]
}

# Registro de validação do API
resource "aws_route53_record" "api_validation_cname_record" {
  zone_id = aws_route53_zone.primary_zone.zone_id
  name    = "_e0d5921b3f565cc394cf510038278ad5.api.volleytrack.com"
  type    = "CNAME"
  ttl     = 60
  records = ["_4f6e474fa23849b35df783a83171b57c.zqxwgxqjmm.acm-validations.aws"]
}

# Registro de validação do GraphQL
resource "aws_route53_record" "graphql_validation_cname_record" {
  zone_id = aws_route53_zone.primary_zone.zone_id
  name    = "_6d9aae93e5790dca0ad136dabe5b6950.graphql.volleytrack.com"
  type    = "CNAME"
  ttl     = 60
  records = ["_5c940f2936480bd48db66adc0645c352.mhbtsbpdnt.acm-validations.aws"]
}
