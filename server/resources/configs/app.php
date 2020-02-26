<?php
return [
    "app_name"=>env("app_name", "eduxplus"),
    "salt_key"=>env("salt_key"),
    "debug"=>env('debug', false),
    "date_default_timezone_set"=>env('timezone', "Asia/Shanghai"),
    "memory_limit"=>env("memory_limit", "1024M"),
    "task"=>[],
    "command"=>[
        \Lib\Command\Init::class,
        \Lib\Command\ClearCache::class,
    ],
    "middleware"=>[],
    "di"=>[],
    "init"=>\Lib\Handle\InitHandle::class,
//    "log"=>\Lib\Handle\LogHandle::class
];
