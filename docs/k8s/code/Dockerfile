FROM alpine:3.10

LABEL maintainer="kaihui.wang <hpuwang@gmail.com>"

WORKDIR /var/www/symfony

ADD ./. $WORKDIR

RUN rm -rf .env;\
    cp -rf .env.test .env;\
    touch /tmp/test
ADD docs/k8s/code/start.sh /tmp/start.sh
RUN chmod +x /tmp/start.sh
CMD ["sh","/tmp/start.sh"]
