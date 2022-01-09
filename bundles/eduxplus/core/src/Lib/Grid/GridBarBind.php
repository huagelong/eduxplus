<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/5/21 17:13
 */

namespace Eduxplus\CoreBundle\Lib\Grid;


class GridBarBind
{
    protected $struct=["type"=>"button","route"=>"","url"=>"", "iconCLass"=>"mdi mdi-plus", "class"=>"btn-success", "isBlank"=>0];
    private $title=null;
    protected $service;
    protected $uid;

    public function __construct($service, $uid)
    {
        $this->service = $service;
        $this->uid = $uid;
    }

    public function getBindData(){
        if($this->struct["route"]){
            $uid = $this->uid;
            if($this->service->isAuthorized($uid, $this->struct["route"])){
                $new = [$this->title=>$this->struct];
                return $new;
            }
        }
        return [];
    }

    public function setType($type, $title){
        $this->title = $title;
        $this->struct['type']=$type;
        return $this;
    }

    public function route($route, $params=[]){
        $this->struct["route"] = $route;
        $this->struct["url"] = $this->service->genUrl($route, $params);
        return $this;
    }

    public function iconClass($icon){
        $this->struct["iconCLass"] = $icon;
        return $this;
    }

    public function styleClass($class){
        $this->struct["class"] = $class;
        return $this;
    }

    public function isBlank($isBlank=0){
        $this->struct["isBlank"] = $isBlank;
        return $this;
    }

}
