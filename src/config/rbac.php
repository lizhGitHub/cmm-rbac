<?php

return [
    //避免和系统变量冲突，添加RBAC_前缀
    //权限控制中心前端域名
    'rbac_front_url' => env('RBAC_FRONT_URL', ''),
    //权限控制中心后端域名
    'rbac_back_url' => env('RBAC_BACK_URL', ''),

    'app_key' => env('RBAC_APP_KEY', ''),    //应用标识
    'app_secret' => env('RBAC_APP_SECRET', ''),

    'api' => [
        //日志
        'log' => env('RBAC_API_LOG', '/api/log/save'),
        //用户信息
        'user' => env('RBAC_API_USER', '/api/app/user/info'),
        //用户列表
        'user_list' => env('RBAC_API_USER_LIST', '/api/app/users'),
        //角色列表
        'role_list' => env('RBAC_API_ROLE_LIST', '/api/app/roles'),
    ]
];
