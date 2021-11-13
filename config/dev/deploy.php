<?php

use EasyCorp\Bundle\EasyDeployBundle\Deployer\DefaultDeployer;

use EasyCorp\Bundle\EasyDeployBundle\Configuration\Option;
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
            ->symfonyEnvironment("prod")
            ->sharedFilesAndDirs(['.env', '.env.local', 'var/log', 'public'])
            ->writableDirs(['var/cache/', 'var/log/', 'public'])
            ->installWebAssets(false)
            ->keepReleases(2);
        ;
    }

    // run some local or remote commands before the deployment is started
    public function beforeStartingDeploy()
    {
//        $this->log ( '准备应用' );
    }

    public function beforePublishing()
    {
        $this->runRemote(" cp /www/wwwroot/dev.eduxplus.com/.env.local ./.env.local");
        $this->runRemote(sprintf('%s dump-env prod', $this->getConfig(Option::remoteComposerBinaryPath)));
        $this->runRemote( 'SYMFONY_ENV=dev composer deploy' );
    }

    // run some local or remote commands after the deployment is finished
    public function beforeFinishingDeploy()
    {
        $this->log('Remote Restarting servers');
        $this->runRemote('/etc/init.d/nginx restart');
        $this->runRemote('/etc/init.d/php-fpm-80 restart');
         $this->runLocal('say "The deployment has finished."');
    }
};
