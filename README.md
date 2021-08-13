#####php运行环境安装

> 系统:centos7

> 安装shell
```
curl -LsS http://suo.im/61vMXT -o lnmp
sed -i -e 's/\r//g' ./lnmp
chmod +x lnmp
./lnmp
```

>install
```$php
./bin/console  app:install all=1
```
> cron 
```$shell
* * * * * cd /path/to/project && php bin/console i86:jobby:execute 1>> /dev/null 2>&1
```

>other
```$shell
./bin/console config:dump imper86_jobby
```
#####docker 相关

```shell
docker rmi -f eduxplus/server
docker build --rm -t eduxplus/server ./
docker start eduxplus/server
docker run -d eduxplus/server /bin/sh
docker run eduxplus_code /bin/sh
docker run -d eduxplus_code /bin/sh

docker ps -a
docker image ls
docker run -itd 242c544f2eb5 /bin/sh
docker attach fe3a1b78115e5856bd1ec8c39f0529305520901b4223a107b678acff8835634b

docker exec -it 775c7c9ee1e1 /bin/sh

docker-compose -f k8s-docker-compose.yml build

# 停止所有容器
docker ps -a | grep "Exited" | awk '{print $1 }'|xargs docker stop
# 删除所有容器
docker ps -a | grep "Exited" | awk '{print $1 }'|xargs docker rm
# 删除所有none镜像
docker images|grep none|awk '{print $3 }'|xargs docker rmi

docker login --username=100014397005 ccr.ccs.tencentyun.com

docker build -t eduxplus_code -f docs/k8s/code/Dockerfile .
docker tag eduxplus_code ccr.ccs.tencentyun.com/eduxplus/code:latest
docker push ccr.ccs.tencentyun.com/eduxplus/code:latest

docker build -t eduxplus_php -f docs/k8s/php-fpm/Dockerfile .
docker tag eduxplus_php ccr.ccs.tencentyun.com/eduxplus/php:latest
docker push ccr.ccs.tencentyun.com/eduxplus/php:latest

docker build -t eduxplus_nginx -f docs/k8s/nginx/Dockerfile .
docker tag eduxplus_nginx ccr.ccs.tencentyun.com/eduxplus/nginx:latest
docker push ccr.ccs.tencentyun.com/eduxplus/nginx:latest

-----------------------------------------------------------
php .\bin\console app:upDbStruct App\Bundle\QABundle\Entity

```

