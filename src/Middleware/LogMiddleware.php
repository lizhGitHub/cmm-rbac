<?php

namespace CMM\RBAC\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class LogMiddleware
{
    /**
     * 模型名称
     *
     * @var string
     */
    private $name;

    /**
     * 原值
     *
     * @var array
     */
    private $original = [];

    /**
     * 改变后的值
     *
     * @var array
     */
    private $changes = [];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Event::listen(['eloquent.created: *', 'eloquent.updated: *', 'eloquent.deleted: *'], function ($event, $model) {
            $model = $model[0];

            $this->name = $model->desc ?: $event;
            $this->original = $model->getOriginal();
            $this->changes = $model->getDirty();
        });

        return $next($request);
    }

    /**
     * 记录日志
     *
     * @param Request $request
     */
    public function terminate($request)
    {
        Log::info( '请求路径：' . $request->path());
        Log::info( '模型：' . $this->name);
        Log::info( '原始值：', Arr::only($this->original, array_keys($this->changes)));
        Log::info( '更新值：', $this->changes);
    }
}
