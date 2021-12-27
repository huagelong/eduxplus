<?php

$routeArr = include("var/cache/dev/url_generating_routes.php");
foreach ($routeArr as $key => $value) {
	if(substr($key, 0,1) == "_"){
		continue;
	}
	$routeName = $key;
	$controller = $value[1]["_controller"];
	$pathPreArr = $value[3];
	$path="";
	$requirements =[];
	foreach($pathPreArr as $v){
		if($v[0]=="text"){
			$path=$v[1];
		}
	}
	$paramsArr = $value[0];
	$defaults = [];
	if($paramsArr){
		foreach($paramsArr as $pv){
				$path .= "/{".$pv."}";
				if(isset($value[1][$pv])){
					$defaults[$pv] = $value[1][$pv];
				}

				if(isset($value[2][$pv])){
					$requirements[$pv] = $value[2][$pv];
				}
		}
	}

	if($value[2]){
		print_r($value);exit;
	}

	$controllerArr = explode('\\', $controller);
	list($p, $bundel, $c, $controllerAction) = $controllerArr;
	list($controllerName, $action) = explode('::', $controllerAction);

	$routesPath = __DIR__."/bundles/eduxplus/";
	if($bundel == "CoreBundle"){
		$routesPath .= "core";
	}elseif($bundel == "ApiBundle"){
		$routesPath .= "api";
	}elseif($bundel == "QaBundle"){
		$routesPath .= "qa";
	}elseif($bundel == "WebsiteBundle"){
		$routesPath .= "website";
	}
	$routesPath .= "/src/Resources/config/";

	$mainRouteFile = $routesPath."routes.yaml";
	

	print_r($routesPath);exit;

}