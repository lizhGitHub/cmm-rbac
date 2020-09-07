<?php
/**
 * User: lzh
 * Date: 2020/9/3
 * Time: 20:16
 */

if (!function_exists('rbac_sign')) {
    /**
     * rbac签名
     *
     * @param array $data
     * @param $secret
     * @return string
     */
    function rbac_sign(array $data, $secret)
    {
        ksort($data);

        $str = '';

        foreach ($data as $key => $value) {
            $str .= $key . '=' . (is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value) . '&';
        }

        $str = md5($str . $secret);

        return $str;
    }
}
