<?php

declare (strict_types = 1);

namespace app\gii\controller;

use app\admin\logic\Node;
use app\admin\model\AdminNav;
use app\admin\model\AdminRole;
use app\gii\logic\Body;
use app\gii\logic\Item;
use app\gii\logic\Search;
use app\gii\model\FileUtil;
use app\gii\model\O;
use think\App;
use think\facade\View;

class Crud extends Common {

    public $model;
    public $rulesStr;
    public $indexHeadStr;
    public $searchStr;
    public $searchArr;
    public $defaultValue;
    public $itemStr;
    public $getLanStr;
    public $autoField;
    public $attributeLabels;
    public $tableTitle;
    public $moudleName;
    public $modelNamelName; //模型名称
    public $cName;
    public $viewName;
    public $theme;
    public $thiscontrollerDir;
    public $modelDir;
    public $viewDir;
    public $jsonAttr;
    public $dateAttr;

    public function __construct(App $app) {
        parent::__construct($app);
        $this->model = new O();
    }

    public function index() {
        $this->title = "增删改查";
        return $this->display();
    }

    /**
     * 主创建程序
     */
    public function add() {
        if (request()->isPost()) {

            $tableInfo = $this->model->showTables($this->in['tableName']);
            $tableInfo = $this->arraykeyToLower($tableInfo);
            if (!$tableInfo) {
                die_echo("表{$this->in['tableName']}不存在", 1);
            }
            $dirData = FileUtil::getDirList(base_path());

            if (!in_array($this->in['moduleName'], $dirData)) {
                die_echo("模块{$this->in['moduleName']}不存在", 1);
            }
            //表单参数验证
            $this->check_fzbd();
            //模板主题文件判断
            $this->theme = $this->in['theme'] ? $this->in['theme'] : "default_tpl";

            $this->themePath = app_path() . 'assets/' . $this->theme . '/';
            if (!is_dir($this->themePath)) {
                die_echo("主题目录{$this->themePath}不存在", 1);
            }
            $all_tpl = array('item', 'controller', 'index', 'model');
            $is_die = 0;
            foreach ($all_tpl as $value) {
                $file = $this->themePath . "{$value}.tpl";
                if (!file_exists($file)) {
                    error_echo("模板文件{$file}不存在");
                    $is_die = 1;
                }
            }
            if ($is_die == 1) {
                die_echo('');
            }

            //处理名称
            $DB_PREFIX = DB_PREFIX;
            $tableInfo['table_name'] = str_replace($DB_PREFIX, '', $tableInfo['table_name']);
            $this->tableTitle = $tableInfo['table_comment'] ? $tableInfo['table_comment'] : $tableInfo['table_name']; //表注释
            $this->moudleName = $this->in['moduleName']; //模块名
            $this->modelName = $this->in['modelName'] ? tableNameToModelName($this->in['modelName']) : tableNameToModelName($tableInfo['table_name']); //模型名称
            $this->cName = $this->in['cName'] ? tableNameToModelName($this->in['cName']) : tableNameToModelName($tableInfo['table_name']); //控制器名称
            $this->viewName = $this->in['viewName'] ? $this->in['viewName'] : $tableInfo['table_name']; //视图文件夹名称
            //创建目录

            $this->controllerDir = base_path() . "{$this->moudleName}/controller/";
            $this->modelDir = base_path() . "{$this->moudleName}/model/";
            $this->viewDir = base_path() . "{$this->moudleName}/view/{$this->viewName}/";
            if (!is_dir($this->controllerDir)) {
                mkdir($this->controllerDir, 0775, true);
            }
            if (!is_dir($this->modelDir)) {
                mkdir($this->modelDir, 0775, true);
            }
            if (!is_dir($this->viewDir)) {
                mkdir($this->viewDir, 0775, true);
            }

            //检测是否存在同样的控制器和模型文件
            $newControllerPath = $this->controllerDir . "{$this->cName}.php";

            $newModelPath = $this->modelDir . "{$this->modelName}.php";
            if (file_exists($newControllerPath) && $this->in['is_cover'] == 0) {
                die_echo("控制器文件{$newControllerPath}已存在，为避免覆盖错误，请手动检查处理");
            }
            if (file_exists($newModelPath) && $this->in['is_cover'] == 0) {
                die_echo("模型文件{$newModelPath}已存在，为避免覆盖错误，请手动检查处理");
            }
            //引入控制器模板
            $controllerStr = file_get_contents(app_path() . 'assets/' . $this->theme . '/controller.tpl');
            $controllerStr = str_replace(
                    array('#moudleName#', '#tableTitle#', '#modelName#', '#controllerName#', '#is_search#', '#is_xls#', '#is_add#', '#is_edit#', '#is_del#', '#sort_rule#'), array($this->moudleName, $this->tableTitle, $this->modelName, $this->cName, $this->in['is_search'], $this->in['is_xls'], $this->in['is_add'], $this->in['is_edit'], $this->in['is_del'], $this->in['sort_rule']), $controllerStr
            );

            //引入模型模板
            $modelStr = file_get_contents(app_path() . 'assets/' . $this->theme . '/model.tpl');
            //获取表的字段信息
            $data = $this->model->getTableInfoArray($this->in['tableName']);
            $data = $this->arraykeyToLower($data);
//            p($data);
            //生成Index模板的字符串
            $this->indexBodyStr = '';
            //组合字段的字符串


            foreach ($data as $key => $value) {

                $column_comment = $value['column_comment'] ? $value['column_comment'] : $value['column_name'];
                if (stripos($value['extra'], 'auto_increment') !== false) {
                    $this->autoField = $value['column_name'];
                }
                if (strpos($column_comment, '|') !== false) {
                    $column_comment_tmp = explode('|', $column_comment);
                    $column_comment = $column_comment_tmp[0];
                }
                $value['column_comment'] = $column_comment;
                $this->merge_rule($value);
                $this->default_merge($value);
                $this->search_merge($value, $key);
                $this->attr_merge($value);
                $this->item_merge($value);
                $this->fieldlan_merge($value);
            }


            $this->fzdb_merge();
            $cacheName = time();
            $modelStr = str_replace(
                    array('#moudleName#', '#tableName#', '#modelName#', '#attributeLabels#', '#autoField#', '#rules#', '#defaultValue#', '#cacheName#', '#sort_rule#', '#jsonAttr#', '#getLanStr#', '#searchArr#', '#dateAttr#'), array($this->moudleName, $tableInfo['table_name'], $this->modelName, $this->attributeLabels, $this->autoField, $this->rulesStr, $this->defaultValue, $cacheName, $this->in['sort_rule'], $this->jsonAttr, $this->getLanStr, $this->searchArr, $this->dateAttr), $modelStr
            );
            //创建文件
            $res = writeFile($newControllerPath, $controllerStr);
            arr_echo($res, 1);
            $res = writeFile($newModelPath, $modelStr);
            arr_echo($res, 1);
            $this->create_view();


            if ($this->in['is_create_auth'] == 1) {
                $this->create_auth();
            }
            if ($this->moudleName == 'admin') {
                $this->moudleName = C('admin_app');
            }
            $url = url("{$this->moudleName}/{$this->cName}/index");
            success_echo("<a href='{$url}' target='_blank'>点击链接打开</a>", 1);
        }
    }

    /**
     * 复杂表单的按钮
     */
    public function fzbd_btn() {
        $all_fields = cache("tb_{$this->in['tb']}");
        if (!$all_fields) {
            die;
        }
        View::assign('all_fields', $all_fields);
        return $this->display();
    }

    //加载模型文件
    public function load_model_file() {
        $app = $this->in['app'];
        $html = "<option value='' >请选择</option>";
        tool()->classs('FileUtil');
        $FileUtil = new \FileUtil();
        $dir = APP_PATH . $app . '/model/';
        $ms = [];
        if (is_dir($dir)) {
            $ms = $FileUtil->getOnleFileList($dir);
        }
        foreach ($ms as $v) {
            $html .= "<option value='{$v}' >{$v}</option>";
        }
        return $html;
    }

    /**
     * 生成视图
     */
    private function create_view() {
        //创建视图文件
        $arr = array('item', 'index');
        foreach ($arr as $v) {
            $path = $this->viewDir . "{$v}.php";
            if (file_exists($path) && $this->in['is_cover'] == 0) {
                error_echo("文件{$path}已存在，创建失败");
                continue;
            }
            $str = file_get_contents(app_path() . "assets/{$this->theme}/{$v}.tpl");
            $str = str_replace(
                    array('#searchStr#', '#indexHeadStr#', '#indexBodyStr#', '#itemStr#'), array($this->searchStr, $this->indexHeadStr, $this->indexBodyStr, $this->itemStr), $str
            );
            $res = writeFile($path, $str);
            arr_echo($res);
        }
    }

    /**
     * 创建菜单和权限
     */
    private function create_auth() {
        if ($this->in['is_create_auth'] == 1) {
            (new AdminNav())->up_all_node();
        }
    }

    /**
     * 生成验证规则
     * @param type $value
     */
    private function merge_rule($value) {
        $floatArr = array('double', 'float', 'decimal', 'numeric');
        $strArr = array('varchar', 'char');
        $intArr = array('int', 'tinyint', 'smallint', 'mediumint', 'integer', 'bigint');

        if (stripos($value['extra'], 'auto_increment') !== false) {
            return;
        }
        $rule = '';


        if ($value['is_nullable'] == 'NO') {
            $rule .= '"require",';
        }
        //字符串
        if (in_array($value['data_type'], $strArr)) {
            $rule .= '"max"=>' . $value['character_maximum_length'] . ',';
        }
        //数字
        if (in_array($value['data_type'], $intArr)) {
            if (stripos($value['column_type'], 'unsigned') !== false) {
                $rule .= '"number",';
            } else {
                $rule .= '"integer",';
            }
        }
        //小数
        if (in_array($value['data_type'], $floatArr)) {
            if (stripos($value['column_type'], 'unsigned') !== false) {
                $rule .= '"float",';
                $rule .= '"egt"=>0,';
            } else {
                $rule .= '"float",';
            }
        }

        $this->rulesStr .= "'{$value['column_name']}|{$value['column_comment']}'=>[{$rule}]," . PHP_EOL;
    }

    private function attr_merge($value) {

        $this->attributeLabels .= "'{$value['column_name']}'=>'{$value['column_comment']}'," . PHP_EOL;
    }

    //生成字典
    private function fieldlan_merge($value) {
        $fieldparam = $this->in["fieldparam_{$value['column_name']}"];
        if ($fieldparam) {
            $ps = explode(',', $fieldparam);
            $html = "'{$value['column_name']}' => array(";
            foreach ($ps as $v) {
                $vv = explode('=', $v);
                $html .= "'{$vv[1]}'=>'{$vv[0]}',";
            }
            $html .= "),";
            $this->getLanStr .= $html;
        }
    }

    private function fzdb_merge() {
        $itemLogic = new Item();
        foreach ($this->in as $k => $v) {
            if (strpos($k, 'fzbd_') !== false) {
                $kname = str_replace('fzbd_', '', $k);
                foreach ($v as $kv => $vv) {
                    $this->itemStr .= $itemLogic->returnStr(
                            array(
                                'name' => $vv['name'],
                                'type' => $kname,
                                'field' => $vv['field'],
                                'lat_name' => $vv['lat'],
                                'lng_name' => $vv['lng'],
                                'province_name' => $vv['province'],
                                'city_name' => $vv['city'],
                                'country_name' => $vv['country'],
                                'app' => $vv['app'],
                                'model' => $vv['model'],
                            )
                    );
                }
            }
        }
    }

    private function item_merge($value) {
        $itemLogic = new Item();
        $this->itemStr .= $itemLogic->returnStr(
                array('field' => $value['column_name'],
                    'name' => $this->in['fieldname_' . $value['column_name']],
                    'type' => $this->in["fieldtype_{$value['column_name']}"],
                    'val' => $this->in["fieldval_{$value['column_name']}"],
                    'param' => $this->in["fieldparam_{$value['column_name']}"],
                    'other' => array(
                        'source' => $this->in["source_{$value['column_name']}"],
                        'guanlian' => $this->in["guanlian_{$value['column_name']}"],
                        'callback' => $this->in["callback_{$value['column_name']}"],
                        'pguanli' => $this->in["pguanli_{$value['column_name']}"],
                    )
                )
        );
        if ($this->in["fieldtype_{$value['column_name']}"] == 'images') {
            $this->jsonAttr .= "'{$value['column_name']}',";
        }


        if (in_array($this->in["fieldtype_{$value['column_name']}"], ['ftime', 'fdate', 'datetime'])) {
            $this->dateAttr .= "'{$value['column_name']}',";
        }
    }

    private function default_merge($value) {
        if ($value['column_default'] !== '') {
            $this->defaultValue .= "'{$value['column_name']}'=>'{$value['column_default']}'," . PHP_EOL;
        }
    }

    private function search_merge($value, $key) {

        $auto_search_type = lang('auto_seach_type')['form_input'];

        $auto_search_type2 = lang('auto_seach_type')['form_select'];

        //in_array($value['column_name'], $this->in['index_fields'])
        $type = $this->in["searchtype_{$value['column_name']}"];
//        foreach ($auto_search_type as &$tv) {
//            if (strpos($tv, '*') !== false) {
//                $tv = str_replace('*', '', $tv);
//                if (strpos($value['column_name'], $tv) !== false && $value['column_name'] != $tv) {
//                    $auto_search_type[] = $value['column_name'];
//                }
//            }
//        }
//        || in_array($value['column_name'], $auto_search_type)
        $searchstatus = $this->in["searchstatus_{$value['column_name']}"];

        $showfield = $this->in["showfield_{$value['column_name']}"];
        if (in_array($type, ['selec', 'rangedate']) || $searchstatus == 1) {
            $class = 'fix-border';
            if ($key == 0) {
                $class = '';
            }
            $searchLogic = new Search();
            $this->searchArr .= "'{$value['column_name']}'=>'{$this->in["search_{$value['column_name']}"]}'," . PHP_EOL;

            if ($searchstatus) {
                $this->searchStr .= $searchLogic->returnStr(
                        array(
                            'field' => $value['column_name'],
                            'name' => $this->in['fieldname_' . $value['column_name']],
                            'type' => $type,
                            'param' => $this->in["searchparam_{$value['column_name']}"],
                            'other' => array(
                                'source' => $this->in["searchsource_{$value['column_name']}"],
                                'guanlian' => $this->in["searchguanlian_{$value['column_name']}"],
                                'callback' => $this->in["searchcallback_{$value['column_name']}"],
                            )
                        )
                );
            }
        }
        if ($showfield != 'wu') {

            $this->indexHeadStr .= "<th>{$this->in['fieldname_' . $value['column_name']]}</th>" . PHP_EOL;
            $botyLogic = new Body();
            $this->indexBodyStr .= $botyLogic->returnStr(
                    array(
                        'type' => $showfield,
                        'name' => $value['column_name'],
                    )
            );
        }


        //用户搜索关联下拉框
        if (in_array($value['column_name'], $auto_search_type2)) {
            $class = 'fix-border';
            if ($key == 0) {
                $class = '';
            }
            $searchLogic = new Search();
            $this->searchArr .= "'{$value['column_name']}'=>'{$this->in["search_{$value['column_name']}"]}'," . PHP_EOL;
            if ($this->in["search_{$value['column_name']}"]) {
                $this->searchStr .= $searchLogic->returnStr(
                        array(
                            'field' => $value['column_name'],
                            'name' => $this->in['fieldname_' . $value['column_name']],
                            'type' => 'user_selec',
                            'param' => $this->in["searchparam_{$value['column_name']}"],
                            'other' => array(
                                'source' => $this->in["searchsource_{$value['column_name']}"],
                                'guanlian' => $this->in["searchguanlian_{$value['column_name']}"],
                                'callback' => $this->in["searchcallback_{$value['column_name']}"],
                            )
                        )
                );
            }

//            $this->indexHeadStr .= "<th>{$this->in['fieldname_' . $value['column_name']]}</th>" . PHP_EOL;
//            $botyLogic = new Body();
//            $this->indexBodyStr .= $botyLogic->returnStr(
//                    array(
//                        'type' => $this->in["showfield_{$value['column_name']}"],
//                        'name' => $value['column_name'],
//                    )
//            );
        }
    }

    /**
     * 加载验证
     */
    public function check_crud() {


        $c = config('database.connections.mysql');
        $DB_PREFIX = DB_PREFIX;
        $DB_NAME = $c['database'];
        if ($this->in['name'] == 'tableName') {

            $data = $this->model->getTableName($this->in['val']);
            if (!$data) {
                $this->error('该表不存在');
            }
            $fields = $this->model->getTableInfoArray($this->in['val']);
            $fields = $this->arraykeyToLower($fields);

            $sort = '';
            $fast_fields = array();
            $all_fields = array();
            foreach ($fields as $v) {
                $form_type = $this->get_form_type($v);
                if ($v['column_name'] == 'sort') {
                    $has_sort = "sort asc,";
                }

                if ($v['column_key'] == 'PRI') {
                    $sort = "{$v['column_name']} desc";
                } else {
                    $fast_fields[] = array(
                        'name' => $form_type['name'],
                        'field' => $v['column_name'],
                    );
                }
//                [data_type] => datetime
                $is_hide = 0;
                if ($v['extra'] == 'auto_increment') {
                    $is_hide = 1;
                }



                $all_fields[] = array(
                    'name' => $form_type['name'],
                    'field' => $v['column_name'],
                    'default' => $v['column_default'],
                    'is_hide' => $is_hide,
                    'data_type' => $v['data_type'],
                    'type' => $form_type['type'],
                    'param' => $form_type['param'],
                    'edit_type' => $form_type['edit_type'],
                    'search_type' => $form_type['search_type'],
                    'search_status' => $form_type['search_status'],
                );
            }
//            p($all_fields);

            if ($has_sort) {
                $sort = trim("{$has_sort}{$sort}", ",");
            }
            View::assign('all_fields', $all_fields);
            cache("tb_{$this->in['val']}", $all_fields);

            json_msg(1, null, null, array(
                'field' => $fields,
                'sort' => $sort,
                'fast_fields' => $fast_fields,
                'all_fields' => $all_fields,
                'sel_html' => View::fetch('sel'),
                'fast_html' => View::fetch('fast'),
            ));
        } elseif ($this->in['name'] == 'moduleName') {
            $dirData = FileUtil::getDirList(base_path());
//            $this->in['val'] = ucfirst($this->in['val']);
            if (!in_array($this->in['val'], $dirData)) {
                $this->error('该模块不存在');
            }
        }
    }

    public static function arraykeyToLower($data = array()) {
        if (!empty($data)) {
            //一维数组
            if (count($data) == count($data, 1)) {
                $data = array_change_key_case($data, CASE_LOWER);
            } else {    //二维数组
                foreach ($data as $key => $value) {
                    $data[$key] = array_change_key_case($value, CASE_LOWER);
                }
            }
        }
        return $data;
    }

    private function get_form_type($item) {
        $field = $item['column_name'];
        $auto_field_type = lang('auto_field_type');

        $auto_search_type = lang('auto_seach_type')['form_input'];

        $auto_search_form_select = lang('auto_seach_type')['form_select'];
        $arr = array(
            'type' => 'input',
            'default' => '',
            'param' => '',
            'name' => '',
            'edit_type' => 'wu',
            'search_type' => '',
            'search_status' => 0,
        );
        foreach ($auto_search_type as &$tv) {
            if (strpos($tv, '*') !== false) {
                $tv = str_replace('*', '', $tv);
                if (strpos($field, $tv) !== false && $field != $tv) {
                    $auto_search_type[] = $field;
                }
            }
        }
        foreach ($auto_search_form_select as &$tv2) {
            if (strpos($tv2, '*') !== false) {
                $tv2 = str_replace('*', '', $tv2);
                if (strpos($field, $tv2) !== false && $field != $tv2) {
                    $auto_search_form_select[] = $field;
                }
            }
        }


        if ($item['data_type'] == 'datetime') {
            $arr['type'] = 'datetime';
            $arr['search_type'] = 'rangedate';
        }
        if ($item['data_type'] == 'date') {
            $arr['type'] = 'fdate';
        }
        if ($item['data_type'] == 'time') {
            $arr['type'] = 'ftime';
        }

        if (strpos($field, 'is_') !== false || $field == 'status') {
            $arr['edit_type'] = 'fast_check';
        } else if (strpos($item['column_comment'], '|') !== false) {
            $arr['edit_type'] = 'lan';
        }


        if (strpos($field, 'is') !== false || strpos($field, 'status') !== false || strpos($item['column_comment'], '|') !== false) {
            $field = 'status';
        }



        if (in_array($field, ['sort'])) {
            $arr['edit_type'] = 'text';
        }
        if (in_array($field, ['create_time'])) {
            $arr['type'] = 'hide';
        }
        foreach ($auto_field_type as $k => $v) {
            $arr['name'] = $item['column_comment'] ? $item['column_comment'] : $item['column_name'];

            if (in_array($field, $v)) {
                $arr['type'] = $k;
                if ($k == 'images' || $k == 'thumb') {
                    $arr['edit_type'] = 'images';
                }

                //is_*
                if ($k == 'radio') {
                    $arr['search_type'] = 'selec';
                    $item['column_comment'] = str_replace('，', ',', $item['column_comment']);
                    $param_str = '';
                    if (strpos($item['column_comment'], '|') !== false) {
                        $cv = explode('|', $item['column_comment']);
                        $arr['name'] = $cv[0];
                        $map = explode(',', $cv[1]);
                        foreach ($map as $mv) {
                            $marr = explode('=', $mv);
                            $param_str .= "{$marr[1]}={$marr[0]},";
                        }
                        $param_str = trim($param_str, ',');
                    } else {
                        $param_str = '是=1,否=0';
                    }
                    $arr['param'] = $param_str;
                }
                break;
            }
        }

        if (in_array($field, $auto_search_type)) {
            $arr['search_status'] = 1;
            $arr['edit_type'] = 'no';
        }
         if (in_array($field, $auto_search_form_select)) {
            $arr['search_status'] = 1;
        }
        
        return $arr;
    }

    private function check_fzbd() {
        foreach ($this->in as $k => $v) {
            if (strpos($k, 'fzbd_') !== false) {
                $kname = str_replace('fzbd_', '', $k);
                foreach ($v as $kv => $vv) {
                    foreach ($vv as $kv2 => $vv2) {
                        if (!$vv2) {
                            die_echo("复杂表单 {$kname} 参数 {$kv2} 不能为空");
                        }
                    }
                }
            }
        }
    }

}
