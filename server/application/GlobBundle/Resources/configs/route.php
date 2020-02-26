<?php
return [
    [
        "name" => "glob",
        "prefix" => "glob",
        "domain" => "",
        "middleware"=>[],
        "routes" => [
            ['post', '/upyun', "glob::file/upyun"],
            ['any', '/recaptcha/<type=null:\w+>', 'glob::captcha/recaptcha'],
            ['any', '/checkcaptcha/<type=null:\w+>', 'glob::captcha/checkcaptcha'],
        ]
    ]
];