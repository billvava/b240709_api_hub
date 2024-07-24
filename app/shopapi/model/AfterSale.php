<?php

namespace app\shopapi\model;

use think\facade\Db;
use think\Model;

/**
 * 代码自动生成所需参数
 * moudleName:模块名称
 * tableName:表名
 * modelName:模型名称
 * */
class AfterSale extends Model {

    protected $name = 'mall_after_sale';

    const USER_APPLY_REFUND = '会员申请退款';
    const USER_SEND_EXPRESS = '会员填写退货物流信息';
    const USER_CANCEL_REFUND = '会员撤销售后申请';
    const USER_AGAIN_REFUND = '会员重新提交申请';
    const SHOP_AGREE_REFUND = '商家同意退款';
    const SHOP_REFUSE_REFUND = '商家拒绝退款';
    const SHOP_TAKE_GOODS = '商家收货';
    const SHOP_REFUSE_TAKE_GOODS = '商家拒绝收货';
    const SHOP_CONFIRM_REFUND = '商家确认退款';
    const REFUND_SUCCESS = '退款成功';
    const REFUND_ERROR = '退款失败';

    public function handle($v) {
        $lan = $this->getLan();
        $v['refund_type_text'] = $lan['refund_type'][$v['refund_type']];
        $v['status_text'] = $lan['status'][$v['status']];

        $v['status_str'] = $v['status_text'];


        //售后日志
        $v['log'] = Db::name('mall_after_log')->where(array('after_id' => $v['id']))->order('id desc')->select()->toArray();
        $orderLogic = new \app\mall\logic\Order();
        $res = $orderLogic->getOption($v);
        $pay_type = lang('pay_type');
        $v['pay_type_str'] = $pay_type[$v['pay_type']];
        $v['user'] = (new \app\common\model\User())->getUserInfo($v['master_id']);
        $v['show_send'] = 0;
        $v['show_cacel'] = 0;
        if ($v['status'] == 2) {
            $v['show_send'] = 1;
        }
        if ($v['status'] != 6) {
            $v['show_cacel'] = 1;
        }

        if($v['order_goods_id']){
            $v['order_goods'][] = Db::name('mall_order_goods')->where(array('id' => $v['order_goods_id']))->find();

        }else{
            $orderModel = new Order();
            $v['order_goods'] = $orderModel->getGoods($v['order_id']);

        }
        foreach ($v['order_goods'] as &$vg) {
            $vg['thumb'] = get_img_url($vg['thumb']);
        }
        $v['name'] = $v['order_goods'][0]['name'];

        return $v;
    }

//售后原因
    public function getReasonLists() {
        $data = [
            '7天无理由退换货',
            '大小尺寸与商品描述不符',
            '颜色/图案/款式不符',
            '做工粗糙/有瑕疵',
            '质量问题',
            '卖家发错货',
            '少件（含缺少配件）',
            '不喜欢/不想要',
            '快递/物流一直未送到',
            '空包裹',
            '快递/物流无跟踪记录',
            '货物破损已拒签',
            '其他',
        ];
        return $data;
    }

    public function getList($where, $page = 1, $num = 10, $cache = false) {
        $order = "a.id desc";
        $data = Db::name('mall_after_sale')->alias('a')->where($where)->join("mall_order b", "a.order_id=b.order_id")
                        ->field("a.*,b.pay_type,b.pay_status,b.status as order_status,b.ordernum,total,trade_no")
                        ->page($page, $num)
                        ->order($order)
                        ->select()->toArray();
        foreach ($data as &$v) {
            $v = $this->handle($v);
        }
        return $data;
    }

    public function get_data($where = array(), $num = 10) {
        $count =  Db::name('mall_after_sale')->where($where)->count();
        tool()->classs('PageForAdmin');
        $page = new \PageForAdmin($count,$num);
        $order = "id desc";
        $data['count'] = $count;
        $data['list'] = Db::name('mall_after_sale')->where($where)->limit($Page->firstRow, $Page->listRows)->order($order)->select()->toArray();
        $data['page'] = $page->show();
        return $data;
    }

    public function getAll($where = array()) {
        $pre = md5(serialize($where));
        $name = "mall_after_sale_1615534837_{$pre}";
        $data = S($name);
        if (!$data) {
            $order = "id desc";
            $data = Db::name('mall_after_sale')->where($where)->order($order)->select()->toArray();
            S($name, $data);
        }
        return $data;
    }

    public function getInfo($pk) {
        if (!$pk) {
            return null;
        }
        $data = Db::name('mall_after_sale')->alias('a')->where(array('a.id' => $pk))->join("mall_order b", "a.order_id=b.order_id")
                        ->field("a.*,b.pay_type,b.pay_status,b.status as order_status,b.ordernum,total,trade_no")
                        ->find();
        $data = $this->handle($data);
        return $data;
    }

    //获取字典
    public function getLan($field = '', $type = 1) {
        $lans = array(
            'refund_type' => array('0' => '仅退款', 1 => '退款退货', '2' => '换货', '3' => '维修',),
            'status' => array('0' => '申请退款中', '1' => '商家拒绝', '2' => '商品待退货', '3' => '商家待收货', '4' => '商家拒收货', '5' => '等待退款', '6' => '退款成功', '7' => '已撤销',),
            'del' => array('0' => '正常', '1' => '已撤销',),);
        if ($field == '') {
            return $lans;
        }
        if ($type == 2) {
            $tmp = array();
            foreach ($lans[$field] as $k => $v) {
                $tmp[] = array('key' => $k, 'val' => $v);
            }
            return $tmp;
        }
        return $lans[$field];
    }

    public function getOption($name = 'name') {
        $as = $this->getAll();
        $names = array_reduce($as, create_function('$v,$w', '$v[$w["' . $this->getPk() . '"]]=$w["' . $name . '"];return $v;'));
        return $names;
    }

    public function clear($pk = '') {
        $name = "mall_after_sale_1615534837";
        S($name, null);
        if ($pk) {
            $name = "mall_after_sale_info_1615534837_{$pk}";
            S($name, null);
        }
    }

    public function setVal($id, $key, $val) {
        $pk = $this->getPk();
        if ($pk) {
            return $this->where(array($pk => $id))->save([$key=>$val]);
        }
    }

    /**
     * 列名
     * @return type
     */
    public function attributeLabels() {
        return array(
            'id' => 'id',
            'sn' => '退款单号',
            'master_id' => '用户id',
            'order_id' => '订单id',
            'order_goods_id' => '订单商品关联表id',
            'goods_id' => '商品id',
            'item_id' => '规格id',
            'goods_num' => '商品数量',
            'refund_reason' => '退款原因',
            'refund_remark' => '退款说明',
            'refund_image' => '退款图片',
            'refund_type' => '退款类型|0=仅退款,1=退款退货',
            'refund_price' => '退款金额',
            'express_name' => '快递公司名称',
            'invoice_no' => '快递单号',
            'express_remark' => '物流备注说明',
            'express_image' => '物流凭证',
            'confirm_take_time' => '确认收货时间',
            'status' => '售后状态|0=申请退款,1=商家拒绝,2=商品待退货,3=商家待收货,4=商家拒收货,5=等待退款,6=退款成功',
            'audit_time' => '审核时间',
            'admin_id' => '门店管理员id',
            'admin_remark' => '售后说明',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
            'del' => '撤销状态|0=正常,1=已撤销',
            'goods_info' => 'goods_info',
        );
    }

    /**
     * 规则
     * @return type
     */
    public function rules() {
        return array(
            array('sn', '0,20', '退款单号长度最大20个字符', 0, 'length'),
            array('master_id', 'require', '用户id不能是空！', 0),
            array('master_id', 'checkIsInt', '用户id必须是整数', 0, 'callback'),
            array('order_id', 'checkIsInt', '订单id必须是整数', 0, 'callback'),
            array('order_goods_id', 'checkIsInt', '订单商品关联表id必须是整数', 0, 'callback'),
            array('goods_id', 'checkIsInt', '商品id必须是整数', 0, 'callback'),
            array('item_id', 'checkIsInt', '规格id必须是整数', 0, 'callback'),
            array('goods_num', 'checkIsInt', '商品数量必须是整数', 0, 'callback'),
            array('refund_reason', '0,255', '退款原因长度最大255个字符', 0, 'length'),
            array('refund_remark', '0,255', '退款说明长度最大255个字符', 0, 'length'),
            array('refund_image', '0,255', '退款图片长度最大255个字符', 0, 'length'),
            array('refund_type', 'checkIsInt', '退款类型|0=仅退款,1=退款退货必须是整数', 0, 'callback'),
            array('refund_price', 'checkIsNumber', '退款金额必须是数字', 0, 'callback'),
            array('express_name', '0,255', '快递公司名称长度最大255个字符', 0, 'length'),
            array('invoice_no', '0,255', '快递单号长度最大255个字符', 0, 'length'),
            array('express_remark', '0,255', '物流备注说明长度最大255个字符', 0, 'length'),
            array('express_image', '0,255', '物流凭证长度最大255个字符', 0, 'length'),
            array('confirm_take_time', 'checkIsInt', '确认收货时间必须是整数', 0, 'callback'),
            array('status', 'checkIsInt', '售后状态|0=申请退款,1=商家拒绝,2=商品待退货,3=商家待收货,4=商家拒收货,5=等待退款,6=退款成功必须是整数', 0, 'callback'),
            array('audit_time', 'checkIsInt', '审核时间必须是整数', 0, 'callback'),
            array('admin_id', 'checkIsInt', '门店管理员id必须是整数', 0, 'callback'),
            array('admin_remark', '0,255', '售后说明长度最大255个字符', 0, 'length'),
            array('create_time', 'checkIsInt', '创建时间必须是整数', 0, 'callback'),
            array('update_time', 'checkIsInt', '更新时间必须是整数', 0, 'callback'),
            array('del', 'checkIsInt', '撤销状态|0=正常,1=已撤销必须是整数', 0, 'callback'),
            array('goods_info', '0,255', 'goods_info长度最大255个字符', 0, 'length'),
        );
    }

    /**
     * 自增的字段
     * @return type
     */
    public function getAutoField() {
        return "id";
    }

    /**
     * 默认值
     * @return type
     */
    public function defaultValue() {
        return array(
            'id' => '',
            'master_id' => '',
            'order_id' => '',
            'order_goods_id' => '',
            'goods_id' => '',
            'item_id' => '0',
            'goods_num' => '',
            'refund_reason' => '',
            'refund_remark' => '',
            'refund_image' => '',
            'refund_type' => '',
            'refund_price' => '',
            'express_name' => '',
            'invoice_no' => '',
            'express_remark' => '',
            'express_image' => '',
            'confirm_take_time' => '',
            'status' => '0',
            'audit_time' => '',
            'admin_id' => '',
            'admin_remark' => '',
            'create_time' => '',
            'update_time' => '',
            'del' => '0',
            'goods_info' => '',
        );
    }

    /**
     * 要转成json的字段
     * @return type
     */
    public function jsonAttr() {
        return array(
        );
    }

    /**
     * 字段类型
     * @return type
     */
    public function fieldType() {
        return array(
                #fieldType#
        );
    }

    /**
     * 检测是否为数字
     * @param type $str
     * @return boolean
     */
    function checkIsNumber($str) {
        if ($str === '') {
            return false;
        }
        return is_numeric($str);
    }

    /**
     * 检测是否为数字，并且符号是正
     * @param type $str
     * @return boolean
     */
    function checkIsZhengNumber($str) {
        if ($str === '') {
            return false;
        }
        if (!is_numeric($str)) {
            return false;
        }
        if ($str < 0) {
            return false;
        }
        return true;
    }

    /**
     * 检测是否为正整数
     * @param type $str
     * @return boolean
     */
    function checkIsNotInt($str) {
        if (!$str && $str !== '0') {
            return false;
        }
        if (!is_numeric($str)) {
            return false;
        }
        if ($str < 0) {
            return false;
        }
        if (is_int($str)) {
            return true;
        }
    }

    /**
     * 检测是否为整数
     * @param type $str
     * @return boolean
     */
    function checkIsInt($str) {
        if (!$str && $str !== '0') {
            return false;
        }
        if (!is_numeric($str)) {
            return false;
        }
        if (is_int($str)) {
            return true;
        }
    }

    /**
     * 验证邮箱
     * @param type $str
     * @return boolean
     */
    function checkEmail($str) {
        if (!$str) {
            return false;
        }
        $chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
        if (strpos($str, '@') !== false && strpos($str, '.') !== false) {
            if (preg_match($chars, $str)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 检查手机号码格式
     * @param type $str
     * @return boolean
     */
    function checkTel($str) {
        if (!$str) {
            return false;
        }
        if (preg_match("/^1\d{10}$/", $str)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 检测数组，例如 id[]
     * @param type $i
     * @return boolean
     */
    public function checkArr($i) {
        if (count($i) <= 0) {
            return false;
        }
        return true;
    }

    /**
     * 检测生日
     */
    function checkIsDate($str) {
        if (strtotime($str) == 0) {
            return false;
        } else {
            return true;
        }
    }

}
