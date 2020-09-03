<?php
/**
 * User: lzh
 * Date: 2020/9/3
 * Time: 11:00
 */

namespace CMM\RBAC;

use CMM\RBAC\Middleware\LogMiddleware;
use Illuminate\Support\ServiceProvider;

class RBACServiceProvider extends ServiceProvider
{
    /**
     *
     */
    public function register()
    {
        $this->app->singleton(LogMiddleware::class);
    }

    /**
     *
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
    }
}
