<?php

declare (strict_types = 1);

namespace app\common\model;

use think\facade\Db;

class Weixin {

    /**
     * 微信处理类
     * @var type
     */
    protected $weixin;
    protected $tpl_id;

    public function __construct() {
        if (!class_exists('weixin')) {
            include INCLUDE_PATH . 'weixin/weixin.class.php';
        }
        $this->weixin = new \weixin(C('appid'), C('apppwd'));
        /*
          消费业
          {{first.DATA}}
          提醒内容：{{keyword1.DATA}}
          提醒时间：{{keyword2.DATA}}
          {{remark.DATA}} */
        $this->tpl_id = C('order_to_weixin_tplid');
    }

    /*
     * 发送模板消息
     */

    public function sendTplMsg($openid, $data = array(), $url = '') {
//        $data = array(
//            'first' => '标题',
//            'keyword1' => '提醒内容',
//            'keyword2' => '提醒时间',
//            'remark' => '备注',
//        );
        $temp = array();
        foreach ($data as $k => $v) {
            $v = $v ? $v.'' : '';
            $temp[$k] = array('value' => urlencode($v), 'color' => "#743A3A");
        }
        if ($url && !is_contain_http($url)) {
            $url = C('siteurl') . $url;
        }
        return $this->weixin->doSend($openid, $this->tpl_id, $url, $temp);
    }

    /**
     * 返现通知
     * @param type $uid
     * @param type $total
     */
    public function sendGetMoeny($uid, $total) {
        if (!$uid || !$total) {
            return false;
        }

        $openid = Db::name('user')->where('id', $uid)->value('openid');
        if (!$openid) {
            return false;
        }
        $wapurl = C('wapurl') . '/sell';
        $msg = "您有新的一笔订单佣金\n";
        $msg .= "<a href='{$wapurl}'>订单佣金：{$total}元</a>";
        $this->sendTxt($openid, $msg);
    }

    /**
     * 发送模板消息
     * @param type $openid
     * @param type $data
     */
    public function sendTpl($openid, $data) {
        $template_id = C('order_to_weixin_tplid');
        $url = C("wapurl") . '/sell';
        $this->weixin->doSend($openid, $template_id, $url, $data);
    }

    /**
     * 发送文本消息
     * @param type $openid
     * @param type $msgtxt
     */
    public function sendTxt($openid, $msgtxt) {
        return $this->weixin->sendtxtmsg($openid, $msgtxt);
    }

    /**
     * 发送模板消息
     * $templateInfo = array(
     *  'first'=>'111',
     *  'k1'=>'000',
     * )
     * @return [type] [description]
     */
    public function tplMsg($openid = '', $tpl_id = '', $templateInfo = array(), $url = '', $topcolor = '#7B68EE') {
        $arr = array();
        foreach ($templateInfo as $key => $value) {
            if ($key == 'first') {
                $arr[$key] = array('value' => urlencode($value), 'color' => "#743A3A");
            } else {
                $arr[$key] = array('value' => urlencode($value), 'color' => '#535353');
            }
        }
        return $this->weixin->doSend($openid, $tpl_id, $url, $arr, $topcolor);
    }

    /**
     * 判断是否关注
     */
    public function is_sub($openid = '') {
        $openid = $openid ? $openid : session('weixin.openid');
        if (!$openid) {
            return false;
        }
        $res = $this->weixin->get_user_baseinfo($openid);
        if ($res["subscribe"] == 0) {
            return false;
        }
        return true;
    }

    /**
     * 退款
     * 该功能需要证书支持
     * @param type $transaction_id  微信支付流水号
     * @param type $total  订单总金额
     * $refund_fee 退款金额
     * @return boolean
     */
    public function refund($transaction_id, $total_fee, $refund_fee) {

        if (!class_exists('WxPayRefund')) {
            require_once INCLUDE_PATH . "WxPayPubHelper/lib/WxPay.Api.php";
        }
        $total_fee = $total_fee * 100;
        $refund_fee = $refund_fee * 100;
        $input = new \WxPayRefund();
        $input->SetTransaction_id($transaction_id);
        $input->SetTotal_fee($total_fee);
        $input->SetRefund_fee($refund_fee);
        $input->SetOut_refund_no(C('wxshopid') . date("YmdHis"));
        $input->SetOp_user_id(C('wxshopid'));
        $WxPayApi = new \WxPayApi();
        $res = $WxPayApi->refund($input);
        if ($res['result_code'] == 'SUCCESS') {
            return array('status' => 1, 'info' => '退款成功');
        } else {
            return array('status' => 0, 'info' => $res['err_code_des'] . $res['return_msg']);
        }
    }

    /**
     * 发送微信红包
     * @param type $openid
     * @param type $money
     * @param type $msg
     */
    public function sendRed($openid, $money, $msg = '红包') {
        if (!$openid) {
            return array('status' => 0, 'msg' => 'openid不存在');
        }
        if ($money <= 0) {
            return array('status' => 0, 'msg' => '金额必须大于0');
        }
        $new_money = $money * 100;
        $data['nonce_str'] = md5(uniqid());
        $data['mch_id'] = C('wxshopid');
        $data['mch_billno'] = $data['mch_id'] . date('YmdHis', time()) . rand(1000, 9999);
        $data['wxappid'] = C('appid');
        $data['re_openid'] = $openid;
        $data['nick_name'] = C('title');
        $data['send_name'] = C('title');
        $data['total_amount'] = $new_money;
        $data['min_value'] = $new_money;
        $data['max_value'] = $new_money;
        $data['total_num'] = 1;
        $data['wishing'] = $msg;
        $data['client_ip'] = get_client_ip();
        $data['act_name'] = $msg;
        $data['remark'] = $msg;
        //创建签名
        $data['sign'] = $this->weixin->create_sign($data);
        $xml_str = arrayToXml($data);
        //发送红包
        $res = $this->weixin->send_hongbao($xml_str);
        $postObj = simplexml_load_string($res, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($postObj->result_code == 'SUCCESS') {
            return array('status' => 1, 'msg' => '发放成功');
        } else {
            return array('status' => 0, 'msg' => $postObj->return_msg);
        }
    }

    /**
     * 微信注册
     * @param type $openid
     * @param type $pid
     */
    public function reg($verfiy_id, $pid = 0, $weixinInfo = null) {
        $weixinInfo['pid'] = $pid;
        $userModel = new User();
        return $userModel->reg($verfiy_id, 'openid', $weixinInfo);

        if (C('weixin_reg') != 1) {
            return false;
        }
        $is_unionid = C('is_unionid');
        $userModel = new User();
        $type = "update";
        $field = $is_unionid == 1 ? 'unionid' : 'openid';
        $user_id = Db::name('user')->where(array($field => $verfiy_id))->value('id');
        if (!$user_id) {
            //为防止获取微信返回过慢，导致两次注册，先注册基本信息
            $nickname = $weixinInfo['nickname'] ? userTextEncode($weixinInfo['nickname']) : '';
            $u_add = array(
                'username' => $nickname ? $nickname : time(),
                'pwd' => '',
                'status' => 1,
                'create_time' => date('Y-m-d H:i:s'),
                'create_ip' => get_client_ip(),
                'update_time' => date('Y-m-d H:i:s'),
                'im_key' => xf_md5(uniqid() . rand(1000, 9999)),
                'nickname' => $nickname ? $nickname : '无昵称',
                'openid' => $is_unionid == 1 ? '' : $verfiy_id,
                'unionid' => $is_unionid == 1 ? $verfiy_id : '',
                'headimgurl' => $weixinInfo['headimgurl'] ? $weixinInfo['headimgurl'] : '',
                'sex' => $weixinInfo['sex'],
                'pid' => $pid ? $pid : 0
            );
            $user_id = Db::name('user')->insertGetId($u_add);
            $type = "add";
            if ($user_id) {
                //再将微信信息更新进去
                if ($weixinInfo == null && $is_unionid == 0) {
                    $weixinInfo = $this->anonymous_fn('get_user_baseinfo', array('p1' => $verfiy_id));
                    $nickname = userTextEncode($weixinInfo['nickname']) ? userTextEncode($weixinInfo['nickname']) : '';
                    $save = array(
                        'username' => $nickname ? $nickname : time(),
                        'nickname' => $nickname ? $nickname : '无昵称',
                        'sex' => $weixinInfo['sex'],
                        'headimgurl' => $weixinInfo['headimgurl'] ? $weixinInfo['headimgurl'] : '',
                    );
                    Db::name('user')->where(array('id' => $user_id))->save($save);
                }
                //三级分销

                Db::name('user_ext')->insert(array('user_id' => $user_id));
                $arr = array('user_id' => $user_id, 'pid1' => 0, 'pid2' => 0, 'pid3' => 0);
                if ($pid > 0) {
                    $pinfo = Db::name('user_parent')->where(array('user_id' => $pid))->find();
                    Db::name('user_ext')->where(array('user_id' => $pid))->inc('share_num')->update();
                    $arr['pid1'] = $pid;
                    $arr['pid2'] = $pinfo['pid1'];
                    $arr['pid3'] = $pinfo['pid2'];
                }
                Db::name('user_parent')->insert($arr);

                //注册优惠卷
//                $MallCoupon = D('Mall/MallCoupon');
//                $mall_coupon_tpl = Db::name('mall_coupon_tpl');
//                $coupon_tpl = $mall_coupon_tpl->where(array('type' => 2))->find();
//                $MallCoupon->send_tpl($user_id,$coupon_tpl['id'],1);
//                if($pid){
//                    $MallCoupon->send_tpl($pid,$coupon_tpl['id'],1);
//                }
            }
        }
        return array('user_id' => $user_id, 'type' => $type);
    }

    /**
     * 生成关注二维码
     * @param type $str 要附加的字符串
     */
    public function createQr($str = '') {
        $file_path = "./uploads/qr/";
        if (!is_dir($file_path)) {
            mkdir($file_path, 0777, true);
        }
        if (!$str) {
            $str = 0;
        }
        $file_path = "{$file_path}{$this->weixin->appid}_{$str}.png";
        if (!file_exists($file_path)) {
            $access_token = $this->weixin->get_access_token();
            $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $access_token;
            $todata = json_encode(array(
                'action_name' => "QR_LIMIT_STR_SCENE",
                'action_info' => array(
                    'scene' => array(
                        'scene_str' => $str,
                    )
                )
                    )
            );
            $res = $this->weixin->request_post($url, $todata);
            $res = json_decode($res, true);
            if ($res['ticket']) {
                $erweima_url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=" . $res['ticket'];
                $BaseImg = imagecreatefromstring(file_get_contents($erweima_url));
                ImagePng($BaseImg, $file_path);
                imagedestroy($BaseImg);
            }
        }
        return __ROOT__ . trim($file_path, '.');
    }

    /**
     * 执行一个匿名
     */
    public function anonymous_fn($fn_name, $params = array()) {
        extract($params);
        return $this->weixin->$fn_name($p1, $p2, $p3, $p4);
    }

    /**
     * 提现成功通知
     * @param type $uid
     * @param type $total
     */
    public function sendBroMoeny($uid, $total) {
        if (!$uid || !$total) {
            return false;
        }
        $openid = Db::name('user')->cache(true)->where('id', $uid)->value('openid');
        if (!$openid) {
            return false;
        }
        $wapurl = C('wapurl') . '/sell';
        $msg .= "佣金提现成功：{$total}元,请注意查收";
        $this->sendTxt($openid, $msg);
    }

    //提现
    public function sendMoney($user_id, $money) {
        // 'oOrcpwslfhb_8yhBKMQVfzmbtjQU',
        $openid = $user_id;
        if($user_id > 0){
            $openid = Db::name('user')->cache(true)->where(array('id' => $user_id))->getField('openid');
        }
        $data = array(
            'mch_appid' => C('appid'),
            'mchid' => C('wxshopid'),
            'nonce_str' => md5(uniqid()),
            'partner_trade_no' => get_ordernum(),
            'openid' => $openid,
            'check_name' => 'NO_CHECK',
            'amount' => $money * 100,
            'desc' => '用户提现',
            'spbill_create_ip' => get_client_ip(),
        );
        $data['sign'] = $this->weixin->create_sign($data);
        tool()->func('str');
        $xml_str = arrayToXml($data);
        $res = $this->weixin->http_post("https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers", $xml_str);
        $res .= '';
        $postObj = simplexml_load_string($res, 'SimpleXMLElement', LIBXML_NOCDATA);
        /* SimpleXMLElement Object
          (
          [return_code] => SUCCESS
          [return_msg] => 支付失败
          [mch_appid] => wxf0d39fc698344b0c
          [mchid] => 1405663102
          [result_code] => FAIL
          [err_code] => AMOUNT_LIMIT
          [err_code_des] => 付款金额不能小于最低限额,金额区间在1.00元到20000.00元之间
          ) */
        return $postObj;
    }

    public function queryTixian($partner_trade_no) {
        tool()->func('str');
        $data = array(
            'appid' => C('appid'),
            'mch_id' => C('wxshopid'),
            'nonce_str' => md5(uniqid()),
            'partner_trade_no' => $partner_trade_no,
        );
        $data['sign'] = $this->weixin->create_sign($data);
        $xml_str = arrayToXml($data);
        $res = $this->weixin->http_post("https://api.mch.weixin.qq.com/mmpaymkttransfers/gettransferinfo", $xml_str);
        $postObj = simplexml_load_string($res, 'SimpleXMLElement', LIBXML_NOCDATA);
        /* SimpleXMLElement Object
          (
          [return_code] => SUCCESS
          [return_msg] => 支付失败
          [mch_appid] => wxf0d39fc698344b0c
          [mchid] => 1405663102
          [result_code] => FAIL  //用这个
          [err_code] => AMOUNT_LIMIT
          [err_code_des] => 付款金额不能小于最低限额,金额区间在1.00元到20000.00元之间
          ) */
        $res = object_to_array($postObj);
        return $res;
    }


    /**
     * 批量获取 用户
     * @param $user_list
     * array(
     * array('openid'=>'otvxTs4dckWG7imySrJd6jSi0CWE',"lang":"zh_Cn"),
     * array('openid'=>'otvxTs4dckWG7imySrJd6jSi0CWE',"lang":"zh_Cn")
     * )
     * @return bool
     */
    public function get_user_baseinfo_batch($user_list) {
        $url = "https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token={$this->get_access_token()}";
        $data = array(
            'user_list' => $user_list,
        );
        $json_data = json_encode($data);
        $dataRes = $this->request_post($url, urldecode($json_data));
        return json_decode($dataRes, true);
    }

}
