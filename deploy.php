<?php
namespace Deployer;

require 'recipe/common.php';

// Project name
set('application', 'eduxplus');

// Project repository
set('repository', 'git@github.com:trensy/eduxplus.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', ['var', 'var/log', 'var/cache']);
set('allow_anonymous_stats', false);

set('composer_options', 'install --verbose --prefer-dist --no-progress --no-interaction --optimize-autoloader --no-suggest');

set('env', function () {
    return [
        'SYMFONY_ENV' => "dev"
    ];
});

// Hosts

localhost()
    ->set('deploy_path', '/opt/nginx/html/{{application}}');

// Tasks
task('app:install', ' (mkdir -p var && chmod 0777 -R var) && (rm -rf .env) && (cp .env.test .env)  && (/usr/bin/php ./bin/console app:install all)');

desc('start ... ');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'deploy:vendors',
    'deploy:clear_paths',
    "app:install",
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success'
]);

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

