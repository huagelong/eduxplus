<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/5/21 17:11
 */

namespace Eduxplus\CoreBundle\Lib\Grid;


class GridBind
{
    protected $struct=["type"=>"", "field"=>"", "sort"=>"", "options"=>[], "actionCall"=>[], "routeName"=>""];
    private $title=null;
    protected $service;
    protected $uid;

    public function __construct($service, $uid)
    {
        $this->service = $service;
        $this->uid = $uid;
    }

    public function getBindData(){

        $new = [$this->title=>$this->struct];
        return $new;
    }

    public function setType($type, $title){
        $this->title = $title;
        $this->struct['type']=$type;
        return $this;
    }

    public function field($value){
        $this->struct["field"] = $value;
        return $this;
    }

    public function sort($value){
        $this->struct["sort"] = $value;
        return $this;
    }

    /**
     * 供选择的数据
     * @param $value
     * @return $this
     */
    public function options($value){
        $this->struct["options"] = $value;
        return $this;
    }

    /**
     * 可操作的数据，按钮，链接等
     * @param $route
     * @param $value
     * @return $this
     */
    public function actionCall($route, $value){
        $uid = $this->uid;
        if($this->service->isAuthorized($uid, $route)){
            $this->struct["actionCall"] = $value;
        }
        return $this;
    }

}
