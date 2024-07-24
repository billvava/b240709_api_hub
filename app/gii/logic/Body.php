<?php

namespace app\gii\logic;

class Body {

    public $config;

    public function returnStr($config) {
        $this->config = array(
            'name' => $config['name'],
            'type' => $config['type'],
        );

        return $this->{$config['type']}() . PHP_EOL;
    }

    public function no() {
        return "<th><?php echo \$v['{$this->config['name']}']; ?></th>";
    }

    public function text() {
        return "<th><input type='text' class='layui-input w100' data-type='setval'    data-key='{\$pk}'  data-keyid='<?php echo \$v[\$pk]; ?>'  data-field='{$this->config['name']}'  data-url='{:url('set_val')}' value='<?php echo \$v['{$this->config['name']}']; ?>'/></th>";
    }

    public function lan() {
        return "<th><font style='<?php echo \$color[\$v['{$this->config['name']}']];  ?>'><?php  echo \$all_lan['{$this->config['name']}'][\$v['{$this->config['name']}']]; ?></font></th>";
    }

    public function images() {
        return "<th><?php  echo_img(\$v['{$this->config['name']}']); ?></th>";
    }

    public function fast_check() {
        return "<td><?php echo fast_check(array('key'=>\$pk,'keyid'=>\$v[\$pk],'field'=>'{$this->config['name']}','url'=>url('set_val'),'txt'=>'开启','check'=>\$v['{$this->config['name']}'])); ?></td>";
    }

}
