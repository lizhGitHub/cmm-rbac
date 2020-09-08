<?php

namespace CMM\RBAC\Middleware;

use Closure;
use CMM\RBAC\Events\LogEvent;
use CMM\RBAC\Facades\User;
use CMM\RBAC\Traits\HttpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LogMiddleware
{
    use HttpRequest;

    /**
     * @var array
     */
    private $data = [];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //监听模型事件
        Event::listen(['eloquent.created: *', 'eloquent.updated: *', 'eloquent.deleted: *'], function ($event, $payload) {
            $payload = $payload[0];
            //没有意义的更新
            if (Str::is('eloquent.updated: *', $event) && !$payload->getDirty()) {
                return;
            }

            $this->data[] = [
                'desc' => $payload->desc ?: $event,
                'original' => $payload->getOriginal(),
                'changes' => $payload->getDirty(),
            ];
        });
        //自定义事件监听，查询构造器操作数据库需手动触发事件
        Event::listen(LogEvent::class, function (LogEvent $event) {
            $this->data[] = [
                'desc' => $event->desc,
                'original' => $event->original,
                'changes' => $event->changes,
            ];
        });

        return $next($request);
    }

    /**
     * 记录日志
     *
     * @param Request $request
     */
    public function terminate(Request $request)
    {
        if (!count($this->data)) {
            return;
        }

        $routeName = $request->route()[1]['desc'] ?? '';

        $route = $request->path();

        $config = config('rbac');

        $token = $request->header('token') ?? $request->cookie('token');

        $param = [
            'app_key' => $config['app_key'],
            'route_path' => $route,
            'route_name' => $routeName,
            'data' => $this->data,
        ];

        $param['sign'] = rbac_sign($param, $config['app_secret']);

        $url = $config['rbac_back_url'] . Arr::get($config, 'api.log');

        $result = [];

        try {
            $result = $this->postJson($url, $param, ['token' => $token]);

            if (!isset($result['code']) || $result['code'] != 0) {
                throw new \Exception('同步rbac日志失败，' . $result['msg'] ?? '');
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            Log::info('请求rbac返回', $result ?? []);
            //换取用户id
            $param['user_id'] = User::user()['id'] ?? 0;

            unset($param['token']);
            //rbac请求失败，本地保存
            Log::info('操作日志', $param);
        }
    }
}
