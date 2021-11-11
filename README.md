##### 安装

## Requirements

- PHP >= 7.3;
- PDO PHP Extension;
- GD PHP extension
- MySQL >= 5.7;
- And the [usual Symfony application requirements][2].

## Installation

1.Install Composer (see http://getcomposer.org/download)

2.download code 

3.make dir

```$shell
mkdir -p var && chmod -R 0777 var

```

4.config

```$shell
cp .env .env.local
//打开.env.local 配置数据库，redis等
```

5.install

```$php
composer install
composer gen
```

6.cron 

```$shell
* * * * * cd /path/to/project && php bin/console i86:jobby:execute 1>> /dev/null 2>&1
```

##### Additional documentation
[1]: https://symfony.com/
[2]: https://symfony.com/doc/current/reference/requirements.html
[3]: https://getcomposer.org/doc/03-cli.md#create-project

##### docker 相关

```$shell
docker build --rm -t trensy/eduxplus ./
docker run -d --restart=always trensy/eduxplus /bin/sh

```

