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
    function request_curl($url, array $params = [], $ispost = false, $https = false)
    {
        $USERAGENT =
            'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/' .
            '537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36';
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
if (!function_exists('array_sort')) {
    function array_sort($arr, $keys, $type = 'asc')
    {
        $keysvalue = $new_array = array();
        foreach ($arr as $k => $v) {
            $keysvalue[$k] = $v[$keys];
        }
        if ($type == 'asc') {
            asort($keysvalue);
        } else {
            arsort($keysvalue);
        }
        reset($keysvalue);
        foreach ($keysvalue as $k => $v) {
            $new_array[$k] = $arr[$k];
        }
        return $new_array;
    }
}


if (!function_exists('get_table_rows')) {
    /**
     * @param string $url 地址
     * @param string $scope 存储数据的账户名称
     * @param string $code 提供该表的智能合约名称
     * @param string $table 要查询的表名
     * @param int $limit 限制返回的结果数(可选)
     * @param string $lower_bound
     * @return bool|string
     */
    function get_table_rows($url,$scope, $code, $table, $limit,$lower_bound)
    {
        if ($url == null || $scope == null || $code == null || $table == null) {
            return false;
        }
        $paramArr = [
            "scope" => $scope,
            "code" => $code,
            "table" => $table,
            "json" => true
        ];
        if ($limit != null) {
            $paramArr['limit'] = $limit;
        }
        if ($lower_bound != null){
            $paramArr['lower_bound'] = $lower_bound;
        }
        $post_str = json_encode($paramArr);
        $init = curl_init();
        curl_setopt($init, CURLOPT_URL, $url . '/v1/chain/get_table_rows');//set url
        curl_setopt($init, CURLOPT_HTTPHEADER, [0 => 'Content-Type: application/json']);
        curl_setopt($init, CURLOPT_USERAGENT,
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36");
        curl_setopt($init, CURLOPT_POSTFIELDS, $post_str);
        curl_setopt($init, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($init, CURLOPT_ENCODING, "");
        curl_setopt($init, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($init, CURLOPT_AUTOREFERER, true);
        curl_setopt($init, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($init, CURLOPT_MAXREDIRS, 10);
        $output = curl_exec($init);
        return $output;
    }
}
