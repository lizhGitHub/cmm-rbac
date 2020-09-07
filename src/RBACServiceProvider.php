<?php
/**
 * User: lzh
 * Date: 2020/9/3
 * Time: 11:00
 */

namespace CMM\RBAC;

use CMM\RBAC\Middleware\LogMiddleware;
use CMM\RBAC\Services\UserService;
use Illuminate\Support\ServiceProvider;

class RBACServiceProvider extends ServiceProvider
{
    /**
     *
     */
    public function register()
    {
        $this->app->singleton(LogMiddleware::class);

        $this->app->singleton('rbac.user', function ($app) {
            return new UserService($app['request']);
        });

        $this->mergeConfigFrom(__DIR__ . '/config/rbac.php', 'rbac');
    }

    /**
     *
     */
    public function boot()
    {
//        $this->loadRoutesFrom(__DIR__ . '/routes.php');
    }
}
