<?php

namespace app\common\service;

use think\App;
use think\facade\Db;

/**
 * 下单活动的工具类
 */
class Alipay {

    public $request;
    public $pay;

    public function __construct()
    {
        $params = new \Yurun\PaySDK\AlipayApp\Params\PublicParams;
        $params->appID = '2021003108646XXX';
        $params->sign_type = 'RSA2';
        // 使用公钥证书模式
        $params->usePublicKeyCert = true;
        $params->appPrivateKey = 'MIIEowIBAAKCAQEAhc07q06MOEx5NjGSQA/q83bmhaZduuwz8ojZHNiD69OTCn0ZtH8k/F4bG5Te7aiwWEH42ijCQ1QNC+va5k23BXIzg5NrFQGgucVlNlWvsjydwhP+/KN1ykCeA/mCE3UZSS0rLs5P1vE3mhKEWAzHFQG0bBO7vb6pS1ZPYFXv/JAMabe03PIfzCn7G2l6e0HXH5NZygiIGYfdPr9saFyvabGi1u29eormfegohDQtBLaCpl2CRpkvrkSf5K5jLWxJYbaf+zJUyIsE+zh4/XsaJeKjIgAeuBk9m8A45X2pz7NLw6QRGwzNQTjqLNCfjR38hIzczN14BhcDvx75zyN7fwIDAQABAoIBAHJgscxL9hO+U7OfBo1azhbXolHHmMJMXk/K6gX9lDAJCd5ieJHSSnStoXCffz4cALtBkhAz3XSeqhRxxwsujfDK4fxtDZHEdXe4pT+gWfP8W0NnwoaG2Q9O04VESgyGAlqKkSqO1LNCOqyrfZSjP5/WeDwXVlrqXF/5ZRtN+zujTHdbpSXSWin3FTFoqCD4RiSj0udqqkbMEo8EhgjNuPypx2Rs6ViMdBdmkSLk8w98UwFqVq8I4snUxXzm0CZX5cCqUKpTPgEZ1U7TLoKUkD4PDZYIdf8+WwwsboJvaycAZYAF9HtLwJ8fMHfATLUyVICeD1KX0HkFgzWRamfRStECgYEA1HvyKme6HnNxiWqiwqTrJV1w0W8DtHoxtRS96+diHz+gOztknOA4V4xWHveH6rYriZvtQTxnSyFIjyzjs/QQCqnNT5p0tFE2UiyrqTqi8LTKTSpPaLbxSH1U7KaTEKTF7EMv0OnpY7yOpSd7ux4CKF47QKDrS+ILjOPO104xVkkCgYEAoTQllNVM6mRq22HL+tLzZcII1eeAp107WIAZtObAwAh/HpEhIBpGgpaWcciPoFwisYKcj1sM7jPxwUOZsa6IVg6eSiDeMnPeLRbwSzMSfjaYJKqPg1WH/77EX6Fi0OMKhkXCvVikGtca8o8YdA2B6fOFZaUQ3tDKzKwpWmW7I4cCgYB51eATZIqZr0QosRZGWV3jfOKVk/wh3fbmh9AmlfPzSv5LOFEGiqKDwJoZA4HRmUb5jojJ4SKxN5JtViXyJz2Tf62TuVK1meN11ttPG6oWLvPYryv9NPjAweySDSlCX9iFEjk2pwkGATYhiLQvpJNEJlP/SMXiKujIjk8psVIk0QKBgFcROD8j2dA7fm+Rab/GkknivCz0Rcufkn8haUEI5RCTRj13O4IATxcXSJiaL+D4ApEHr5HKJri9e5Tb8zZBw0dfy+TTzc9IP7bwzcaABjv/NruPF8ZiuvrJyXyxSRLzneqZ4S2to60Pg3GBEj8UUJfQvLsCpZMDCJX1Yrx9d88fAoGBAJklQX52pQqgJLEtILlWm0bwbE+ONpxxfueuX/R/0UGiqMgT2LMPHFk3J9S7kawfEbV/UyCdYR6TT9LB03O8JWQKDja9jAAA3FzUsrx6LKrhaIvkEYMe7I0JRzd3LbmS8RznQpC4QHkpzflMOp01hKg4YCHUaE1xKRBB6LDSOuAX';
        // 支付宝公钥证书文件路径
        $params->alipayCertPath =  '../tool/alipay/cert/alipayCertPublicKey_RSA2.crt';
        // 支付宝根证书文件路径
        $params->alipayRootCertPath = '../tool/alipay/cert/alipayRootCert.crt';
        // 支付宝应用公钥证书文件路径
        $params->merchantCertPath = '../tool/alipay/cert/appCertPublicKey.crt';
        $this->pay = new \Yurun\PaySDK\AlipayApp\SDK($params);
    }


    public function transfer($info)
    {

        try {
            // 支付接口
            $request = new \Yurun\PaySDK\AlipayApp\Fund\UniTransfer\Request;
            $payeeInfo = [
                'identity' => $info['num'],
                'identity_type' => 'ALIPAY_LOGON_ID',
                'name' => $info['realname']
            ];
            $ordernum = get_ordernum();
            $request->businessParams->out_biz_no = $ordernum;
            $request->businessParams->biz_scene = 'DIRECT_TRANSFER';
            $request->businessParams->product_code = 'TRANS_ACCOUNT_NO_PWD';
            $request->businessParams->trans_amount = $info['real_total'];
            $request->businessParams->payee_info = $payeeInfo;
            $result = $this->pay->execute($request);
        } catch (\Exception $e) {
            return ['status' => 0, 'info' => $e->getMessage()];
        }
        if ($this->pay->checkResult()) {
            return $result;
        } else {
            file_put_contents('Transfer-errors.txt', date('Y-m-d H:i:s') . ': ' . $info['id'] . $this->pay->getError() . "\r\n", FILE_APPEND);
            return ['status' => 0, 'info' => $this->pay->getError()];
        }
    }

}
