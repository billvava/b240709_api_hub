<?php

namespace app\mall\model;

use think\facade\Db;
use think\Model;

/**
 * 代码自动生成所需参数
 * moudleName:模块名称
 * tableName:表名
 * modelName:模型名称
 * */
class GoodsCategory extends Model
{

    protected $name = 'mall_goods_category';
    protected $pk = 'category_id';

    //将上级分类的商品改成他的

    public static function onAfterInsert($model)
    {

        //自动创建菜单
        $data = $model->toArray();

        if ($data['pid'] > 0 && $data['category_id']) {
            Db::name('mall_goods')->where('category_id', $data['pid'])->save(['category_id' => $data['category_id']]);
            self::updateGoodsNum();
        }
    }

    /**
     * 获取分类的
     */
    public function getOption()
    {
        return $this->cache(true)->column('name', 'category_id');
    }

    /**
     * 删除子类和相关表
     * @param type $category_id
     * @return boolean
     */
    public function deleteRela($category_id)
    {
        $cates = $this->getChildIds($category_id);
        if (count($cates) > 0) {
            $this->where('category_id', 'in', $cates)->delete();
            return true;
        }
        return false;
    }

    /**
     * 获取递归的option html
     * @return type
     */
    public function getAllOptionHtml($category_id = null)
    {
        $tree = $this->getTreeCache();
        return $this->getAllOptionHtml_($tree, $category_id);
    }

    private function getAllOptionHtml_($tree, $category_id = null)
    {
        foreach ($tree as $v) {
            $str = str_repeat("----", $v['lev'] - 1);
            $check = '';
            if ($category_id == $v['category_id']) {
                $check = "selected=''";
            }
            $html .= "<option value='{$v['category_id']}' pid='{$v['pid']}' {$check} category_id='{$v['category_id']}'>{$str}{$v['name']}</option>";
            if (!empty($v['children'])) {
                $html .= $this->getAllOptionHtml_($v['children'], $category_id);
            }
        }
        return $html;
    }

    /**
     * 递归获取
     * @return type
     */
    public function categoryTreeeTree()
    {
        $lists = $this->select()->toArray();
        return $this->cateToTree($lists, 0, '|-----', 1);
    }

    public function cateToTree($lists, $pid = 0, $html = '|-----', $level = 1, $clear = true)
    {
        static $tree = [];
        if ($clear)
            $tree = [];
        foreach ($lists as $k => $v) {
            if ($v['pid'] == $pid) {
                $v['html'] = str_repeat($html, $level);
                $tree[] = $v;
                unset($lists[$k]);
                $this->cateToTree($lists, $v['category_id'], $html, $level + 1, false);
            }
        }
        return $tree;
    }

    /**
     * 列名
     * @return type
     */
    public function attributeLabels()
    {
        return array(
            'category_id' => '类目ID',
            'name' => '类目名称',
            'english' => '英文名称',
            'desc' => '类目描述',
            'sort' => '排序',
        );
    }

    /**
     * 规则
     * @return type
     */
    public function rules()
    {

    }

    /**
     * 自增的字段
     * @return type
     */
    public function getAutoField()
    {
        return "category_id";
    }

    /**
     * 默认值
     * @return type
     */
    public function defaultValue()
    {
        return array(
            'category_id' => '',
            'sort' => '99',
            'is_show' => '1',
            'is_nav' => '1',
            'pid' => '0',
        );
    }

    /**
     * 检测是否为整数
     * @param type $str
     * @return boolean
     */
    function checkIsInt($str)
    {
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

    public function getDingji($pid)
    {
        if (!in_array($pid, [1, 2])) {
            $pid = $this->where('category_id', $pid)->value('pid');
            if (!in_array($pid, [1, 2])) {
                $pid = $this->where('category_id', $pid)->value('pid');
                if (!in_array($pid, [1, 2])) {
                    $pid = $this->where('category_id', $pid)->value('pid');
                }
            }
        }
        return $pid;
    }

    /**
     * 获取下级的所有id,包含他本身
     * @param type $category_id
     * @param type $arr
     * @return type
     */
    public function getChildIds($category_id, $arr = array())
    {
        $arr[] = $category_id;
        $ids = $this->where('pid', $category_id)->column('category_id');
        foreach ($ids as $value) {
            $arr[] = $value;
            $arr = array_merge($arr, $this->getChildIds($value));
        }
        return array_unique($arr);
    }

    /**
     * 获取下级的所有id,包含他本身 缓存
     */
    public function getChildIdsCache($category_id)
    {
        $name = "getCategoryChildIdsCache{$category_id}";
        if (!$data = cache($name)) {
            $data = $this->getChildIds($category_id);
            cache($name, $data);
        }
        return $data;
    }

    //获取缓存
    public function getTreeCache($where = [])
    {
        $name = "getCategoryTreeCache";
        if (!$data = cache($name)) {
            $data = $this->getTree($where);
            cache($name, $data);
        }
        return $data;
    }

    public function clear()
    {
        $name = "getCategoryTreeCache";
        cache($name, null);
    }

    /**
     * 获取树
     * @return type
     */
    public function getTree($where = [])
    {
        $data = $this->where($where)->select()->toArray();
        $data = $this->arrToTree($data, 0);
        return $data;
    }

    /**
     * 数组转树
     * @param type $tree
     * @param type $rootId
     * @return type
     */
    public function arrToTree($tree, $rootId = 0, $lev = 1)
    {
        $return = array();
        foreach ($tree as $leaf) {
            if ($leaf['pid'] == $rootId) {
                if ($leaf['pid'] == 0) {
                    $lev = 1;
                }
                $leaf['lev'] = $lev;
                foreach ($tree as $subleaf) {
                    if ($subleaf['pid'] == $leaf['category_id']) {
                        $leaf['children'] = $this->arrToTree($tree, $leaf['category_id'], $lev + 1);
                        break;
                    }
                }
                $return[] = $leaf;
            }
        }
        return $return;
    }

    /**
     * 树转html
     * @param type $tree
     */
    public function treeToHtml($tree)
    {
        echo '<ul>';
        foreach ($tree as $leaf) {
            echo '<li>' . $leaf['name'];
            if (!empty($leaf['children']))
                $this->treeToHtml($leaf['children']);
            echo '</li>';
        }
        echo '</ul>';
    }

    //获取推荐的栏目
    public function getNavCate()
    {
        $w[] = ['is_nav', '=', 1];
        $w[] = ['is_show', '=', 1];
        return $this->where($w)->order("sort asc")->cache(true)->select()->toArray();
    }

    //更新商品数量
    public function updateGoodsNum()
    {
        $data = Db::name('mall_goods')->field(array('count(goods_id) as s', 'category_id'))->group('category_id')->select()->toArray();
        $this->where("goods_num", '>', 0)->save(['goods_num' => 0]);
        foreach ($data as $v) {
            $this->where('category_id', $v['category_id'])->save(['goods_num' => $v['s']]);
        }
    }

    /**
     * 转移商品
     */
    public function move_goods($old_id, $new_id)
    {
        Db::name('mall_goods')->where('category_id', $old_id)->save(array('category_id' => $new_id));
        $this->updateGoodsNum();
    }

    /**
     * 获取栏目信息
     * @param type $category_id
     * @param type $is_child
     * @param type $is_cache
     */
    public function get_cate_info($category_id, $is_child = false, $is_cache = true)
    {
        $name = "get_mall_cate_info_{$category_id}_{$is_child}";

        $data = cache($name);

        if ($data === false || $is_cache == false) {
            $w[] = ['is_nav', '=', 1];
            $w[] = ['category_id', '=', $category_id];
            $data = $this->where($w)->find();
            if ($data) {
                $data['thumb'] = get_img_url($data['thumb']);
            }
            if ($is_child == true) {
                $data['child'] = $this->get_child_cate($category_id);
            }
            cache($name, $data);
        }
        return $data;
    }

    //递归查询
    public function get_child_cate($pid, $where = array('is_nav' => 1, 'is_show' => 1))
    {
        if (!$pid) {
            return null;
        }
        $a = array('pid' => $pid);
        $a = array_merge($a, $where);
        $result = $this->where($a)->order('sort asc')->select()->toArray();
        if ($result) {
            foreach ($result as &$value) {
                $value['thumb'] = get_img_url($value['thumb']);
                $value['child'] = $this->get_child_cate($value['category_id'], $where);
            }
        }
        return $result;
    }

    /**
     * 获取下级
     */
    public function getChild($pid = 0)
    {
        return $this->where(array('pid' => $pid, 'is_show' => 1))->order('sort asc')->cache(true)->select()->toArray();
    }

}
