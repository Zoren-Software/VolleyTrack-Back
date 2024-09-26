terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = ">= 3.0"
    }
  }
}

provider "aws" {
  alias = "conta2"
  region = "us-east-1"
}

# Recurso para a zona hospedada no Route 53
resource "aws_route53_zone" "primary_zone" {
  name = var.domain_name
}

# Registro A
resource "aws_route53_record" "a_record" {
  zone_id = aws_route53_zone.primary_zone.zone_id
  name    = var.a_record_name
  type    = "A"
  ttl     = 60
  records = [var.a_record_value]
}

# Registro MX
resource "aws_route53_record" "mx_record" {
  zone_id = aws_route53_zone.primary_zone.zone_id
  name    = var.mx_record_name
  type    = "MX"
  ttl     = 300
  records = var.mx_record_values
}

# Registro NS
resource "aws_route53_record" "ns_record" {
  count   = length(data.aws_route53_zone.zone) == 0 ? 1 : 0  # Não criar se já existir

  zone_id = aws_route53_zone.primary_zone.zone_id
  name    = var.domain_name
  type    = "NS"
  ttl     = 60
  records = var.ns_record_values
}

# Registro SOA
resource "aws_route53_record" "soa_record" {
  count   = length(data.aws_route53_zone.zone) == 0 ? 1 : 0  # Não criar se já existir

  zone_id = data.aws_route53_zone.zone.zone_id
  name    = var.domain_name
  type    = "SOA"
  ttl     = 900
  records = [var.soa_record_value]
}

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


data "aws_route53_zone" "zone" {
  name = var.domain_name
}