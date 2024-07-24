<?php

declare (strict_types = 1);

namespace app\com\model;

use think\facade\Db;
use think\Model;

/**
 * @mixin think\Model
 */
class HelpItem extends Model {

    protected $name = 'help_item';

    public function dbName() {
        return $this->name;
    }

    public function get_pk() {
        return "id";
    }

    public function handle($v) {
        $v['content_str'] = '';
        if ($v['content']) {
            $preg = '/<img.*?src=[\"|\']?(.*?)[\"|\']?\s.*?>/i';
            $v['html'] = htmlspecialchars_decode($v['content'], ENT_QUOTES);
            preg_match_all($preg, $v['html'], $allImg);
            foreach ($allImg[1] as $k => $av) {
                $new_str = "<img src='" . get_img_url($av) . "' style='width:100%; display:block;float:left;height:auto;' />";
                $v['html'] = str_replace($allImg[0][$k], $new_str, $v['html']);
            }
            $v['content_str'] = strip_tags($v['html']);

        }
        $v['title'] = $v['name'];
        $v['h5_url'] = C('wapurl') . '/comapi/HelpShow/index?id=' . $v['id'];
        $v['thumb'] = get_img_url($v['thumb']);
        return $v;
    }

    public function getList($where, $page = 1, $num = 10) {
        $order = "sort asc,id desc";
        $data = Db::name($this->name)->where($where)->page($page, $num)->order($order)->cache(true)->select()->toArray();
        foreach ($data as &$v) {
            $v = $this->handle($v);
        }
        return $data;
    }

    public function get_data($where = array(), $num = 10) {
        $count = Db::name($this->name)->where($where)->count();
        tool()->classs('Page');
        $Page = new \Page($count, 20);
        $order = "sort asc,id desc";
        $data['count'] = $count;
        $data['list'] = Db::name($this->name)->where($where)->limit($Page->firstRow, $Page->listRows)->order($order)->select()->toArray();
        $data['page'] = $Page->show();
        return $data;
    }

    public function getAll($where = array()) {
        $pre = md5(json_encode($where));
        $name = "help_item_1626256731_{$pre}";
        $data = cache($name);
        if (!$data) {
            $order = "sort asc,id desc";
            $data = Db::name($this->name)->where($where)->order($order)->select()->toArray();
            cache($name, $data);
        }
        return $data;
    }

    public function itemAll() {
        $data = $this->getAll();
        $list = array();
        $pk = $this->get_pk();
        foreach ($data as $v) {
            $list[] = array(
                'val' => $v[$pk], 'name' => $v['name']
            );
        }
        return $list;
    }

    public function getInfo($pk) {
        if (!$pk) {
            return null;
        }
        $name = "help_item_info_1626256731_{$pk}";
        $data = cache($name);
        if (!$data) {

            $where = [];
            if (is_numeric($pk)) {
                $where[] = ['id', '=', $pk];
            } else {
                $where[] = ['token', '=', $pk];
            }
            $data = Db::name($this->name)->where($where)->find();
            if ($data) {
                $data = $this->handle($data);
            }
            cache($name, $data);
        }
        return $data;
    }

    //获取字典
    public function getLan($field = '') {
        $lans = array('status' => array('1' => '显示', '0' => '隐藏',),);
        if ($field == '') {
            return $lans;
        }
        return $lans[$field];
    }

    public function getOption($name = 'name') {
        $as = $this->getAll();
        $this->open_name = $name;
        $names = array_reduce($as, function($v, $w) {
            $v[$w[$this->get_pk()]] = $w[$this->open_name];
            return $v;
        });
        return $names;
    }

    public function clear($pk = '') {
        $name = "help_item_1626256731";
        cache($name, null);
        if ($pk) {
            $name = "help_item_info_1626256731_{$pk}";
            cache($name, null);
        }
    }

    public function setVal($id, $key, $val) {
        $pk = $this->get_pk();
        if ($pk) {
            return $this->where(array($pk => $id))->save([$key => $val]);
        }
    }

    public function getVal($id, $key, $cache = true) {
        $pk = $this->get_pk();
        if ($pk) {
            return $this->where(array($pk => $id))->cache($cache)->value($key);
        }
    }

    /**
     * 搜索框
     * @return type
     */
    public function searchArr() {
        return [
            'id' => '1',
            'token' => '1',
            'name' => '1',
            'content' => '1',
            'status' => '1',
            'sort' => '1',
            'cate_id' => '1',
        ];
    }

    /**
     * 列名
     * @return type
     */
    public function attributeLabels() {
        return ['id' => '编号',
            'token' => '标识',
            'name' => '标题',
            'content' => '内容',
            'status' => '状态|1=显示,0=隐藏',
            'sort' => '排序',
            'cate_id' => '分类',
        ];
    }

    /**
     * 规则
     * @return type
     */
    public function rules() {
        return [
            'rule' => [
                'token|标识' => ["max" => 25,],
                'name|标题' => ["max" => 255,],
                'content|内容' => [],
                'status|状态|1=显示,0=隐藏' => ["integer",],
                'sort|排序' => ["integer",],
                'cate_id|分类' => ["integer",],
            ],
            'message' => []
        ];
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
        return ['id' => '',
            'token' => '',
            'name' => '',
            'content' => '',
            'status' => '1',
            'sort' => '10',
            'cate_id' => '',
        ];
    }

    /**
     * 要转成json的字段
     * @return type
     */
    public function jsonAttr() {
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
