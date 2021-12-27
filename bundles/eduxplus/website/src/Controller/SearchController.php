<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/11/2 17:46
 */

namespace Eduxplus\WebsiteBundle\Controller;


use Eduxplus\CoreBundle\Lib\Base\BaseHtmlController;
use Eduxplus\WebsiteBundle\Service\SearchService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends BaseHtmlController
{

    /**
     * type 1 课程，2-资讯
     */
    public function indexAction($type=1, $page=1, Request $request, SearchService $searchService){
        $kw = $request->get("kw");
        $adapter = $searchService->getOption("app.search.adapter");
        $page = $page?$page:1;
        $pageSize = 40;
        $data = [];
        $data["type"] = $type;
        $data["kw"] = $kw;
        $data["adapter"] = $adapter;
        if($adapter == 1){
            list($pagination, $list) =$searchService->getList($kw, $type, $page, $pageSize);
            $data['list'] = $list;
            $data['pagination'] = $pagination;
        }else{
            $scrollId = $request->get("scrollId");
            list($totalPage, $list, $scrollId, $highlights) = $searchService->getEsList($kw, $type, $scrollId, $pageSize);
            $data['list'] = $list;
            $data['pageCount'] = $totalPage;
            $data['route'] = "app_search";
            $data['params'] = ["type"=>$type, "page"=>$page];
            $data['highlights'] = $highlights;
            $data['query'] = "?kw=".$kw."&scrollId=".$scrollId;
        }
        return $this->render('@WebsiteBundle/search/index.html.twig', $data);
    }
}
