<?php
/**
 * User: lzh
 * Date: 2020/9/4
 * Time: 11:11
 */

namespace CMM\RBAC\Services;

use CMM\RBAC\Traits\HttpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class UserService
{
    use HttpRequest;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var array
     */
    private $config;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var array
     */
    private $user;

    /**
     * @var array
     */
    private $userList;

    /**
     * @var array
     */
    private $roleList;

    /**
     * RbacService constructor.
     * @param Request $request
     * @param $config
     */
    public function __construct(Request $request)
    {
        $this->config = config('rbac');
        $this->baseUrl = $this->config['rbac_back_url'];
        $this->request = $request;
    }

    /**
     * 用户信息
     *
     * @return mixed
     * @throws \Exception
     */
    public function user()
    {
        if ($this->user) {
            return $this->user;
        }

        return $this->user = $this->sendRequest(Arr::get($this->config, 'api.user'))['user'];
    }

    /**
     * 角色
     *
     * @return mixed
     * @throws \Exception
     */
    public function roleList()
    {
        if ($this->roleList) {
            return $this->roleList;
        }

        return $this->roleList = $this->sendRequest(Arr::get($this->config, 'api.role_list'))['roles'];
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function users()
    {
        if ($this->userList) {
            return $this->userList;
        }

        return $this->userList = $this->sendRequest(Arr::get($this->config, 'api.user_list'))['users'];
    }

    /**
     * @param $api
     * @return mixed
     * @throws \Exception
     */
    protected function sendRequest($api)
    {
        $token = $this->request->header('token') ?? $this->request->cookie('token');

        $data = [
            'app_key' => $this->config['app_key'],
            'token' => $token,
        ];
        $data['sign'] = rbac_sign($data, $this->config['app_secret']);

        $response = $this->get($this->baseUrl . $api, $data, [
            'token' => $token,
        ]);

        if (!isset($response['code']) || $response['code'] != 0) {
            app('log')->error('rbac返回参数：', $response);
            throw new \Exception('获取用户信息异常');
        }

        return $response['data'];
    }
}
