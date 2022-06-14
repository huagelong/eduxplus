<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/8 17:50
 */

namespace Eduxplus\CoreBundle\Lib\Grid;

use Eduxplus\CoreBundle\Lib\Base\AdminBaseService;
use Eduxplus\CoreBundle\Lib\Base\BaseAdminController;
use Twig\Environment;

class Grid
{
    protected $gridColumn = [];
    protected $gridService=null;
    protected $tableActionCallback=[];
    protected $searchField=[];
    protected $bathDelete=null;
    protected $gridBar=[];
    protected $action;
    protected $params;

    private $twig;
    protected $service;
    protected $uid = 0;

    /**
     * @var GridBarBind
     */
    protected $gridBarBind=null;
    /**
     * @var GridBind
     */
    protected $gridBind=null;
    /**
     * @var SearchBind
     */
    protected $searchBind=null;


    public function __construct(Environment $twig, AdminBaseService $service, BaseAdminController $baseAdminController)
    {
        $this->twig = $twig;
        $this->service = $service;
        $this->uid = $baseAdminController->getUid();
    }


    public function setTableAction($routeName, $callback)
    {
        if(!$this->service->isAuthorized($this->uid, $routeName)) return ;
        $this->tableActionCallback[] = $callback;
    }

    /**
     * 查看
     * @param $path
     * @param $params
     */
    public function viewAction($path){
        $this->setTableAction($path, function ($obj) use($path) {
            if(is_array($obj)){
                $id = $obj["id"];
            }else{
                $id = $obj->getId();
            }
            $params = ['id' => $id];
            $url = $this->service->genUrl($path, $params);
            $str = '<a href=' . $url . ' data-width="1000px"  data-title="查看" title="查看" class=" btn btn-default btn-xs poppage"><i class="mdi mdi-eye"></i></a>';
            return  $str;
        });
        return $this;
    }

    public function editAction($path){
        $this->setTableAction($path, function ($obj) use($path) {
            if(is_array($obj)){
                $id = $obj["id"];
            }else{
                $id = $obj->getId();
            }
            $params = ['id' => $id];
            $url = $this->service->genUrl($path, $params);
            $str = '<a href=' . $url . ' data-title="编辑" title="编辑" data-width="1000px"  class=" btn btn-info btn-xs poppage"><i class="mdi mdi-file-document-edit"></i></a>';
            return  $str;
        });
        return $this;
    }


    public function deleteAction($path){
        $this->setTableAction($path, function ($obj) use($path) {
            if(is_array($obj)){
                $id = $obj["id"];
            }else{
                $id = $obj->getId();
            }
            $params = ['id' => $id];
            $url = $this->service->genUrl($path, $params);
            return '<a href=' . $url . ' data-confirm="确认要删除吗?" title="删除"  class=" btn btn-danger btn-xs ajaxDelete"><i class="mdi mdi-delete"></i></a>';
        });
        return $this;
    }




    /**
     * 设置批量删除
     * @param $routeName
     */
    public function setBathDelete($routeName, $field="id"){
        if(!$this->service->isAuthorized($this->uid, $routeName)) return ;
        $this->id()->field($field);
        $url = $this->service->genUrl($routeName);
        $this->bathDelete = $url;
    }


    protected function gridBarBindData2array(){
        if($this->gridBarBind){
            $bindData = $this->gridBarBind->getBindData();
            if($bindData){
                $this->gridBar = $this->gridBar?array_merge($this->gridBar, $bindData):$bindData;
            }
            $this->gridBarBind = null;
        }
    }


    protected function gridBindData2array(){
        if($this->gridBind){
            $bindData = $this->gridBind->getBindData();
            if($bindData){
                $this->gridColumn = $this->gridColumn?array_merge($this->gridColumn, $bindData):$bindData;
            }
            $this->gridBind = null;
        }
    }

    protected function searchBindData2array(){
        if($this->searchBind){
            $bindData = $this->searchBind->getBindData();
            if($bindData){
                $this->searchField = $this->searchField?array_merge($this->searchField, $bindData):$bindData;
            }
            $this->searchBind = null;
        }
    }


    public function create($request, $pageSize=20, $tableTpl='default')
    {
        $this->gridBindData2array();
        $this->searchBindData2array();
        $this->gridBarBindData2array();

        $page = $request->query->getInt("page", 1);
        $pagination =  call_user_func_array([$this->gridService, $this->action], [$request, $page, $pageSize, $this->params]);
        $params = [];
        $params['request'] = $request;
        $params['searchField'] =$this->searchField;
        $params['pathinfo'] = $request->getPathInfo();
        $params['pagination'] = is_array($pagination)?$pagination[0]:$pagination;
        $params['list'] =  is_array($pagination)?$pagination[1]:$pagination;;
        $params['column'] = $this->gridColumn;
        $params['tableActionCallback'] = $this->tableActionCallback;
        $params['gridBar'] = $this->gridBar;
        $params['bathDelete'] = $this->bathDelete;
    //    var_dump($this->tableActionCallback);exit;
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
    protected function setSearchField($title, $type, $datakey,$option=null){
        $operateArr = "operates[{$datakey}]";
        $typeArr = "types[{$datakey}]";
        $valueArr = "values[{$datakey}]";
        $this->searchBindData2array();
        $this->searchField[$title] = ["type"=>$type, "field"=>$datakey, "values"=>$valueArr, "operates"=>$operateArr, "types"=>$typeArr, "option"=>$option];
    }

    /**
     * @param $title
     * @param $type text,datetime,textarea,image,boole
     * @param $datakey
     * @param null $sort
     * @return $this
     */
    protected function setTableColumn($title, $type, $datakey, $sort=null, $options=[])
    {
        $this->gridBindData2array();
        $this->gridColumn[$title] = ["type"=>$type, "field"=>$datakey, "sort"=>$sort, "options"=>$options];
    }


    protected function setTableActionColumn($routeName, $title, $type, $datakey, $sort=null, $options=[], $actionCall=null)
    {
        $this->gridBindData2array();
        if(!$this->service->isAuthorized($this->uid, $routeName)){
            $this->gridColumn[$title] = ["type"=>$type, "field"=>$datakey, "sort"=>$sort, "options"=>$options];
        }else{
            $this->gridColumn[$title] = ["type"=>$type, "field"=>$datakey, "sort"=>$sort, "options"=>$options, "actionCall"=>$actionCall];
        }
    }

    public function setGridBar($routeName, $title, $url, $iconCLass, $class='btn-info', $isBlank=0, $type="button")
    {
        $this->gridBarBindData2array();
        if(!$this->service->isAuthorized($this->uid, $routeName)) return ;
        $this->gridBar[$title] = ["url"=>$url, "iconCLass"=>$iconCLass, "class"=>$class, "isBlank"=>$isBlank, "type"=>$type];
    }


    public function setListService($service, $action, $params=null){
        $this->gridService = $service;
        $this->action = $action;
        $this->params = $params;
    }

    protected  function searchBind($title, $type){
        $this->searchBindData2array();
        $this->searchBind =  new SearchBind();
        $this->searchBind->setType($type, $title);
        return $this->searchBind;
    }

    protected  function gridBind($title, $type){
        $this->gridBindData2array();
        $this->gridBind =  new GridBind($this->service, $this->uid);
        $this->gridBind->setType($type, $title);
        return $this->gridBind;
    }

    protected  function gridBarBind($title, $type){
        $this->gridBarBindData2array();
        $this->gridBarBind =  new GridBarBind($this->service, $this->uid);
        $this->gridBarBind->setType($type, $title);
        return $this->gridBarBind;
    }

    /**
     * 按钮
     * @param $title
     * @return GridBarBind
     */
    public function gbButton($title){
        return $this->gridBarBind($title, "button");
    }

    public function gbAddButton($path, $params=[], $title="添加"){
        return $this->gridBarBind($title, "button")->route($path, $params)
            ->styleClass("btn-success")->iconClass("mdi mdi-plus");
    }

    public function id(){
//        $title = "多选";
        return $this->gridBind("", "id");
    }

    public function text($title){
        return $this->gridBind($title, "text");
    }

    public function badgePrimary($title){
        return $this->gridBind($title, "badgePrimary");
    }

    public function badgeSuccess($title){
        return $this->gridBind($title, "badgeSuccess");
    }

    public function badgeInfo($title){
        return $this->gridBind($title, "badgeInfo");
    }

    public function badgeWarning($title){
        return $this->gridBind($title, "badgeWarning");
    }

    public function badgeDanger($title){
        return $this->gridBind($title, "badgeDanger");
    }

    public function badgeDark($title){
        return $this->gridBind($title, "badgeDark");
    }

    public function badgePurple($title){
        return $this->gridBind($title, "badgePurple");
    }

    public function badgePink($title){
        return $this->gridBind($title, "badgePink");
    }

    public function badgeBrown($title){
        return $this->gridBind($title, "badgeBrown");
    }

    public function badgeMuted($title){
        return $this->gridBind($title, "badgeMuted");
    }

    public function datetime($title){
        return $this->gridBind($title, "datetime");
    }

    public function textarea($title){
        return $this->gridBind($title, "textarea");
    }

    public function tip($title){
        return $this->gridBind($title, "tip");
    }

    public function json($title){
        return $this->gridBind($title, "json");
    }

    public function code($title){
        return $this->gridBind($title, "code");
    }

    public function image($title){
        return $this->gridBind($title, "image");
    }

    public function boole($title){
        return $this->gridBind($title, "boole");
    }


    public function boole2($title){
        return $this->gridBind($title, "boole2");
    }

    public function stext($title){
        return $this->searchBind($title, "text");
    }


    public function sselect($title){
        return $this->searchBind($title, "select");
    }


    public function ssearchselect($title){
        return $this->searchBind($title, "search_select");
    }


    public function snumber($title){
        return $this->searchBind($title, "number");
    }


    public function sdaterange($title){
        return $this->searchBind($title, "daterange");
    }

    public function sdaterange2($title){
        return $this->searchBind($title, "daterange2");
    }

    public function sdatetimerange($title){
        return $this->searchBind($title, "datetimerange");
    }

    public function sdatetimerange2($title){
        return $this->searchBind($title, "datetimerange2");
    }
}
