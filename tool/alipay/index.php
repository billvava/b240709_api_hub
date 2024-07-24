<?php

require './alipay.class.php';
require './config.php';

class payAction {

    public $api = null;
    private $homeUrl = null;

    function __construct($config) {
        $this->homeUrl = $this->getPageURL(true);
        $this->api = new alipayApi();
        if (!$this->api->setConfig($config)) {
            $this->JsonReturn($this->api->getErrMessage());
        }
    }

    //手机支付
    public function wap() {
        $objData = array(
            'subject' => '测试支付',
            'body' => '测试支付内容',
            'out_trade_no' => time(),
            'timeout_express' => '1h', //取值范围：1m～15d。m-分钟，h-小时，d-天，1c-当天
            'total_amount' => '0.01', //单位：元
            'passback_params' => 'goods', //支付扩展参数，原样返回
            'quit_url' => '', //h5支付收银台会出现返回按钮，可用于用户付款中途退出并返回到该参数指定的商户网站地址
        );
        $pubData = array(
            'notify_url' => $this->homeUrl . '/notify.php', //异步通知url
            'return_url' => $this->homeUrl . '/index.php?mod=payok', //支付完成返回链接
        );
        $res = $this->api->wapPay($objData, $pubData);
        if ($res) {
            echo $res;
        } else {
            echo $this->api->getErrMessage();
        }
    }

    //wap支付成功回跳页面
    /*
     * 返回数据示例
     * array (
      'total_amount' => '0.01',
      'timestamp' => '2017-11-30 12:45:14',
      'sign' => 'mxQyy6b29Ay0b4Y8G+',
      'trade_no' => '2017***774',
      'sign_type' => 'RSA2',
      'auth_app_id' => '2017***51',
      'charset' => 'UTF-8',
      'seller_id' => '2088***53',
      'method' => 'alipay.trade.wap.pay.return',
      'app_id' => '2017***51',
      'out_trade_no' => '491955948616',
      'version' => '1.0'
      );
     */
    public function payok() {
        $objData = $_GET;
        $flag = $this->api->verifyData($objData);
        if ($flag) {//验证通过，进行数据处理，注意，最终的支付金额和订单号必须以这里返回的数据为准！
        }
        echo $flag ? '支付成功' : '支付失败';
    }

    //app支付
    public function app() {
        $objData = array(
            'body' => '测试支付内容',
            'subject' => '测试支付',
            'out_trade_no' => time(),
            'timeout_express' => '1h', //取值范围：1m～15d。m-分钟，h-小时，d-天，1c-当天
            'total_amount' => '0.01',
            'passback_params' => 'goods', //支付扩展参数，原样返回
        );
        $pubData = array(
            'notify_url' => $this->homeUrl . '/notify.php', //异步通知url
        );
        $res = $this->api->appPay($objData, $pubData);
        if ($res) {
            $this->JsonReturn('ok', $res, 1);
        } else {
            $this->JsonReturn($this->api->getErrMessage());
        }
    }

    //支付结果查询
    /*
     * 已对查询结果进行了检查，新增一个pay_status参数，1：支付成功，0:其他情况
     * $res['data'] 数据结构示例
     * Array
      (
      [code] => 10000
      [msg] => Success
      [buyer_logon_id] => xi***@163.com
      [buyer_pay_amount] => 0.00
      [buyer_user_id] => 2088***15
      [invoice_amount] => 0.00
      [out_trade_no] => 491955948616
      [point_amount] => 0.00
      [receipt_amount] => 0.00
      [send_pay_date] => 2017-11-30 12:45:13
      [total_amount] => 0.01
      [trade_no] => 2017***774
      [trade_status] => TRADE_CLOSED
      [pay_status] => 0
      )
     */
    public function query() {
        $objData = array(
            'out_trade_no' => '491955948616'
        );
        $res = $this->api->query($objData);
        if (!$res) {
            $this->JsonReturn($this->api->getErrMessage());
        } elseif ($res['status'] == 1) {
            $this->JsonReturn('ok', $res['data'], 1);
        } else {
            $this->JsonReturn($res['info']);
        }
    }

    //发起退款
    /*
     * 返回数据示例
     * 
      Array
      (
      [code] => 10000
      [msg] => Success
      [buyer_logon_id] => xia***@163.com
      [buyer_user_id] => 2088*****15
      [fund_change] => N
      [gmt_refund_pay] => 2017-11-30 14:55:05
      [out_trade_no] => 491955948616
      [refund_fee] => 0.01
      [send_back_fee] => 0.00
      [trade_no] => 2017*****774
      )
     */
    public function refund() {
        $objData = array(
            'out_trade_no' => '491955948616',
            'refund_amount' => '0.01', //申请退款金额，单位：元
            'refund_reason' => '不想买了', //可选，退款理由
            'out_request_no' => '', //可选，标识一次退款请求，同一笔交易多次退款需要保证唯一，如需部分退款，则此参数必传
            'operator_id' => '', //可选，商户的操作员编号
            'store_id' => '', //可选，商户的门店编号
            'terminal_id' => '', //可选，商户的终端编号
        );
        $res = $this->api->refund($objData);
        if (!$res) {
            $this->JsonReturn($this->api->getErrMessage());
        } elseif ($res['status'] == 1) {
            $this->JsonReturn('ok', $res['data'], 1);
        } else {
            $this->JsonReturn($res['info']);
        }
    }

    //查询退款申请
    /*
     * 返回数据示例
      Array
      (
      [code] => 10000
      [msg] => Success
      [out_request_no] => 491955948616
      [out_trade_no] => 491955948616
      [refund_amount] => 0.01
      [total_amount] => 0.01
      [trade_no] => 2017113021001004810500431774
      )
     */
    public function refund_query() {
        $objData = array(
            'out_trade_no' => '491955948616',
            'out_request_no' => '491955948616', //必填，请求退款接口时，传入的退款请求号，如果在退款请求时未传入，则该值为创建交易时的外部交易号
        );
        $res = $this->api->refundQuery($objData);
        if (!$res) {
            $this->JsonReturn($this->api->getErrMessage());
        } elseif ($res['status'] == 1) {
            $this->JsonReturn('ok', $res['data'], 1);
        } else {
            $this->JsonReturn($res['info']);
        }
    }

    public function JsonReturn($info = '', $data = null, $status = 0) {
        $objData = array('status' => $status, 'info' => $info, 'data' => $data);
        if (version_compare(PHP_VERSION, '5.4.0', '>')) {
            exit(json_encode($objData, JSON_UNESCAPED_UNICODE)); //必须PHP5.4+
        } else {
            exit(json_encode($objData));
        }
    }

    public function getPageURL($main = false) {
        $pageURL = 'http';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $pageURL .= 's';
        }
        $pageURL .= '://';
        if ($_SERVER['SERVER_PORT'] != '80') {
            $pageURL .= $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        } else {
            $pageURL .= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        }
        if ($main) {
            $pageURL = dirname($pageURL);
        }
        return $pageURL;
    }

}

$mod = isset($_GET['mod']) ? trim($_GET['mod']) : 'wap';
$payAction = new payAction($config);
if ($mod) {
    switch ($mod) {
        case 'wap':
            $payAction->wap();
            break;
        case 'app':
            $payAction->app();
            break;
        case 'payok':
            $payAction->payok();
            break;
        case 'query':
            $payAction->query();
            break;
        case 'refund':
            $payAction->refund();
            break;
        case 'refund_query':
            $payAction->refund_query();
            break;
        default :
            $payAction->JsonReturn('模块名不正确');
    }
} else {
    $payAction->JsonReturn('模块名不正确');
}