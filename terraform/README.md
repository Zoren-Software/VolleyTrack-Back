# **Terraform - Provisionamento de Infraestrutura para VoleiTrack**

![Terraform Version](https://img.shields.io/badge/Terraform-v1.0.0-blue)

Este projeto utiliza **Terraform** para provisionar a infraestrutura necessária para o funcionamento do projeto **VoleiTrack**, incluindo a criação de instâncias **RDS**, clusters **Redis**, e registros **DNS** no **Route 53**.

## Índice
- [**Terraform - Provisionamento de Infraestrutura para VoleiTrack**](#terraform---provisionamento-de-infraestrutura-para-voleitrack)
  - [Índice](#índice)
  - [**Estrutura do Projeto**](#estrutura-do-projeto)
  - [**Pré-requisitos**](#pré-requisitos)
    - [**Arquivos `.env`**](#arquivos-env)
  - [**Como Executar o Projeto**](#como-executar-o-projeto)
    - [**Passos para Provisionar a Infraestrutura**](#passos-para-provisionar-a-infraestrutura)
      - [**1. Inicializar o Terraform**:](#1-inicializar-o-terraform)
      - [**2. Validar o Plano**:](#2-validar-o-plano)
      - [**3. Aplicar o Plano**:](#3-aplicar-o-plano)
    - [**Verificando os Registros DNS**](#verificando-os-registros-dns)
  - [**Rollback ou Destruição da Infraestrutura**](#rollback-ou-destruição-da-infraestrutura)
  - [**Estrutura do Módulo `route53`**](#estrutura-do-módulo-route53)
    - [**Exemplo de Uso**:](#exemplo-de-uso)
  - [**Importante**](#importante)
    - [**Registros NS e SOA**](#registros-ns-e-soa)
  - [**Contribuições**](#contribuições)
  - [**Melhorias Futuras**](#melhorias-futuras)
  - [**Autor**](#autor)

## **Estrutura do Projeto**
A estrutura do projeto segue o seguinte formato:

```bash
/terraform/
    /modules/
        /db/           # Módulo para criação do banco de dados RDS
        /redis/        # Módulo para criação do cluster Redis
        /route53/      # Módulo para gerenciamento dos registros DNS no Route 53
    /environments/
        /conta1/       # Ambiente para a Conta 1
        /conta2/       # Ambiente para a Conta 2
```

## **Pré-requisitos**

- **Terraform** versão 1.0.0 ou superior.
- **AWS CLI** configurado com perfis das contas (`conta1` e `conta2`).
- Credenciais da **AWS** devidamente configuradas nos perfis `conta1` e `conta2`.

### **Arquivos `.env`**
Dentro da pasta `terraform/environments/conta1/` e `terraform/environments/conta2/`, crie um arquivo `.env` com as seguintes variáveis:

```bash
AWS_ACCESS_KEY_ID_CONTA1=<chave_de_acesso_conta1>
AWS_SECRET_ACCESS_KEY_CONTA1=<chave_secreta_conta1>

AWS_ACCESS_KEY_ID_CONTA2=<chave_de_acesso_conta2>
AWS_SECRET_ACCESS_KEY_CONTA2=<chave_secreta_conta2>
```

Esses arquivos serão utilizados pelo Terraform para provisionar os recursos em suas respectivas contas.

## **Como Executar o Projeto**

### **Passos para Provisionar a Infraestrutura**

#### **1. Inicializar o Terraform**:

Em cada ambiente (por exemplo, `terraform/environments/conta2/`), rode o comando:

```bash
terraform init
```

#### **2. Validar o Plano**:

Para garantir que o plano de infraestrutura está correto, execute:

```bash
terraform plan
```

#### **3. Aplicar o Plano**:

Para provisionar a infraestrutura, execute:

```bash
terraform apply
```

Confirme com `yes` quando solicitado.

```bash
y
```

Esse comando criará os recursos de banco de dados (RDS), cluster Redis, e registros DNS no Route 53.

### **Verificando os Registros DNS**

Após o provisionamento, você pode verificar os registros DNS no console do **AWS Route 53** ou utilizar o comando `dig` para confirmar que os registros **A**, **MX**, **CNAME**, e outros foram criados corretamente.

## **Rollback ou Destruição da Infraestrutura**

Se for necessário remover a infraestrutura provisionada, você pode usar o seguinte comando:

```bash
terraform destroy
```

Isso irá remover todos os recursos que foram criados pelo Terraform. **Use com cautela em ambientes de produção**.

## **Estrutura do Módulo `route53`**

O módulo de **Route 53** gerencia a criação de registros DNS. Ele suporta a criação de registros **A**, **CNAME**, **TXT**, **MX**, e outros, conforme definido no código do Terraform.

### **Exemplo de Uso**:

O módulo `route53` é configurado para criar registros como:

- **A Record**: `volleytrack.com` apontando para `76.76.21.21`.
- **CNAME Record**: `api.volleytrack.com` e `graphql.volleytrack.com` apontando para aliases CloudFront.
- **TXT Record**: Para validação do Amazon SES.
- **Wildcard A Record**: Para qualquer subdomínio (`*.volleytrack.com`).

Você pode configurar esses registros no arquivo de variáveis do Terraform.

## **Importante**

### **Registros NS e SOA**

**Não é necessário recriar os registros NS e SOA manualmente**, pois eles são criados automaticamente pelo **AWS Route 53** ao provisionar a zona hospedada. Esses registros estão comentados no código e servem como referência.

## **Contribuições**

Sinta-se à vontade para contribuir com melhorias ou ajustes necessários. Basta abrir um **Pull Request**.

## **Melhorias Futuras**

- Configurar provisionamento automático de **certificados SSL via ACM**.
- Implementar automações para monitoramento e alarmes via **CloudWatch**.

## **Autor**

Este projeto foi desenvolvido por **Maicon Cerutti** com o objetivo de automatizar o provisionamento da infraestrutura do **VoleiTrack** utilizando **Terraform** e **AWS**.