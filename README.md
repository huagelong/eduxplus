## 项目介绍
eduxplus 是一款服务于教育机构、个人、小型团队的在线教育系统,可用于个人知识付费或者在线教育培训等项目使用

>注意： 当前beta版本，不建议生产使用

## demo地址
- 前台地址: https://demo.eduxplus.com/admin  
- 后台地址: https://demo.eduxplus.com/admin  账号:1000000001 密码:111


## 功能一览
- 教务
  - 校区管理
  - 老师管理
  - 班级管理
- 教研
  - 协议管理
  - 课程管理
  - 产品管理
  - 课程分类
- 商城
  - 商品管理
  - 优惠券
  - 订单管理
  - 支付管理
  - 帮助系统
  - 资讯管理
  - banner管理
- 试题
  - 试题管理
    - 知识点
      - 题目
  - 试卷管理
  - 试卷商品管理

## 特色
> 整个系统主线从课程->产品->开课计划->商品，流程清晰，支持滚动开课，开班
## 安装

#### 必要条件

- PHP >= 7.3;
- PDO PHP Extension;
- GD PHP extension
- MySQL >= 5.7;
- And the [https://symfony.com/doc/current/reference/requirements.html](https://symfony.com/doc/current/reference/requirements.html).

#### 安装步骤

1.安装 Composer (see http://getcomposer.org/download)

2.下载代码

3.进入代码根目录，配置

```$shell
cp .env .env.local
//打开.env.local 配置数据库，redis等配置
```

4.执行

```$php
composer install
composer gen
```

5.配置 计划任务 

```$shell
crontab -e

* * * * * cd /path/to/project && php bin/console i86:jobby:execute 1>> /dev/null 2>&1
```

## docker 相关

```$shell
docker build --rm -t trensy/eduxplus ./
docker run -d --restart=always trensy/eduxplus /bin/sh
```

## 相关文档

 - [https://symfony.com/](https://symfony.com/)
 - [https://symfony.com/doc/current/reference/requirements.html](https://symfony.com/doc/current/reference/requirements.html)
 - [https://getcomposer.org/doc/03-cli.md#create-project](https://getcomposer.org/doc/03-cli.md#create-project)

