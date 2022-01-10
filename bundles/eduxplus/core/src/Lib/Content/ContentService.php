<?php

namespace Eduxplus\CoreBundle\Lib\Content;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class ContentService
{
    protected $title;
    protected $breadcrumb=[];
    protected $body;
    protected $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }


    /**
     * @param mixed $title
     */
    public function title($title)
    {
        $this->title = $title;
        return $this;
    }

    public function breadcrumb($name, $path)
    {
        array_push($this->breadcrumb, ["name"=>$name, "path"=>$path]);
        return $this;
    }


    /**
     * @param mixed $body
     */
    protected function body($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @param int $type  1-list,2-add,3-edit,4-view
     */
    public function render($type=1){
        $data = [];
        $data["body"] = $this->body;
        $data["breadcrumb"] = $this->breadcrumb;
        $data["title"] = $this->title;
        $tpl = "list";
        if($type ==1){
            $tpl = "list";
        }else if($type ==2){
            $tpl = "add";
        }else if($type ==3){
            $tpl = "edit";
        }else if($type ==4){
            $tpl = "view";
        }
        $content = $this->twig->render("@Content/".$tpl.".html.twig", $data);
        $response = new Response();
        $response->setContent($content);
        return $response;
    }

    public function renderList($body=""){
        if($body) $this->body($body);
        return $this->render(1);
    }

    public function renderAdd($body=""){
        if($body) $this->body($body);
        return $this->render(2);
    }

    public function renderEdit($body=""){
        if($body) $this->body($body);
        return $this->render(3);
    }

    public function renderView($body=""){
        if($body) $this->body($body);
        return $this->render(4);
    }

}
