<?php


namespace CMM\RBAC\Traits;


use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

trait HttpRequest
{
    /**
     * @param array $options
     * @return Client
     */
    protected function getHttpClient(array $options = [])
    {
        return new Client($options);
    }

    /**
     * @return array
     */
    protected function getBaseOptions()
    {
        return [
            'timeout' => method_exists($this, 'getTimeOut') ? $this->getTimeOut() : 5.0,
        ];
    }

    /**
     * @param $method
     * @param $uri
     * @param array $options
     * @return mixed|string
     */
    protected function request($method, $uri, $options = [])
    {
        return $this->unwrapResponse($this->getHttpClient($this->getBaseOptions())->{$method}($uri, $options));
    }

    /**
     * @param ResponseInterface $response
     * @return mixed|string
     */
    protected function unwrapResponse(ResponseInterface $response)
    {
        $contents = $response->getBody()->getContents();

        return json_decode($contents, true);
    }

    /**
     * @param $uri
     * @param array $query
     * @param array $headers
     * @return mixed|string
     */
    protected function get($uri, $query = [], $headers = [])
    {
        return $this->request('get', $uri, [
            'headers' => $headers,
            'query' => $query,
        ]);
    }

    /**
     * @param $uri
     * @param array $params
     * @param array $headers
     * @return mixed|string
     */
    protected function post($uri, $params = [], $headers = [])
    {
        return $this->request('post', $uri, [
            'headers' => $headers,
            'form_params' => $params,
        ]);
    }

    /**
     * @param $uri
     * @param array $params
     * @param array $headers
     * @return mixed|string
     */
    protected function postJson($uri, $params = [], $headers = [])
    {
        return $this->request('post', $uri, [
            'headers' => $headers,
            'json' => $params,
        ]);
    }
}
