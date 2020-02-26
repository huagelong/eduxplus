<?php
return [
    "view"=>[
        "widget"=>[
            "admin_header"=>\Admin\Widget\HeaderWidget::class,
            "admin_nav"=>\Admin\Widget\NavWidget::class,
            "admin_footer"=>\Admin\Widget\FooterWidget::class,
            "admin_search"=>\Admin\Widget\SearchWidget::class,
        ],
    ],
    "task"=>[
        "backup" => \Admin\Task\Backup::class,
    ],
    "middleware"=>[
        //admin
        "adminaccess"=>\Admin\Middleware\AdminAccess::class,
        "adminglob"=>\Admin\Middleware\AdminGlob::class,
        "adminlogin"=>\Admin\Middleware\AdminLogin::class,
    ],
];