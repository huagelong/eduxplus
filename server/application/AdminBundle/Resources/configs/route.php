<?php
return [
    [
        "name" => "admin",
        "prefix" => "",
//        "domain" => env('http_route_admin',"admin.dc.test"),
        "domain" => "",
        "middleware" => ['adminlogin','adminaccess', 'adminglob'],
        "routes" => [
            //$method,$path,$uses,$middleware
            ['get', '/', "admin::index/index"],
            ['get', '/msg', "admin::index/msg"],
            ['any', '/login', 'admin::account/login'],
            ['any', '/logout', 'admin::account/logout'],
            //权限管理
            ['get', '/access/access_view', 'admin::base@access@access/view'],
            ['get', '/access/role_index', 'admin::base@access@role/index'],
            ['get', '/access/role_add', 'admin::base@access@role/add'],
            ['post', '/access/role_doadd', 'admin::base@access@role/doadd'],
            ['get', '/access/role_edit/<id:\w+>', 'admin::base@access@role/edit'],
            ['post', '/access/role_doedit', 'admin::base@access@role/doedit'],
            ['get', '/access/role_delete/<id:\w+>', 'admin::base@access@role/delete'],
            ['get', '/access/role_accessmg/<id:\w+>', 'admin::base@access@role/accessMg'],
            ['post', '/access/role_accesssave/<id:\w+>', 'admin::base@access@role/accessSave'],

        ]
    ]
];


