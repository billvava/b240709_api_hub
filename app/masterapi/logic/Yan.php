<?php

namespace app\masterapi\logic;

use app\admin\model\Ad;
use app\admin\model\SuoCate;
use app\admin\model\SuoOrder;
use app\admin\model\SuoOrderJia;
use app\admin\model\SuoProduct;
use app\admin\model\SystemGroup;
use app\admin\model\SuoMeansContent;
use app\admin\model\SuoActivities;
use app\common\lib\Util;
use app\common\model\SuoMaster;
use app\admin\model\SuoMaster as SuoMasterModel;
use app\admin\model\SuoQuickReply;
use app\com\model\HelpMsg;
use think\App;
use think\facade\Db;
use app\admin\model\SuoProfit;

class Yan
{

    public $in;
    public $uinfo;
    public $data;
    public $request;
    public $model;

    public function __construct()
    {


    }

    public function config($map)
    {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
        $map['request'] && $this->request = $map['request'];
    }

    public function getcode()
    {

        $tel = $this->in['tel'];
        if (!$tel) {
            return ['status' => 0, 'info' => '请输入手机'];
        }
        $res = (new Util())->sms($tel, 'SMS_169960418');
        if ($res['status'] != 1) {
            return ['status' => 0, 'info' => $res['info']];
        }
        cache("send_verify_{$tel}", $res['code'], 300);
        cache("send_verify_num_{$tel}", 10);
        return ['status' => 1, 'info' => '发送成功,5分钟内有效'];
    }

    public function sub()
    {
        $Common = new \app\masterapi\controller\Rule(app());
        $Common->check("shiming");

        $res = $this->check_verify();
        if ($res['status'] == 0) {
            return ['status' => 0, 'info' => $res['info']];
        }

        $tel = $this->in['tel'];
        $idcard = $this->in['idcard'];
        $realname = $this->in['realname'];
//        $res = $this->renzheng($tel, $idcard, $realname);
//        if ($res['status'] == 0) {
//            return ['status' => 0, 'info' => $res['info']];
//        }
        $this->bad_verify();
        (new SuoMasterModel())->where([
            ['id','=',$this->uinfo['id']]
        ])->save([
            'tel'=>$tel,
            'idcard'=>$idcard,
            'realname'=>$realname,
            'idcard_front'=>$this->in['idcard_front'],
            'idcard_back'=>$this->in['idcard_back'],
            'tel'=>$tel,
            'is_auth'=>1,
        ]);

        return ['status' => 1, 'info' =>'认证成功'];
    }

    //验证验证码
    private function check_verify()
    {
//        return ['status' => 1];
        $reg_cache = "send_verify_{$this->in['tel']}";
        $is = cache($reg_cache);
        $time_num_cache = "send_verify_num_{$this->in['tel']}";
        $num = cache($time_num_cache);
        $num = $num - 1;
        cache($time_num_cache, $num, 60 * 30);
        if (!$is || $is != $this->in['code'] || !$this->in['tel']) {
            return array('status' => 0, "info" => "验证码错误");
        }
        if ($num <= 0) {
            return array('status' => 0, "info" => "验证码已失效");
        }
        return ['status' => 1];
    }

    //销毁验证码
    private function bad_verify()
    {
        $reg_cache = "send_verify_{$this->in['tel']}";
        cache($reg_cache, null);
    }

    //实名方法
    private function renzheng($tel, $idcard, $realname)
    {


        $host = "https://phone3.market.alicloudapi.com";
        $path = "/phonethree";
        $method = "GET";
        $appcode = "fea6b37e1d5d4a8b88d9f61ab84961eb";
        $headers = array();
        $realname = urlencode($realname);
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $querys = "idcard={$idcard}&phone={$tel}&realname={$realname}";
        $bodys = "";
        $url = $host . $path . "?" . $querys;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if (1 == strpos("$" . $host, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $res = curl_exec($curl);
        $res = json_decode($res, true);

        if ($res['code'] == '200') {
            return ['status' => 1];
        } else {
            return ['status' => 0, 'info' => $res['msg']];

        }
    }


}
