##### 安装

>mkdir
```$shell
mkdir -p var && chmod -R 0777 var
mkdir -p var/cache && chmod -R 0777 var/cache
mkdir -p var/log && chmod -R 0777 var/logc
```

>config
```$shell
cp .env .env.local
//打开.env.local 配置数据库，redis等
```

>install
```$php
composer install
composer gen
```

> cron 
```$shell
* * * * * cd /path/to/project && php bin/console i86:jobby:execute 1>> /dev/null 2>&1
```


##### docker 相关

```$shell
docker build --rm -t trensy/eduxplus ./
docker run -d --restart=always trensy/eduxplus /bin/sh

```

