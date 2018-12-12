<?php
/**
 * 自定义助手文件
 * User: dailipeng <1822185693@qq.com>
 * Date: 2018/11/8
 * Time: 10:07
 */


if (!function_exists('request_curl')) {
    /**
     * 拉取页面
     *
     * @param String $url 请求地址
     * @param array $params 请求参数
     * @param bool $ispost 请求方式
     * @param bool $https https协议
     * @return String
     */
    function request_curl( $url, array $params = [],  $ispost = false,  $https = false)
    {
            $USERAGENT=
            'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/' .
            '537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36'
        ;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt(
            $ch,
            CURLOPT_USERAGENT,
            $USERAGENT
        );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($https) {
            // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            // 从证书中检查SSL加密算法是否存在
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        if ($ispost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ($params) {
                if (is_array($params)) {
                    $params = http_build_query($params);
                }
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }

        $response = curl_exec($ch);

        if ($response === false) {
            echo "cURL Error: " . curl_error($ch);
            curl_close($ch);
            return '';
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (404 == $httpCode) {
            curl_close($ch);
            return '';
        }
        curl_close($ch);
        return $response;
    }
}
