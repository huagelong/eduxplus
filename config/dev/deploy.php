<?php

use EasyCorp\Bundle\EasyDeployBundle\Deployer\DefaultDeployer;

return new class extends DefaultDeployer
{
    public function configure()
    {
        return $this->getConfigBuilder()
            // SSH connection string to connect to the remote server (format: user@host-or-IP:port-number)
            ->server('root@dev.eduxplus.com')
            // the absolute path of the remote server directory where the project is deployed
            ->deployDir('/www/wwwroot/dev.eduxplus.com')
            // the URL of the Git repository where the project code is hosted
            ->repositoryUrl('git@gitee.com:wangkaihui/eduxplus.git')
            // the repository branch to deploy
            ->repositoryBranch('master')
            ->symfonyEnvironment("dev")
            ->sharedFilesAndDirs([".env.local"])
            ->installWebAssets(false)
            ->keepReleases(2);
        ;
    }

    // run some local or remote commands before the deployment is started
    public function beforeStartingDeploy()
    {
        $this->log ( '准备应用' );
        $this->runLocal ( 'SYMFONY_ENV=dev composer deploy' );
    }

    // run some local or remote commands after the deployment is finished
    public function beforeFinishingDeploy()
    {
//        $this->runRemote('{{ console_bin }} app:my-task-name');
         $this->runLocal('say "The deployment has finished."');
    }
};
