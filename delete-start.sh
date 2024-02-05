#! /bin/bash
STACK_NAME="TomcatPrueba"
REGION=us-east-1
CLI_PROFILE=default

echo -e "\n=========== Eliminando pila ================="

aws cloudformation delete-stack \
    --region us-east-1 \
    --profile default \
    --stack-name "TomcatPrueba"