<?php
/**
 * User: lzh
 * Date: 2020/9/3
 * Time: 11:09
 */

Route::group(['prefix' => '/rbac', 'namespace' => 'CMM\RBAC\Controllers'], function ($router) {
    $router->get('/test', 'RBACController@test');
});
