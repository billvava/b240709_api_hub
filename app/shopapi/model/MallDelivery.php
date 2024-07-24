<?php
declare (strict_types = 1);

namespace app\shopapi\model;

use app\admin\model\SystemAreas;
use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class MallDelivery extends Model {


    protected $name='mall_delivery';

        public function dbName()
    {
       return $this->name;
    }

    public  function get_pk(){
        return "delivery_id";
    }



    public function handle($v) {

        if ($v['content']) {
            $preg = '/<img.*?src=[\"|\']?(.*?)[\"|\']?\s.*?>/i';
            $v['html'] = htmlspecialchars_decode($v['content'], ENT_QUOTES);
            preg_match_all($preg, $v['html'], $allImg);
            foreach ($allImg[1] as $k => $av) {
                $new_str = "<img src='" . get_img_url($av) . "' style='width:100%; display:block;float:left;height:auto;' />";
                $v['html'] = str_replace($allImg[0][$k], $new_str, $v['html']);
            }
        }

        return $v;
    }
    
    public  function getList($where, $page=1 ,$num = 10) {
        $order = "sort asc,delivery_id desc";
        $data = Db::name($this->name)->where($where)->page(($page ?: 1),$num)->order($order)->cache(true)->select()->toArray();
        foreach ($data as &$v) {

        }
        return $data;
    }

     public  function get_data($where = array(), $num = 10) {
        $count = Db::name($this->name)->where($where)->count();
        tool()->classs('Page');
        $Page = new \Page($count, 20);
        $order = "sort asc,delivery_id desc";
        $data['count'] = $count;
        $data['list'] = Db::name($this->name)->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->order($order)->select()->toArray();
        $data['page'] = $Page->show();
        return $data;
    }

    public  function getAll($where = array()){
       $pre =  md5(serialize($where));
       $name = "mall_delivery_1616060509_{$pre}";
       $data = cache($name);
       if(!$data){
           $order = "sort asc,delivery_id desc";
          $data = Db::name($this->name)->where($where)->order($order)->select()->toArray();
          cache($name,$data);
       }
       return $data;
    }


    public  function itemAll(){
        $data=$this->getAll();
        $list=array();
        $pk = $this->get_pk();
        foreach ($data as $v){
            $list[]=array(
                'val'=>$v[$pk],'name'=>$v['name']
            ) ;
        }
        return $list;
    }


    /**
     * 可配送区域的html
     * @param type $value
     * @param type $data
     * @return string
     */
    public function getRegionContentAttr($data) {
        // 当前区域记录转换为数组
        $regionIds = explode(',', $data['region']);

        if (count($regionIds) === 366) {
            return '全国';
        }
        $SystemAreas = new SystemAreas();
        // 所有地区
        $regionAll = $SystemAreas->getCacheAll();
        $regionTree = $SystemAreas->getCacheTree();
        // 将当前可配送区域格式化为树状结构
        $alreadyTree = array();
        foreach ($regionIds as $regionId) {
            if(isset($regionAll[$regionId]['pid'])){
                $alreadyTree[$regionAll[$regionId]['pid']][] = $regionId;
            }

        }
        $str = '';
        foreach ($alreadyTree as $provinceId => $citys) {
            $str .= $regionTree[$provinceId]['name'];
            if (count($citys) !== count($regionTree[$provinceId]['city'])) {
                $cityStr = '';
                foreach ($citys as $cityId)
                    $cityStr .= $regionTree[$provinceId]['city'][$cityId]['name'];
                $str .= ' (<span class="am-link-muted">' . mb_substr($cityStr, 0, -1, 'utf-8') . '</span>)';
            }
            $str .= '、';
        }
        return mb_substr($str, 0, -1, 'utf-8');
    }


    public  function getInfo($pk) {
        if (!$pk) {
            return null;
        }
        $name = "mall_delivery_info_1616060509_{$pk}";
        $data = cache($name);
        if (!$data) {
            $mypk = $this->get_pk();
            if (!$mypk) {
                return null;
            }
            $data = Db::name($this->name)->find($pk);
            if ($data) {
                $data['rule'] = Db::name('mall_delivery_rule')->where(array('delivery_id' => $pk))->select()->toArray();
                foreach ($data['rule'] as &$v) {
                    $v['region_content'] = $this->getRegionContentAttr($v);
                }
            }
            cache($name, $data);
        }
        return $data;
    }


    //获取字典
    public function getLan($field=''){
        $lans = array();
        if($field==''){
            return $lans;
        }
        return $lans[$field];
    }

    public  function getOption($name = 'name') {
        $as = $this->getAll();
        $this->open_name=$name;
        $names = array_reduce($as, function($v,$w){ $v[$w[ $this->get_pk()]]=$w[$this->open_name ];return $v; });
        return $names;
    }


    public  function clear($pk = ''){
        $name = "mall_delivery_1616060509";
        cache($name,null);
        if ($pk) {
            $name = "mall_delivery_info_1616060509_{$pk}";
            cache($name, null);
        }
    }


    public  function setVal($id,$key,$val){
        $pk = $this->get_pk();
        if($pk){
           return $this->where(array($pk=>$id))->save([$key=>$val]);
        }

    }

    public  function getVal($id,$key,$cache=true){
        $pk = $this->get_pk();
        if($pk){
            return $this->where(array($pk=>$id))->cache($cache)->value($key);
        }
    }




    /**
    * 搜索框
    * @return type
    */
    public  function searchArr() {
        return [
            'delivery_id'=>'1',
'name'=>'1',
'method'=>'1',
'sort'=>'1',

        ];
    }

    /**
     * 列名
     * @return type
     */
    public  function attributeLabels() {
        return ['delivery_id'=>'模板id',
'name'=>'模板名称',
'method'=>'计费方式(1按件数 2按重量)',
'sort'=>'排序方式(数字越小越靠前)',
];
    }
     /**
     * 规则
     * @return type
     */
    public  function rules() {
        return [
            'rule'=>[
            'name|模板名称'=>["require","max"=>255,],
'method|计费方式(1按件数 2按重量)'=>["require","number",],
'sort|排序方式(数字越小越靠前)'=>["require","number",],

            ],
            'message'=>[]
        ];

    }

    /**
     * 自增的字段
     * @return type
     */
    public function getAutoField() {
        return "delivery_id";
    }

     /**
     * 默认值
     * @return type
     */
    public function defaultValue() {
        return ['delivery_id'=>'',
'method'=>'1',
'sort'=>'0',
];
    }
     /**
     * 要转成json的字段
     * @return type
     */
    public function jsonAttr(){
        return [];
    }

    /**
     * 字段类型
     * @return type
     * #fieldType#
     */
    public function fieldType() {
        return [];
    }


}
