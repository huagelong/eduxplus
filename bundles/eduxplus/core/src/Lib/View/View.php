<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/4/7 11:17
 */

namespace Eduxplus\CoreBundle\Lib\View;

use Twig\Environment;

class View
{
    protected $viewField = [];
    protected $disableSubmit = 0;
    private $twig;
    /**
     * @var Bind
     */
    private $bind = null;


    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function disableSubmit()
    {
        $this->disableSubmit = 1;
    }

    public function create($tpl = 'default')
    {
        $this->bindData2form();
        $data = [];
        $data['viewField'] = $this->viewField;
        $data['disableSubmit'] = $this->disableSubmit;
        $result = $this->twig->render("@View/" . $tpl . ".html.twig", $data);

        return $result;
    }

    protected function bindData2form()
    {
        if ($this->bind) {
            $bindFormField = $this->bind->getFormField();
            if ($bindFormField) {
                $this->viewField = $this->viewField ? array_merge($this->viewField, $bindFormField) : $bindFormField;
            }
            $this->bind = null;
        }
    }

    protected  function bind($title, $type)
    {
        $this->bindData2form();
        $this->bind =  new Bind();
        $this->bind->setType($type, $title);
        return $this->bind;
    }

    /**
     * input 文本
     *
     * @param $title
     * @return Bind
     */
    public function text($title)
    {
        return $this->bind($title, "text");
    }


    /**
     * 初始值展示
     * @param $title
     * @return Bind
     */
    public  function string($title)
    {
        return $this->bind($title, "string");
    }


    /**
     * 日期范围，不含时分秒
     * @param $title
     * @return Bind
     */
    public  function daterange($title)
    {
        return $this->bind($title, "daterange");
    }

    /**
     * 时间范围，含时分秒
     * @param $title
     * @return Bind
     */
    public  function datetimerange($title)
    {
        return $this->bind($title, "datetimerange");
    }

    /**
     * 时间
     * @param $title
     * @return Bind
     */
    public  function datetime($title)
    {
        return $this->bind($title, "datetime");
    }

    /**
     * 日期
     * @param $title
     * @return Bind
     */
    public  function date($title)
    {
        return $this->bind($title, "date");
    }

    /**
     * 文件上传
     * @param $title
     * @return Bind
     */
    public  function file($title)
    {
        return $this->bind($title, "file");
    }

    /**
     * 密码文本
     *
     * @param $title
     * @return Bind
     */
    public  function password($title)
    {
        return $this->bind($title, "password");
    }

    /**
     * 大文本
     *
     * @param $title
     * @return Bind
     */
    public  function textarea($title)
    {
        return $this->bind($title, "textarea");
    }

    /**
     * 布尔选择
     *
     * @param $title
     * @return Bind
     */
    public  function boole($title)
    {
        return $this->bind($title, "boole");
    }

    /**
     * 单选
     * @param $title
     * @return Bind
     */
    public  function select($title)
    {
        return $this->bind($title, "select");
    }

    /**
     * 多选
     * @param $title
     * @return Bind
     */
    public  function multiSelect($title)
    {
        return $this->bind($title, "multiSelect");
    }

    /**
     * 搜索多选
     * @param $title
     * @return Bind
     */
    public  function searchMultipleSelect($title)
    {
        return $this->bind($title, "search_multiple_select");
    }

    /**
     * 搜索+单选
     * @param $title
     * @return Bind
     */
    public  function searchSelect($title)
    {
        return $this->bind($title, "search_select");
    }

    /**
     * 富文本
     *
     * @param $title
     * @return Bind
     */
    public  function richEditor($title)
    {
        return $this->bind($title, "rich_editor");
    }
}
