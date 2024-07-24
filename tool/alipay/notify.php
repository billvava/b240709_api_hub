<?php

require './alipay.class.php';
require './config.php';


/*
 * 支付宝支付异步通知返回数据示例
 * array (
  'gmt_create' => '2017-11-30 12:40:18',
  'charset' => 'UTF-8',
  'seller_email' => 'se***@163.com',
  'subject' => '测试支付',
  'sign' => '4ysyrGbKrtAN9B5bZnEcnh',
  'body' => '测试支付内容',
  'buyer_id' => '20880***15',
  'invoice_amount' => '0.01',
  'notify_id' => '2e96bc***ae9bm95',
  'fund_bill_list' => '[{"amount":"0.01","fundChannel":"ALIPAYACCOUNT"}]',
  'notify_type' => 'trade_status_sync',
  'trade_status' => 'TRADE_SUCCESS',
  'receipt_amount' => '0.01',
  'app_id' => '2017***51',
  'buyer_pay_amount' => '0.01',
  'sign_type' => 'RSA2',
  'seller_id' => '208***53',
  'gmt_payment' => '2017-11-30 12:40:19',
  'notify_time' => '2017-11-30 12:54:20',
  'passback_params' => 'goods',
  'version' => '1.0',
  'out_trade_no' => '652766227614',
  'total_amount' => '0.01',
  'trade_no' => '2017***1571',
  'auth_app_id' => '2017***51',
  'buyer_logon_id' => 'xi***@163.com',
  'point_amount' => '0.00',
  )
 */

$api = new alipayApi();
if (!$api->setConfig($config)) {
    exit($api->getErrMessage());
}
$flag = $api->verifyData($_POST);
if ($flag) {//验证通过，进行数据处理，注意，最终的支付金额和订单号必须以这里返回的数据为准！

}
echo $flag ? 'success' : 'error';
