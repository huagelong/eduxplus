<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/3/26 14:07
 */

namespace Eduxplus\WebsiteBundle\Controller;

use Eduxplus\CoreBundle\Lib\Base\BaseHtmlController;
use Eduxplus\WebsiteBundle\Service\BannerService;
use Eduxplus\WebsiteBundle\Service\CategoryService;
use Eduxplus\WebsiteBundle\Service\GoodsService;
use Eduxplus\WebsiteBundle\Service\NewsService;
use Eduxplus\WebsiteBundle\Service\PageService;
use FOS\RestBundle\Controller\Annotations as Rest;

class IndexController extends BaseHtmlController
{

    /**
     * @Rest\Get("/", name="app_index")
     */
    public function indexAction(CategoryService $categoryService, GoodsService $goodsService, BannerService $bannerService, NewsService $newsService)
    {
        $homeCategory = $categoryService->getHomeCategory();
        $topValue = $goodsService->getGoodsByTopValue(8);//热门
        $recommendValue = $goodsService->getGoodsByRecommendValue(4);//推荐
        $banners = $bannerService->getBanners(0);//首页banner
        $news = $newsService->getTopNews(9);
        $live = $goodsService->getRecentlyLiveCourse(2);
        $data = [];
        $data['homeCategory'] = $homeCategory;
        $data['topValue'] = $topValue;
        $data['recommendValue'] = $recommendValue;
        $data['banners'] = $banners;
        $data['news'] = $news;
        $data['live'] = $live;

        $data['copyright'] = $this->getOption("app.copyright");
        $data['beian'] = $this->getOption("app.beian.number");

        $data['seoDescr'] = $this->getOption("app.seo.homepage.descr");
        $data['seoKw'] = $this->getOption("app.seo.homepage.keyword");
        $data['route'] = "app_index";

        return $this->render('@WebsiteBundle/index/index.html.twig', $data);
    }

    /**
     * @Rest\Get("/page/{id}", name="app_index_page")
     */
    public function pageAction($id, PageService $pageService){
        $page = $pageService->getById($id);
        $data = [];
        $data["page"] = $page;
        return $this->render('@WebsiteBundle/index/page.html.twig', $data);
    }

    public function footerAction(){
        $data = [];
        $data['copyright'] = $this->getOption("app.copyright");
        $data['beian'] = $this->getOption("app.beian.number");

        return $this->render('@WebsiteBundle/index/footer.html.twig', $data);
    }
}
