#!/bin/sh
docker build -t eduxplus_code -f docs/k8s/code/Dockerfile .
docker build -t eduxplus_nginx -f docs/k8s/nginx/Dockerfile .
docker build -t eduxplus_php -f docs/k8s/php-fpm/Dockerfile .
