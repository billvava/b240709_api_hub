<?php

namespace app\shopapi\logic;

use app\admin\model\Ad;
use app\admin\model\Shop;
use app\admin\model\SystemGroup;
use app\mall\model\GoodsHistory;
use app\mall\model\MallCouponTpl;
use app\shopapi\model\O;
use think\App;
use think\facade\Db;

class Goods
{

    public $clear;
    public $uinfo;
    public $data;
    public $model;
    public $goodsModel;
    public $in;

    public function __construct()
    {
        $this->model = new \app\common\model\SuoMaster();
        $this->usermodel = new \app\common\model\User();
        $this->goodsModel = new \app\shopapi\model\Goods();
    }

    public function config($map)
    {
        $map['uinfo'] && $this->uinfo = $map['uinfo'];
        $map['in'] && $this->in = $map['in'];
        $map['data'] && $this->data = $map['data'];
    }

    //商品列表
    public function index()
    {

        if ($this->in['load_other'] == 1) {
            $this->data['category_list'] = Db::name('mall_goods_category')->where(array('is_show' => 1, 'pid' => 0))->order("sort desc")->cache(true)->select()->toArray();
            array_unshift($this->data['category_list'], array('category_id' => 0, 'name' => '全部商品'));

            $this->data['category_index'] = 0;
            $orders = array(
                array(
                    'name' => '默认排序',
                    'order' => '',
                ),
                array(
                    'name' => '价格从低到高',
                    'order' => 'min_price',
                ),
                array(
                    'name' => '价格从高到低',
                    'order' => 'max_price',
                ),
                array(
                    'name' => '销量从高到低',
                    'order' => 'sale_num',
                ),
            );
            $this->data['order_index'] = 0;
            $this->data['orders'] = $orders;
        }
        $order_arr = array(
            'recommend' => 'is_recommend desc',
            'sale_num' => 'sale_num desc',
            'is_new' => 'is_new desc',
            'min_price' => 'min_price asc',
        );
        $in = array(
            'status' => 1,
            'category_id' => $this->in['category_id'],
            'page' => $this->in['page'],
            'name' => $this->in['name'],
            'order_type' => $this->in['order_type'],
        );
       
        if($this->in['category_id']){
            $this->data['name'] =Db::name('mall_goods_category')->where(array('is_show' => 1, 'category_id'=>$this->in['category_id']))->value('name');
        }else{
            $this->data['name'] = '商品列表';
        }
        $res = $this->goodsModel->get_data($in);
        $this->data['list'] = $res['list'];
        $this->data['count'] = count($res['list']);
        return array('status' => 1, 'data' => $this->data);
    }

    public function get_data()
    {
        return $this->data;
    }

    //秒杀
    public function seckill()
    {
        //秒杀活动
        if ($this->in['seckill_id']) {
            $this->data['price_name'] = '秒杀价';
            $MsGoods = new \app\shopapi\model\MsGoods();
            $msinfo = $MsGoods->getInfo($this->in['seckill_id']);
            if (!$msinfo) {
                $this->data['sale_status'] = 0;
                $this->data['sale_msg'] = '商品不存在';
            }

            if ($msinfo['num'] <= 0) {
                $this->data['sale_status'] = 0;
                $this->data['sale_msg'] = '已售罄';
            }
            if ($msinfo['status'] != 1) {
                $this->data['sale_status'] = 0;
                $this->data['sale_msg'] = '已下架';
            }
            if (strtotime($msinfo['start']) > time()) {
                $this->data['sale_status'] = 0;
                $this->data['sale_msg'] = '活动未开始';
            }
            if (strtotime($msinfo['end']) < time()) {
                $this->data['sale_status'] = 0;
                $this->data['sale_msg'] = '活动已结束';
            }
            //正常价格换成秒杀价格
            $this->data['act_info'] = $msinfo;
            $this->data['goods_item'] = $this->data['act_info']['items'];

            $this->data['info']['is_down'] = 1;
            $this->data['info']['down_second'] = $msinfo['second'];
            $this->data['info']['min_price'] = $msinfo['min_price'];

            $this->data['order_type'] = 'seckill';
        }
    }

    //拼团
    public function group()
    {
        //秒杀活动
        if ($this->in['group_id']) {
            $this->data['price_name'] = '拼团价';
            $MsGoods = new \app\shopapi\model\PtGoods();
            $msinfo = $MsGoods->getInfo($this->in['group_id']);
            if (!$msinfo) {
                $this->data['sale_status'] = 0;
                $this->data['sale_msg'] = '商品不存在';
            }

            if ($msinfo['num'] <= 0) {
                $this->data['sale_status'] = 0;
                $this->data['sale_msg'] = '已售罄';
            }
            if ($msinfo['status'] != 1) {
                $this->data['sale_status'] = 0;
                $this->data['sale_msg'] = '已下架';
            }
            if (strtotime($msinfo['start']) > time()) {
                $this->data['sale_status'] = 0;
                $this->data['sale_msg'] = '活动未开始';
            }
            if (strtotime($msinfo['end']) < time()) {
                $this->data['sale_status'] = 0;
                $this->data['sale_msg'] = '活动已结束';
            }
            //正常价格换成秒杀价格
            $this->data['act_info'] = $msinfo;
            $this->data['goods_item'] = $this->data['act_info']['items'];

            $this->data['info']['is_down'] = 1;
            $this->data['info']['down_second'] = $msinfo['second'];
            $this->data['info']['min_price'] = $msinfo['min_price'];

            $this->data['order_type'] = 'group';

        }
    }

    //商品详情
    public function item()
    {
        $this->data['order_type'] = 'goods';

        $this->data['info'] = $this->goodsModel->getInfo($this->in['goods_id'], 1, 0);
        $this->data['info']['min_price'] = $this->data['info']['min_price2'];

        $this->data['sale_status'] = 1;
        $this->data['sale_msg'] = '';

        $flag = 1;
        $goods_id = $this->in['goods_id'];
        if (!$this->data['info']) {
            return array('status' => 0, 'info' => '商品已下架！');
        }

        if ($this->data['info']['status'] != 1) {
            $this->data['sale_status'] = 0;
            $this->data['sale_msg'] = '商品已下架';
        }

        //倒计时效果
        if ($this->data['info']['is_down'] == 1) {
            $today = date('Y-m-d');
            $this->data['info']['down_second'] = strtotime("{$today} 23:59:59") - time();
        }
        $this->data['price_name'] = '促销价';


        $in = array(
            'status' => 1,
            'is_recommend' => 1,
            'cache' => true
        );
        //库存
        $this->data['info']['num'] = (new \app\mall\logic\Goods())->get_goods_nums($goods_id);

        //推荐商品
        $res = $this->goodsModel->get_data($in);
        $this->data['re_goods'] = $res['list'];

        //相关规格
        if ($this->data['info']['spec_type'] == 2) {
            $this->data['goods_spec'] = $this->goodsModel->getSpec($goods_id);
            $this->data['goods_item'] = $this->goodsModel->getItems($goods_id, $this->data['info']['thumb']);
        }
        if ($this->data['info']['shop_id'] ) {
            $this->data['shop_info'] = (new Shop())->getInfo($this->data['info']['shop_id']);
        }
        $this->seckill();
        $this->group();

        //相关评论
        $mall_goods_comment = Db::name('mall_goods_comment');
        $this->data['com_list'] = Db::name('mall_goods_comment')
            ->where(array('goods_id' => $goods_id, 'status' => 1))
            ->limit(5)
            ->order('id desc')
            ->select()->toArray();
        foreach ($this->data['com_list'] as &$v) {

            $v['images'] = json_decode($v['images'], true);
            $v['images'] = array_map('get_img_url', $v['images'] ? $v['images'] : []);

            //在门店端评论商品就获取门店用户的头像，否则获取用户端的用户头像
            if($v['master_id']){
               $uu = $this->model->getUserInfo($v['master_id']);
            }else{
               $uu = $this->usermodel->getUserInfo($v['user_id']);
            }

            $v['headimgurl'] = $uu['headimgurl'];

        }
       
        $this->data['com_count'] = Db::name('mall_goods_comment')
            ->where(array('goods_id' => $this->in['goods_id'], 'status' => 1))
            ->count();

        //访问历史
        if ($this->uinfo['id']) {
            (new GoodsHistory())->addLog($goods_id, $this->uinfo['id']);
        }
        //商品参数
        $this->data['attr'] = $this->goodsModel->get_attr($this->data['info']['goods_id'], $this->data['info']['attr_id']);
        $this->data['attr_str'] = '';
        if($this->data['attr']){
            foreach($this->data['attr'] as $k=>$v2){
                if($k > 2){
                    break;
                }
                $this->data['attr_str'] .= "{$v2['name']}:{$v2['val']},";
            }
            $this->data['attr_str']  = trim($this->data['attr_str'],',');
        }
        //收藏
        $this->data['shangpbaozhang'] = (new SystemGroup())->getCache('shangpbaozhang');
        $this->data['shangpbaozhang_str'] = '';
        if($this->data['shangpbaozhang']){
            foreach($this->data['shangpbaozhang'] as $k=>$v3){
                if($k > 2){
                    break;
                }
                $this->data['shangpbaozhang_str'] .= "{$v3['name']}·";
            }
            $this->data['shangpbaozhang_str'] = trim($this->data['shangpbaozhang_str'],'·');
        }
//        sqlListen();
        $this->data['is_atn'] = (new \app\mall\logic\Goods())->isAtn2($this->in['goods_id'], $this->uinfo['id']);

        $ids = Db::name('mall_coupon')->where([
            ['master_id','=',$this->uinfo['id']]
        ])->column('tpl_id');
        $ids = $ids?$ids:[-1];
        $where = function ($query) use($in,$ids) {

            $w1[]=['type','=',4];
            $w1[]=['id','not in',$ids];

            $query->where($w1)->where(function ($query){
                $w[]=['day', '>', 0];
                $w[]=['end', '>', date('Y-m-d H:i:s')];
                $query->whereOr($w);
            });
        };
        $MallCouponTpl = new \app\shopapi\model\MallCouponTpl();
        $this->data['coupon_list'] = $MallCouponTpl->getAll($where);


        return array('status' => 1, 'data' => $this->data);
    }

    //获取规格列表
    public function get_goods()
    {
        $this->data['info'] = $this->goodsModel->getInfo($this->in['goods_id']);
        $this->data['sale_status'] = 1;

        if ($this->data['info']['status'] != 1) {
            $this->data['sale_status'] = 0;
            return array('status' => 0, 'info' => '下架中');
        }
        $this->data['goods_spec'] = $this->goodsModel->getSpec($this->in['goods_id']);
        $this->data['goods_item'] = $this->goodsModel->getItems($this->in['goods_id'], $this->data['info']['thumb']);

        //秒杀价格覆盖
        $this->seckill();

        //拼团价格修改
        if ($this->in['pt_id']) {
            $ptinfo = Db::name('pt_goods')->where(array('id' => $this->in['pt_id']))->find();
            $items = json_decode($ptinfo['items'], true);
            foreach ($this->data['goods_item'] as &$v) {
                if ($items[$v['id']]) {
                    $v['price'] = $items[$v['id']];
                }
            }
        }
        if ($this->data['sale_status'] == 0) {
            return ['status' => 0, 'info' => $this->data['sale_msg']];
        }


        return array('status' => 1, 'data' => $this->data);
    }

    //领取优惠券
    public function get_coupon()
    {
        $MallCouponTpl = new MallCouponTpl();
        $page = $this->in['page'] ? $this->in['page'] : 1;
        $where = [
            ['type', '=', 4],
            ['hd_time', '>', date('Y-m-d H:i:s')],
        ];
        $this->data['list'] = $MallCouponTpl->getList($where, $page);
        $cs = Db::name('mall_coupon')->where(array('master_id' => $this->uinfo['id']))->column('tpl_id');
        $num = 0;
        foreach ($this->data['list'] as &$v) {
            $v['is_get'] = in_array($v['id'], $cs) ? true : false;
            if (!$v['is_get']) {
                $num++;
            }
        }
        $this->data['num'] = $num;
        $this->data['count'] = count($this->data['list']);
        return array('status' => 1, 'data' => $this->data);
    }

    //商品二维码
    public function get_ewm()
    {
        $this->data['ginfo'] = $this->goodsModel->getInfo($this->in['goods_id'], 1, 0);
        $this->data['ewm_url'] = $this->create_goods_share($this->uinfo['id'], $this->data['ginfo']);
        return array('status' => 1, 'data' => $this->data);
    }

    //获取商品评论
    public function get_goods_comment()
    {
        $where = array('status' => 1);
        if ($this->in['goods_id']) {
            $where['goods_id'] = $this->in['goods_id'];
        }
        if ($this->in['shop_id']) {
            $where['shop_id'] = $this->in['shop_id'];
        }
        $page = $this->in['page'] ? $this->in['page'] : 1;
        $limit = $this->in['limit'] ? $this->in['limit'] : 10;
        $Comment = new \app\shopapi\model\Comment();
        $this->data['list'] = $Comment->get_list($where, $page);
        $this->data['count'] = count($this->data['list']);
        return array('status' => 1, 'data' => $this->data);
    }

    //分类页面
    public function cate()
    {
        if ($this->in['load_other'] == 1) {
            $mall_goods_category = Db::name('mall_goods_category');
            $where = array('is_show' => 1, 'pid' => 0);
            if ($this->in['category_id']) {
                $pid = $mall_goods_category->removeOption()->where(array('category_id' => $this->in['category_id']))->value("pid");
                $where['pid'] = $pid;
            }
            $this->data['category_list'] = $mall_goods_category->removeOption()->where($where)->order("sort desc")->cache(true)->select()->toArray();
        }
        $in = array(
            'status' => 1,
            'category_id' => $this->in['category_id'],
            'page' => $this->in['page'],
            'name' => $this->in['name'],
            'order_type' => $this->in['order_type']
        );
        $res = $this->goodsModel->get_data($in);
        $this->data['list'] = $res['list'];
        $this->data['count'] = count($res['list']);
        return array('status' => 1, 'data' => $this->data);
    }

    public function load_list()
    {
        if ($this->in['load_other'] == 1) {
            $arr = array(
                'is_xinren' => 10,
                'is_recommend' => 8,
                'is_dz' => 14,
                'is_lp' => 15,
            );
            $ad_id = $arr[$this->in['field']];

            $this->data['ad8'] = (new Ad())->get_ad($ad_id);
        }
        $in = array(
            'status' => 1,
            'category_id' => $this->in['category_id'],
            'page' => $this->in['page'],
            'food_type' => $this->in['food_type'],
        );
        if (in_array($this->in['field'], array('is_un', 'is_recommend', 'is_xinren', 'is_dz'))) {
            $in[$this->in['field']] = 1;
        }
        $res = $this->goodsModel->get_data($in);
        $this->data['list'] = $res['list'];
        $this->data['count'] = count($res['list']);
        return array('status' => 1, 'data' => $this->data);
    }

    private function create_goods_share($master_id, $ginfo)
    {
        $temp_ewm_path = C('temp_ewm_path');
        $path = $temp_ewm_path . "shop/{$ginfo['goods_id']}/";
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $name = "{$master_id}.png";
        $file = "{$path}{$name}";
        if (!file_exists($file) || 1) {
            $base_img = "./img/1.jpg";
            $BaseImg = imagecreatefromstring(file_get_contents($base_img));
            //二维码
            $res = (new O())->get_cxx_ewm($master_id);
            if ($res['status'] == 1) {
                $QRcodeImg = imagecreatefromstring($res['img']);
                $QRcodeImg_width = imagesx($QRcodeImg);
                $QRcodeImg_height = imagesy($QRcodeImg);
                imagecopyresampled($BaseImg, $QRcodeImg, 344, 525, 0, 0, 120, 120, $QRcodeImg_width, $QRcodeImg_height);
            }
            //缩略图
            $QRcodeImg = imagecreatefromstring(file_get_contents($ginfo['thumb']));
            $QRcodeImg_width = imagesx($QRcodeImg);
            $QRcodeImg_height = imagesy($QRcodeImg);
            imagecopyresampled($BaseImg, $QRcodeImg, 10, 10, 0, 0, 470, 490, $QRcodeImg_width, $QRcodeImg_height);

            //写文字
            $color = imagecolorallocate($BaseImg, 0, 0, 0);
            $lenth = mb_strlen($ginfo['name'], 'utf-8');

            $one = mb_substr($ginfo['name'], 0, 12, 'utf-8');
            $two = mb_substr($ginfo['name'], 12, 12, 'utf-8');

            $is_win = PATH_SEPARATOR == ';' ? 1 : 0;
            $font = './static/admin/font/AlibabaPuHuiTi-2-55-Regular.ttf';
            if ($is_win) {
                $font = getcwd() . '\static\admin\font\AlibabaPuHuiTi-2-55-Regular.ttf';
            }
            imagefttext($BaseImg, 20, 0, 20, 550, $color, $font, $one);
            if ($two) {
                imagefttext($BaseImg, 20, 0, 20, 580, $color, $font, $two);
            }
            //写文字
            $color = imagecolorallocate($BaseImg, 243, 34, 36);
            $ginfo['min_price'] = ceil($ginfo['min_price_old']);
            imagefttext($BaseImg, 22, 0, 20, 650, $color, $font, "￥ {$ginfo['min_price']}");

            $bool = ImagePng($BaseImg, $file);
            imagedestroy($BaseImg);
        }

        $host = C('wapurl') ?: "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}";
        return $host . trim($file, '.');
    }

    private function get_cxx_ewm($master_id)
    {
        return array('status' => 1, 'img' => file_get_contents("./img/empHeader.jpg"));
//        $url = "/pages/goods/item/item?goods_id={$goods_id}&pid={$master_id}";
        if (!class_exists('weixin')) {
            include INCLUDE_PATH . 'weixin/weixin.class.php';
        }
        $weixin = new \weixin(C('appid'), C('apppwd'));
        $access_token = $weixin->get_access_token();
        $url = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=' . $access_token;

        $scene = "pid_{$master_id}";
//        p($scene);
        $data = json_encode(
            array(
                'scene' => ($scene),
                'page' => "pages/index/index",
            )
        );
        $res = $weixin->request_post($url, $data);
        $t = json_decode($res, true);
        if (is_array($t)) {
            return array('status' => 0, 'info' => $t['errmsg']);
        } else {
            return array('status' => 1, 'img' => $res);
        }
    }

}
