<?php

$routeArr = include("var/cache/dev/url_generating_routes.php");
print_r(current($routeArr));

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
	list($pre, $bundel, $c, $controllerName, $action) = $controllerArr;
	
	print_r($controllerArr);exit;

}