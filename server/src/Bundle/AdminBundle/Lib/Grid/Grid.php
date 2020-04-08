<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/8 17:50
 */

namespace App\Bundle\AdminBundle\Lib\Grid;

use App\Bundle\AppBundle\Lib\Base\BaseService;
use Twig\Environment;

class Grid
{
    protected $gridColumn = [];
    protected $gridService=null;
    protected $route=null;
    protected $tableActionCallback=[];
    protected $searchField=[];
    protected $gridBar=[];
    protected $action;

    private $twig;
    protected $service;

    public function __construct(Environment $twig, BaseService $service)
    {
        $this->twig = $twig;
        $this->service = $service;
    }

    public function setRoute($route){
        $this->route = $route;
        return $this;
    }


    public function setTableAction($routeName, $callback)
    {
        if(!$this->service->isAuthorized($routeName)) return ;
        $this->tableActionCallback[] = $callback;
        return $this;
    }

    public function create($request, $pageSize=20, $tableTpl='default', $searchTpl='default')
    {
        $page = $request->query->getInt("page", 1);
        $pagination =  call_user_func_array([$this->gridService, $this->action], [$request, $page, $pageSize]);
        $params = [];
        $params['request'] = $request;
        $params['searchField'] =$this->searchField;
        $params['pathinfo'] = $request->getPathInfo();
        $params['pagination'] = is_array($pagination)?$pagination[0]:$pagination;
        $params['list'] =  is_array($pagination)?$pagination[1]:$pagination;;
        $params['column'] = $this->gridColumn;
        $params['tableActionCallback'] = $this->tableActionCallback;
        $params['gridBar'] = $this->gridBar;

        $result = $this->twig->render("@Grid/".$tableTpl.".html.twig", $params);

        return $result;
    }

    /**
     * @param $title
     * @param $type text,number,daterange,datetimerange
     * @param $datakey
     * @param null $initData
     * @return $this
     */
    public function setSearchField($title, $type, $datakey,$initData=null){
        $operateArr = "operates[{$datakey}]";
        $typeArr = "types[{$datakey}]";
        $valueArr = "values[{$datakey}]";
        $this->searchField[$title] = ["type"=>$type, "field"=>$datakey, "values"=>$valueArr, "operates"=>$operateArr, "types"=>$typeArr, "initData"=>$initData];
        return $this;
    }

    /**
     * @param $title
     * @param $type text,datetime,textarea,image,boole
     * @param $datakey
     * @param null $sort
     * @return $this
     */
    public function setTableColumn($title, $type, $datakey, $sort=null, $options=[])
    {
        $this->gridColumn[$title] = ["type"=>$type, "field"=>$datakey, "sort"=>$sort, "options"=>$options];
        return $this;
    }

    public function setGridBar($routeName, $title, $url, $iconCLass, $class='btn-info')
    {
        if(!$this->service->isAuthorized($routeName)) return ;
        $this->gridBar[$title] = [$url, $iconCLass, $class];
        return $this;
    }


    public function setListService($service, $action){
        $this->gridService = $service;
        $this->action = $action;
        return $this;
    }
}
