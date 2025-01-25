resource "aws_dms_replication_instance" "replication_instance" {
  replication_instance_class = "dms.t3.micro"  # Classe econômica para testes.
  allocated_storage          = 50
  engine_version             = "3.5.4"         # Teste uma versão estável.
  replication_instance_id    = "dms-replication-instance"
  replication_subnet_group_id = aws_dms_replication_subnet_group.dms_subnet_group.id
  vpc_security_group_ids      = [aws_security_group.dms_security_group.id]
  # Configuração adicional
  publicly_accessible = true
  tags = {
    Name = "DMS Replication Instance"
  }
}

resource "aws_dms_endpoint" "source_endpoint" {
  endpoint_id    = "source-mysql-db"
  endpoint_type  = "source"
  engine_name    = "mysql"
  username       = var.source_db_user
  password       = var.source_db_password
  server_name    = var.source_db_endpoint
  port           = 3306
  database_name  = var.source_db_name
}

resource "aws_dms_endpoint" "target_endpoint" {
  endpoint_id    = "target-mysql-db"
  endpoint_type  = "target"
  engine_name    = "mysql"
  username       = var.target_db_user
  password       = var.target_db_password
  server_name    = replace(var.target_db_endpoint, ":3306", "")
  port           = 3306
  database_name  = "" # Deixe vazio para suportar múltiplos bancos
}

resource "aws_dms_replication_task" "migration_task" {
  replication_task_id        = "migration-task"
  source_endpoint_arn        = aws_dms_endpoint.source_endpoint.endpoint_arn
  target_endpoint_arn        = aws_dms_endpoint.target_endpoint.endpoint_arn
  migration_type             = "full-load"
  replication_instance_arn   = aws_dms_replication_instance.replication_instance.replication_instance_arn

  # Configuração de seleção de tabelas (inclui todas as tabelas e esquemas)
  table_mappings = jsonencode({
    "rules": [
      # Importar todos os tenants
      {
        "rule-type": "selection",
        "rule-id": "1",
        "rule-name": "include-all-tables",
        "rule-action": "include",
        "object-locator": {
          "schema-name": "%",
          "table-name": "%"
        }
      },
      # Excluir schemas de sistema
      {
        "rule-type": "selection",
        "rule-id": "2",
        "rule-name": "exclude-information-schema",
        "rule-action": "exclude",
        "object-locator": {
            "schema-name": "information_schema",
            "table-name": "%"
      }
      },
      {
        "rule-type": "selection",
        "rule-id": "3",
        "rule-name": "exclude-mysql-schema",
        "rule-action": "exclude",
        "object-locator": {
            "schema-name": "mysql",
            "table-name": "%"
        }
      },
      {
        "rule-type": "selection",
        "rule-id": "4",
        "rule-name": "exclude-performance-schema",
        "rule-action": "exclude",
        "object-locator": {
            "schema-name": "performance_schema",
            "table-name": "%"
      }
      },
        {
        "rule-type": "selection",
        "rule-id": "5",
        "rule-name": "exclude-sys-schema",
        "rule-action": "exclude",
        "object-locator": {
            "schema-name": "sys",
            "table-name": "%"
        }
      }
    ]
  })

  # Configurações avançadas de replicação
  replication_task_settings = jsonencode({
    "TargetMetadata": {
      "ParallelLoadThreads": 8    # Threads para carregar dados
    },
    "FullLoadSettings": {
      "CreatePkAfterFullLoad": true,  # Cria chave primária se faltar no destino
      "TargetTablePrepMode": "DROP_AND_CREATE", # Recria as tabelas no destino
      "MaxFullLoadSubTasks": 16       # Subtarefas paralelas durante a migração
    },
    "ErrorBehavior": {
      "DataErrorPolicy": "LOG_ERROR",      # Continua no caso de erro de dados
      "TableErrorPolicy": "SUSPEND_TABLE", # Suspende apenas tabelas problemáticas
      "FailOnNoTablesCaptured": false     # Não falha se não capturar tabelas
    },
    "Logging": {
      "EnableLogging": true,
    }
  })

  start_replication_task = true
  tags = {
    Name = "DMS Migration Task"
  }
}

# Security Group para permitir que o DMS se comunique com o banco de dados
resource "aws_security_group" "dms_security_group" {
  name_prefix = "dms-security-group"

  ingress {
    from_port   = 3306
    to_port     = 3306
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]  # Permitir o tráfego de entrada na porta do MySQL
  }

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }

  tags = {
    Name = "DMS Security Group"
  }
}

resource "aws_dms_replication_subnet_group" "dms_subnet_group" {
  replication_subnet_group_id          = "dms-subnet-group"
  replication_subnet_group_description = "Subnet group for DMS replication"  # Adicione uma descrição aqui
  subnet_ids                           = var.subnet_ids  # IDs das subnets, como configurado anteriormente

  tags = {
    Name = "DMS Subnet Group"
  }
}

# Cria a role se ela não existir. Se já existir, o Terraform lidará com o erro.
resource "aws_iam_role" "dms_vpc_role" {
  name = "dms-vpc-role"
  
  assume_role_policy = jsonencode({
    "Version": "2012-10-17",
    "Statement": [
      {
        "Action": "sts:AssumeRole",
        "Effect": "Allow",
        "Principal": {
          "Service": "dms.amazonaws.com"
        }
      }
    ]
  })
}

resource "aws_iam_policy" "dms_vpc_access_policy" {
  name        = "dms-vpc-access-policy"
  description = "Policy granting DMS access to VPC resources"

  policy = jsonencode({
    "Version": "2012-10-17",
    "Statement": [
      {
        "Effect": "Allow",
        "Action": [
          "ec2:DescribeVpcs",
          "ec2:DescribeSubnets",
          "ec2:DescribeSecurityGroups",
          "ec2:CreateNetworkInterface",
          "ec2:DeleteNetworkInterface",
          "ec2:DescribeNetworkInterfaces"
        ],
        "Resource": "*"
      }
    ]
  })
}

# Vincula a política à role
resource "aws_iam_role_policy_attachment" "dms_vpc_policy_attachment" {
  policy_arn = aws_iam_policy.dms_vpc_access_policy.arn
  role       = aws_iam_role.dms_vpc_role.name
}

# Anexa as políticas à role criada ou existente
resource "aws_iam_role_policy_attachment" "dms_vpc_role_vpc_access" {
  role       = aws_iam_role.dms_vpc_role.name
  policy_arn = "arn:aws:iam::aws:policy/service-role/AmazonDMSVPCManagementRole"
}

resource "aws_iam_role_policy_attachment" "dms_vpc_role_rds_access" {
  role       = aws_iam_role.dms_vpc_role.name
  policy_arn = "arn:aws:iam::aws:policy/AmazonRDSFullAccess"
}

resource "aws_iam_role_policy_attachment" "dms_vpc_role_ec2_access" {
  role       = aws_iam_role.dms_vpc_role.name
  policy_arn = "arn:aws:iam::aws:policy/AmazonEC2FullAccess"
}
