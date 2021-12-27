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

	$controllerArr = explode('\\', $controller);
    $subDir = "";
    if(count($controllerArr) == 4){
        list($p, $bundel, $c, $controllerAction) = $controllerArr;
    }else{
        list($p, $bundel, $c, $subDir, $controllerAction) = $controllerArr;
    }
    $subDir = strtolower($subDir);
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

    if(!is_file($mainRouteFile)){
        file_put_contents($mainRouteFile, "", FILE_APPEND);
    }

    $controllerName = substr($controllerName, 0,-10);
    $controllerName = strtolower($controllerName);

    $content = file_get_contents($mainRouteFile);
    if(!stristr($content, $controllerName.".yaml")){
        if($subDir){
            $append=<<<EOT
$controllerName:
  resource: "./routes/$subDir/$controllerName.yaml"
EOT;
        }else{
            $append=<<<EOT
$controllerName:
  resource: "./routes/$controllerName.yaml"
EOT;
        }
        $append .= "\n";
        file_put_contents($mainRouteFile,$append, FILE_APPEND);
    }
    if($subDir){
        $subRouteFile = $routesPath . "routes/{$subDir}/{$controllerName}.yaml";
        if(!is_dir($routesPath . "routes/{$subDir}")){
            mkdir($routesPath . "routes/{$subDir}", 0777, true);
        }
    }else{
        $subRouteFile = $routesPath . "routes/{$controllerName}.yaml";
    }

    if(!is_file($subRouteFile)){
        file_put_contents($subRouteFile, "", FILE_APPEND);
    }
    $content = file_get_contents($subRouteFile);
    $controller = str_replace('\\', "\\", $controller);
    if(!stristr($content, $controller)) {
        $append = <<<EOT
$routeName:
  path: $path
  controller: $controller
EOT;

        if ($defaults) {
            $append .= "
  defaults:  ";
            foreach ($defaults as $k => $v) {
                $append .= "\n     {$k}: {$v} ";
            }
        }

        if ($requirements) {
            $append .= "
  requirements: ";
            foreach ($defaults as $k => $v) {
                $append .= "\n      {$k}: {$v} ";
            }
        }
        $append .= "\n";
        file_put_contents($subRouteFile, $append, FILE_APPEND);
    }
}
