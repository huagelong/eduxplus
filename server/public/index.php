<?php
define("ROOT_PATH", __DIR__."/..");
define("STORAGE_PATH", ROOT_PATH."/storage");
define("APPLICATION_PATH",ROOT_PATH."/application");
define("RESOURCE_PATH",ROOT_PATH."/resources");
require_once ROOT_PATH . "/vendor/autoload.php";
$request = new \Trensy\Foundation\Bootstrap\Http\Request();
$response = new \Trensy\Foundation\Bootstrap\Http\Response();
\Trensy\Foundation\Application::runWeb($request, $response);
