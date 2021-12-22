<?php
/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/11/2 10:14
 */

namespace Eduxplus\WebsiteBundle\Controller;


use Eduxplus\CoreBundle\Lib\Base\BaseHtmlController;
use Eduxplus\WebsiteBundle\Service\BannerService;
use Eduxplus\WebsiteBundle\Service\NewsService;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;

class NewsController extends BaseHtmlController
{

    /**
     * @Rest\Get("/news/{category}/{page}", name="app_news")
     */
    public function indexAction($category=0, $page=1,BannerService $bannerService, NewsService $newsService){
        $page = $page?$page:1;
        $pageSize = 40;
        list($pagination, $list) = $newsService->getNewsList($category, $page, $pageSize);
        $banners = $bannerService->getBanners(1);//资讯首页banner
        $newsCategory = $newsService->getAllNewsCategory();
        $noticeNews = $newsService->getNewsByTopValue(1);//公告
        $recommendNews =  $newsService->getNewsByTopValue(2);//推荐
        $hotNews =  $newsService->getNewsByTopValue(3);//热门
        $data = [];
        $data["banners"] = $banners;
        $data["newsCategory"] = $newsCategory;
        $data["noticeNews"] = $noticeNews;
        $data["hotNews"] = $hotNews;
        $data['recommendNews'] = $recommendNews;
        $data['list'] = $list;
        $data['pagination'] = $pagination;
        $data['category'] = $category;
        $data['route'] = "app_news";
        return $this->render("@WebsiteBundle/news/index.html.twig", $data);
    }

    /**
     * 新闻详情
     * @Rest\Get("/news_detail/{id}", name="app_news_detail")
     */
    public function detailAction($id, NewsService $newsService){
        $newsService->viewNumIncre($id);
        $detail = $newsService->getById($id);
        $hotNews =  $newsService->getNewsByTopValue(3);//热门
        $data = [];
        $data['detail'] = $detail;
        $data["hotNews"] = $hotNews;
        $data['route'] = "app_news";
        return $this->render("@WebsiteBundle/news/detail.html.twig", $data);
    }
}
