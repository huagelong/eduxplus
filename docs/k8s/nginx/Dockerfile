FROM alpine:3.10

LABEL maintainer="Vincent Composieux <vincent.composieux@gmail.com>"

RUN apk add --update --no-cache nginx busybox-extras

COPY docs/k8s/nginx/nginx.conf /etc/nginx/
COPY docs/k8s/nginx/symfony.conf /etc/nginx/conf.d/

RUN echo "upstream php-upstream { server 127.0.0.1:9001; }" > /etc/nginx/conf.d/upstream.conf

RUN adduser -D -g '' -G www-data www-data

WORKDIR /var/www/symfony

RUN rm -rf /etc/nginx/conf.d/default.conf

CMD ["nginx"]

EXPOSE 80
EXPOSE 443
