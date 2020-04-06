<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/8 17:50
 */

namespace App\Bundle\AdminBundle\Lib\Grid;

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

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function setRoute($route){
        $this->route = $route;
        return $this;
    }


    public function setTableAction($callback)
    {
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
        $params['pagination'] = $pagination;
        $params['column'] = $this->gridColumn;
        $params['tableActionCallback'] = $this->tableActionCallback;
        $params['gridBar'] = $this->gridBar;

        $result = $this->twig->render("@Grid/tables/".$tableTpl.".html.twig", $params);

        return $result;
    }


    public function setSearchField($title, $type, $datakey,$initData=null){
        $datakey = str_replace(".", "_", $datakey);
        $operate = "-".$datakey;
        $datakey = $type."-".$datakey;
        $this->searchField[$title] = [$type, $datakey, $initData, $operate];
        return $this;
    }

    public function setTableColumn($title, $type, $datakey, $sort=null)
    {
        $this->gridColumn[$title] = [$type, $datakey, $sort];
        return $this;
    }

    public function setGridBar($title, $url, $iconCLass, $class='btn-info')
    {
        $this->gridBar[$title] = [$url, $iconCLass, $class];
        return $this;
    }


    public function setService($service, $action){
        $this->gridService = $service;
        $this->action = $action;
        return $this;
    }
}
