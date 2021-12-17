<?php

namespace Eduxplus\CoreBundle\Command;

use Eduxplus\CoreBundle\Lib\Service\EsService;
use Eduxplus\CoreBundle\Lib\Service\RedisService;
use Eduxplus\CoreBundle\Kernel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class EsCommand extends Command
{
    protected static $defaultName = 'app:es';
    protected $esService;
    protected $redisService;

    public function __construct(EsService $esService, RedisService $redisService)
    {
        $this->esService = $esService;
        $this->redisService = $redisService;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('elasticsearch 重新索引');
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if(!$this->esService->existIndex("goods")){
//            $this->esService->delindex("goods");
            $this->esService->esCreateIndex("goods", "name", 1);
        }

        if(!$this->esService->existIndex("news")){
//            $this->esService->delindex("news");
            $this->esService->esCreateIndex("news", "title", 1);
        }

//        $this->esService->esCreateIndex("goods", "name", 1);
//        $this->esService->esCreateIndex("news", "title", 1);

        //更新数据
        $redisKey = "es_goods_rank";
        $lastId= (int) $this->redisService->get($redisKey);
//        $lastId = 0;
        while(1) {
            $sql = "SELECT a FROM App:MallGoods a WHERE a.status=1 AND a.id >:id";
            $data = $this->esService->fetchAll($sql, ["id"=>$lastId]);
            if (!$data) break;
            foreach ($data as $v){
                echo "good:".$v['id']."\r\n";
                $this->esService->esUpdate("goods", $v['id'], ["name"=>$v['name']]);
                $lastId = $v['id'];
                $this->redisService->setex($redisKey, 3600*24*2,  $v['id']);
            }
        }

        $redisKey = "es_news_rank";
        $lastId= (int) $this->redisService->get($redisKey);
//        $lastId = 0;
        while(1) {
            $sql = "SELECT a FROM App:MallNews a WHERE a.status=1 AND a.id >:id";
            $data = $this->esService->fetchAll($sql, ["id"=>$lastId]);
            if (!$data) break;
            foreach ($data as $v){
                echo "news:".$v['id']."\r\n";
                $this->esService->esUpdate("news", $v['id'], ["title"=>$v['title']]);
                $lastId = $v['id'];
                $this->redisService->setex($redisKey, 3600*24*2,  $v['id']);
            }
        }

        $io->success('import - success!');

        return 0;
    }
}
