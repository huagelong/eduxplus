<?php
/**
 * User: Peter Wang
 * Date: 16/10/9
 * Time: 下午12:45
 */
return [
    "server" => [
        "pdo" => [
            "type" => env('pdo_type',"mysql"),
            "prefix" => env("pdo_prefix"),
            "master" => [
                "host" => env("pdo_master_host"),
                "user" =>env("pdo_master_user"),
                "port" => env("pdo_master_port"),
                "password" => env("pdo_master_password"),
                "db_name" => env("pdo_master_db_name"),
                "timeout" => env("pdo_master_timeout"),
            ]
        ],
        "redis" => [
            "servers" => [
                env('redis_servers1'),
            ],
            "options" => [
                'prefix' => env("redis_prefix"),
                'cluster' => env("redis_cluster", "redis"),
                "timeout" => env("redis_timeout", 1),
            ],
        ]
    ],
    "diff_output"=>RESOURCE_PATH."/sqls",
];