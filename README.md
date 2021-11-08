#####php运行环境安装

> 系统:centos7

>install
```$php
./bin/console  app:install all=1
```

>mkdir

```$shell
mkdir -p var && chmod -R 0777 var
```
> cron 
```$shell
* * * * * cd /path/to/project && php bin/console i86:jobby:execute 1>> /dev/null 2>&1
```

>other
```$shell
./bin/console config:dump imper86_jobby
```

> https://github.com/microsoft/vscode-remote-try-php  

#####docker 相关

```$shell
docker rmi -f trensy/eduxplus
docker build --rm -t trensy/eduxplus ./
docker start trensy/eduxplus
docker run -d trensy/eduxplus /bin/sh

docker ps -a
docker image ls
docker run -itd 242c544f2eb5 /bin/sh
docker exec -it 775c7c9ee1e1 /bin/sh
# 停止所有容器
docker ps -a | grep "Exited" | awk '{print $1 }'|xargs docker stop
# 删除所有容器
docker ps -a | grep "Exited" | awk '{print $1 }'|xargs docker rm
# 删除所有none镜像
docker images|grep none|awk '{print $3 }'|xargs docker rmi

#
php .\bin\console app:upDbStruct App\Bundle\QABundle\Entity

```

