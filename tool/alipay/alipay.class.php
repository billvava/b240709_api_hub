<?php

/*
 * 支付宝支付接口
 * 特么受够了官方的垃圾sdk！
 */

class alipayApi {

    public static $config = array();
    public $errMsg = null;
    private $gatewayUrl = 'https://openapi.alipay.com/gateway.do';
    //private $gatewayUrl='https://openapi.alipaydev.com/gateway.do';

    public function __construct() {
        
    }

    public function setConfig($config = array()) {
        $arr = array('app_id', 'sign_type');
        foreach ($arr as $val) {
            if (!isset($config[$val]) || !$config[$val]) {
                $this->halt('配置信息不完整');
                return false;
            }
        }
        $keyArr = array('notify_url', 'private_key_path', 'public_key_path', 'private_key', 'public_key', 'alipay_public_key');
        foreach ($keyArr as $val) {
            if (!isset($config[$val])) {
                $config[$val] = '';
            }
        }
        self::$config = $config;
        return $this->checkConfig();
    }

    //wap手机支付接口
    public function wapPay($objData, $pubData = array()) {
        $objData['product_code'] = 'QUICK_WAP_WAY';
        $res = $this->getAllParam($objData, 'wap', $pubData);
        if (!$res || !$res['data']) {
            $this->halt('请求信息有误');
            return false;
        }
        $res['data']['sign'] = $this->rsaSign($res['str']);
        return $this->buildRequestForm($res['data']);
    }

    //app端支付接口
    public function appPay($objData, $pubData = array()) {
        $objData['product_code'] = 'QUICK_MSECURITY_PAY';
        $res = $this->getAllParam($objData, 'app', $pubData);
        if (!$res || !$res['data']) {
            $this->halt('请求信息有误');
            return false;
        }
        $res['data']['sign'] = $this->rsaSign($res['str']);
        return http_build_query($res['data'], '', '&'); //传给支付宝接口的数据
    }

    //支付结果查询
    /*
     * 返回数据示例
     * {
      "alipay_trade_query_response": {
      "code": "10000",
      "msg": "Success",
      "trade_no": "2013***536",
      "out_trade_no": "6823789339978248",
      "buyer_logon_id": "159****5620",
      "trade_status": "TRADE_CLOSED",
      "total_amount": 88.88,
      "receipt_amount": "15.25",
      "buyer_pay_amount": 8.88,
      "point_amount": 10,
      "invoice_amount": 12.11,
      "send_pay_date": "2014-11-27 15:45:57",
      "store_id": "NJ_S_001",
      "terminal_id": "NJ_T_001",
      "fund_bill_list": [
      {
      "fund_channel": "ALIPAYACCOUNT",
      "amount": 10,
      "real_amount": 11.21
      }
      ],
      "store_name": "证大五道口店",
      "buyer_user_id": "2088101117955611",
      "buyer_user_type": "PRIVATE"
      },
      "sign": "ERITJKEIJKJHKKKKKKKHJEREEEEEEEEEEE"
      }
     */
    public function query($objData, $pubData = array()) {
        $res = $this->getAllParam($objData, 'query', $pubData);
        if (!$res || !$res['data']) {
            $this->halt('请求信息有误');
            return false;
        }
        $res['data']['sign'] = $this->rsaSign($res['str']);
        $rs = $this->getHttp($this->gatewayUrl . '?' . http_build_query($res['data'], '', '&'));
        if ($rs) {
            $data = json_decode($rs, true);
        } else {
            $data = array();
        }
        if ($data && isset($data['alipay_trade_query_response'])) {
            $result = $data['alipay_trade_query_response'];
            if ($result['code'] == 10000) {
                if ($result['trade_status'] == 'TRADE_SUCCESS') {
                    $result['pay_status'] = 1;
                } else {
                    $result['pay_status'] = 0;
                }
                return array('status' => 1, 'info' => 'ok', 'data' => $result);
            } else {
                return array('status' => 0, 'info' => $result['sub_msg'], 'data' => array());
            }
        }
        return array('status' => 0, 'info' => '返回数据失败' . $rs, 'data' => array());
    }

    //申请退款
    /*
     * 返回数据示例
     * {
      "alipay_trade_refund_response": {
      "code": "10000",
      "msg": "Success",
      "trade_no": "支付宝交易号",
      "out_trade_no": "6823789339978248",
      "buyer_logon_id": "159****5620",
      "fund_change": "Y",
      "refund_fee": 88.88,
      "gmt_refund_pay": "2014-11-27 15:45:57",
      "refund_detail_item_list": [
      {
      "fund_channel": "ALIPAYACCOUNT",
      "amount": 10,
      "real_amount": 11.21,
      "fund_type": "DEBIT_CARD"
      }
      ],
      "store_name": "望湘园联洋店",
      "buyer_user_id": "2088101117955611"
      },
      "sign": "ERITJKEIJKJHKKKKKKKHJEREEEEEEEEEEE"
      }
     */
    public function refund($objData, $pubData = array()) {
        $res = $this->getAllParam($objData, 'refund', $pubData);
        if (!$res || !$res['data']) {
            $this->halt('请求信息有误');
            return false;
        }
        $res['data']['sign'] = $this->rsaSign($res['str']);
        $rs = $this->getHttp($this->gatewayUrl . '?' . http_build_query($res['data'], '', '&'));
        if ($rs) {
            $data = json_decode($rs, true);
        } else {
            $data = array();
        }
        if ($data && isset($data['alipay_trade_refund_response'])) {
            $result = $data['alipay_trade_refund_response'];
            if ($result['code'] == 10000) {
                return array('status' => 1, 'info' => 'ok', 'data' => $result);
            } else {
                return array('status' => 0, 'info' => $result['sub_msg'], 'data' => array());
            }
        }
        return array('status' => 0, 'info' => '返回数据失败' . $rs, 'data' => array());
    }

    //申请退款进度查询
    /*
     * 返回数据示例
     * {
      "alipay_trade_fastpay_refund_query_response": {
      "code": "10000",
      "msg": "Success",
      "trade_no": "2014112611001004680073956707",
      "out_trade_no": "20150320010101001",
      "out_request_no": "20150320010101001",
      "refund_reason": "用户退款请求",
      "total_amount": 100.2,
      "refund_amount": 12.33
      },
      "sign": "ERITJKEIJKJHKKKKKKKHJEREEEEEEEEEEE"
      }
     */
    public function refundQuery($objData, $pubData = array()) {
        $res = $this->getAllParam($objData, 'refundQuery', $pubData);
        if (!$res || !$res['data']) {
            $this->halt('请求信息有误');
            return false;
        }
        $res['data']['sign'] = $this->rsaSign($res['str']);
        $rs = $this->getHttp($this->gatewayUrl . '?' . http_build_query($res['data'], '', '&'));
        if ($rs) {
            $data = json_decode($rs, true);
        } else {
            $data = array();
        }
        if ($data && isset($data['alipay_trade_fastpay_refund_query_response'])) {
            $result = $data['alipay_trade_fastpay_refund_query_response'];
            if ($result['code'] == 10000) {
                return array('status' => 1, 'info' => 'ok', 'data' => $result);
            } else {
                return array('status' => 0, 'info' => $result['sub_msg'], 'data' => array());
            }
        }
        return array('status' => 0, 'info' => '返回数据失败' . $rs, 'data' => array());
    }

    /**
     * 异步通知RSA验证签名
     * @param $data 待签名数据
     * @param $ali_public_key_path 支付宝的公钥文件路径
     * @param $sign 要校对的的签名结果
     * return 验证结果
     */
    public function verifyData($data) {
        if (!$data || !isset($data['sign']) || !isset($data['sign_type'])) {
            $this->halt('请求信息不完整');
            return false;
        }
        $sign = $data['sign'];
        $data['sign'] = null;
        $data['sign_type'] = null;
        $checkParam = $this->checkParam($data);
        if (self::$config['alipay_public_key_path']) {
            $pubKey = file_get_contents(self::$config['alipay_public_key_path']);
        } else {
            $pubKey = "-----BEGIN PUBLIC KEY-----\n" .
                    wordwrap(self::$config['alipay_public_key'], 64, "\n", true) .
                    "\n-----END PUBLIC KEY-----";
        }
        $res = openssl_get_publickey($pubKey);
        $result = (bool) openssl_verify($checkParam['str'], base64_decode($sign), $res, OPENSSL_ALGO_SHA256);
        openssl_free_key($res);
        return $result;
    }

    public function getErrMessage() {
        return $this->errMsg? : '';
    }

    private function halt($msg) {
        $this->errMsg = $msg;
    }

    private function getAllParam($data, $mod = 'wap', $pubData = array()) {
        $objData = array();
        $objData['app_id'] = self::$config['app_id'];
        $objData['biz_content'] = self::json_encode($data);
        $objData['charset'] = 'UTF-8';
        $objData['format'] = 'json';
        switch ($mod) {
            case 'wap':
                $objData['method'] = 'alipay.trade.wap.pay';
                break;
            case 'app':
                $objData['method'] = 'alipay.trade.app.pay';
                break;
            case 'query':
                $objData['method'] = 'alipay.trade.query';
                break;
            case 'refund':
                $objData['method'] = 'alipay.trade.refund';
                break;
            case 'refundQuery':
                $objData['method'] = 'alipay.trade.fastpay.refund.query';
                break;
            default :
                $this->halt('请设置请求模块');
                return false;
        }
        $objData['notify_url'] = self::$config['notify_url'];
        $objData['sign_type'] = self::$config['sign_type'];
        $objData['timestamp'] = date('Y-m-d H:i:s', time());
        $objData['version'] = '1.0';
        $objData['alipay_sdk'] = 'alipay-sdk-php-20161101';
        if ($pubData) {
            $objData = array_merge($objData, $pubData);
        }
        return $this->checkParam($objData);
    }

    private function checkParam($objData) {
        if (!$objData || !is_array($objData)) {
            return array('data' => array(), 'str' => '');
        }
        ksort($objData);
        $arr = array();
        foreach ($objData as $key => $val) {
            if ($val) {
                $arr[] = $key . '=' . urldecode($val);
            } else {
                unset($objData[$key]);
            }
        }
        return array('data' => $objData, 'str' => implode('&', $arr));
    }

    /**
     * 建立请求，以表单HTML形式构造（默认）
     * @param $para 请求参数数组
     * @return 提交表单HTML文本
     */
    private function buildRequestForm($para) {
        $sHtml = '<form id="alipaysubmit" name="alipaysubmit" action="' . $this->gatewayUrl . '?charset=UTF-8" method="POST">';
        foreach ($para as $key => $val) {
            $val = str_replace("'", "&apos;", $val);
            $sHtml.= '<input type="hidden" name="' . $key . '" value=\'' . $val . '\'/>';
        }
        //submit按钮控件请不要含有name属性
        $sHtml .='<input type="submit" value="ok" style="display:none;"></form>';
        $sHtml .='<script>document.forms["alipaysubmit"].submit();</script>';
        return $sHtml;
    }

    /**
     * RSA签名
     * @param $data 待签名数据
     * @param $private_key_path 商户私钥文件路径
     * return 签名结果
     */
    private function rsaSign($data) {
        if (self::$config['public_key_path']) {
            $priKey = file_get_contents(self::$config['private_key_path']);
        } else {
            $priKey = "-----BEGIN RSA PRIVATE KEY-----\n" .
                    wordwrap(self::$config['private_key'], 64, "\n", true) .
                    "\n-----END RSA PRIVATE KEY-----";
        }
        $res = openssl_get_privatekey($priKey);
        $sign = '';
        openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
        openssl_free_key($res);
        return base64_encode($sign);
    }

    /**
     * 中文转义的json结构
     * @param array $arr
     */
    static public function json_encode($arr) {
        return json_encode($arr, JSON_UNESCAPED_UNICODE);
    }

    private function checkConfig() {
        if (!self::$config['app_id']) {
            return false;
        }
        if ((!self::$config['private_key_path'] || !self::$config['public_key_path']) && (!self::$config['private_key'] || !self::$config['public_key']) || (!self::$config['alipay_public_key'])) {
            $this->halt('配置信息不完整(密钥)');
            return false;
        }
        return true;
    }

    /**
     * 快捷 HTTP 请求，支持简单GET和POST请求
     * @param $url string 请求地址
     * @param $data array POST请求数据
     * @param $opt 自定义参数
     * $headers = array(
      'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9) Gecko/2008052906 Firefox/3.0',
      'Referer: http://www.163.com',
      'Host:',
      'Origin:',
      'X-Requested-With:XMLHttpRequest'
      );
     * @return string content
     */
    private function getHttp($url, $data = array(), $opt = array()) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        if (stripos($url, 'https://') !== FALSE) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        }
        if ($opt && !is_array($opt)) {
            curl_setopt($curl, CURLOPT_REFERER, $opt);
            curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36 MicroMessenger/6.5.2.501 NetType/WIFI WindowsWechat'); // 模拟用户使用的浏览器
        }
        if ($opt && is_array($opt)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $opt);
        }
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        if ($data) {
            if (is_array($data)) {
                $p = http_build_query($data, '', '&');
            } else {
                $p = $data;
            }
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $p);
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($curl);
        if (curl_errno($curl)) {
            $this->halt('Curl Error:url:' . $url . ' ,info:' . curl_error($curl));
        }
        curl_close($curl);
        return $tmpInfo;
    }

}
