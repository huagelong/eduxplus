<?php

/**
 * @Author: kaihui.wang
 * @Contact  hpuwang@gmail.com
 * @Version: 1.0.0
 * @Date: 2020/10/9 10:51
 */

namespace Eduxplus\CmsBundle\Service;

use Eduxplus\CmsBundle\Lib\Git\Git;
use Eduxplus\CoreBundle\Lib\Base\BaseService;

class GitService extends BaseService{
    
    public function __construct()
    {
        $gitBinPath = $this->getOption("cms.gitbin.path");
        if($gitBinPath){
            Git::setBin($gitBinPath);
        }
    }


    /**
     * clone
     *
     * @param [type] $remoteUrl git 地址
     * @param [type] $type 1-网址，2-ssh
     * @param [gitUserName] $gitUserName
     * @param [typgitPwde] $gitPwd
     * @return void
     */
    public function clone($remoteUrl, $type, $gitUserName="", $gitPwd=""){
        
    }
}
