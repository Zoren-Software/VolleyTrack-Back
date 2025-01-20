terraform {
    required_providers {
        aws = {
        source  = "hashicorp/aws"
        version = ">= 5.0"
        }
    }
}

# Definindo um banco de dados RDS
resource "aws_db_instance" "rds_mysql" {
    identifier               = var.db_identifier
    allocated_storage        = 20
    instance_class           = "db.t3.micro"
    engine                   = "mysql"
    engine_version           = "8.0.35"  # Alterando para uma versão compatível com db.t2.micro
    username                 = var.db_user
    password                 = var.db_password
    parameter_group_name     = "default.mysql8.0"
    skip_final_snapshot      = true
    publicly_accessible      = true
    vpc_security_group_ids   = var.vpc_security_group_ids
}
