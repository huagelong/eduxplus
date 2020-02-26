<?php
/**
 * Created by PhpStorm.
 * User: wangkaihui
 * Date: 2017/4/13
 * Time: 15:47
 */

namespace Lib\Handle;


use Lib\Support\DevTool\Boostrap;
use Lib\Support\Xtrait\Helper;

class InitHandle
{
    use Helper;

    public function perform()
    {
        //添加kendoUI标签
       $kendoUI = new \Trensy\KendoUI\Boostrap();
       $kendoUIBladeExList = $kendoUI->getBladexExList();
       $bladeExList = $this->config()->get('app.view.bladex_ex');
       if($kendoUIBladeExList){
           $bladeExListAll = array_merge($bladeExList, $kendoUIBladeExList);
           $this->config()->set('app.view.bladex_ex', $bladeExListAll);
       }
       //添加命令
        $devTool = Boostrap::getCmdList();
        $configCommands = $this->config()->get('app.command');
        $commands = array_merge($configCommands, $devTool);
        $this->config()->set('app.command', $commands);

    }
}