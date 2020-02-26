<?php
/**
 * Trensy Framework
 *
 * PHP Version 7
 *
 * @author          kaihui.wang <hpuwang@gmail.com>
 * @copyright      trensy, Inc.
 * @package         trensy/framework
 * @version         3.0.0
 */

namespace Trensy;

use Trensy\Foundation\AnnotationLoadInterface;
use Lib\Base\BaseController;

abstract class MiddlewareAbstract implements AnnotationLoadInterface
{

    abstract public function before();
    abstract public function after();

    /**
     * @return \Trensy\Http\Request
     */
    public function getRequest()
    {
        return Context::request();
    }

    /**
     * @return \Trensy\Http\Response
     */
    public function getResponse()
    {
        return Context::response();
    }

    public function getController(){
        $request  = $this->getRequest();
        $response  = $this->getResponse();
        $controllerObj = new BaseController;
        $controllerObj->request = $request;
        $controllerObj->response = $response;
        $controllerObj->view = $response->view;
        $obj = Di::injectOn($controllerObj);
        return $obj;
    }

}