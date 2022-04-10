<div align="center"><h3 align="center">eduxplus 在线教育平台</h3></div>
<div align="center"><h3 align="center">eduxplus 是一款服务于教育机构、个人、小型团队的在线教育系统,可用于个人知识付费或者在线教育培训等项目使用</h3></div>
 
<p align="center">
    <a href='https://gitee.com/wangkaihui/eduxplus/stargazers'><img src='https://gitee.com/wangkaihui/eduxplus/badge/star.svg?theme=dark' alt='star'></img></a>
    <a href='https://gitee.com/wangkaihui/eduxplus/members'><img src='https://gitee.com/wangkaihui/eduxplus/badge/fork.svg?theme=dark' alt='fork'></img></a>
</p>

## 项目介绍
eduxplus 是一款服务于教育机构、个人、小型团队的在线教育系统,可用于个人知识付费或者在线教育培训等项目使用,基于symfony.

## demo地址
- 前台地址: https://dev.eduxplus.com  账号:17621487000 密码:Eduxplus@1
- 后台地址: https://dev.eduxplus.com/admin  账号:17621487000 密码:Eduxplus@1


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


## 相关截图
![图片1](docs/screens/admin-01.png)
![图片1](docs/screens/admin-02.png)
![图片1](docs/screens/admin-03.png)
![图片1](docs/screens/admin-04.png)
![图片1](docs/screens/admin-05.png)
![图片1](docs/screens/admin-06.png)
## 特色
> 整个系统主线从课程->产品->开课计划->商品，流程清晰，支持滚动开课，开班
## 安装

#### 必要条件

- PHP >= 7.2;
- PDO PHP Extension;
- GD PHP extension;
- ZIP PHP extension;
- MySQL >= 5.7;
- And the [https://symfony.com/doc/current/reference/requirements.html](https://symfony.com/doc/current/reference/requirements.html).

#### 安装步骤
> 以下步骤在linux系统操作

1.安装 Composer (详见 http://getcomposer.org/download)

2.安装 symfony_cli (详见 https://symfony.com/download)

3. 安装 git (详见 https://git-scm.com/downloads)

4.下载代码
```$shell
git clone https://gitee.com/wangkaihui/eduxplus.git
```

5.进入代码根目录，修改配置

```$shell
cp .env .env.local
// 打开.env.local 配置数据库DATABASE_URL，redis配置REDIS_HOST
```

4.执行

```$php
composer install
//初始化数据
composer gen
```

5.配置 计划任务 

```$shell
crontab -e
* * * * * cd /path-to-your-project && symfony console schedule:run >> /dev/null 2>&1
```
6.nginx配置
> ./docs/nginx.conf 是配置例子，可以参考

7.or 直接运行下面命令
```$shell
symfony serve
//打开 http://127.0.0.1:8000 进行预览
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

