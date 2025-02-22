provider "aws" {
  alias   = "conta1"
  region  = "us-east-1"
  profile = "conta1"
}

provider "aws" {
  alias   = "conta2"
  region  = "us-east-1"
  profile = "conta2"
}