<?php

namespace app\gii\logic;

class Item {

    public $config;

    public function returnStr($config) {
        if (in_array($config['type'], ['area1', 'area2', 'area3'])) {
            $config['type'] = 'select_areas';
        }
        if ($config['model']) {
            $config['model'] = str_replace('.php', '', $config['model']);
        }
        
        $this->config = $config;
//        $this->config = array(
//            'field' => $config['field'],
//            'name' => $config['name'],
//            'type' => $config['type'],
//            'val' => $config['val'],
//            'param' => $config['param'],
//            'other' => $config['other'],
//        );



        return PHP_EOL . "<!--  【{$config['field']}】 start  !-->" . PHP_EOL . $this->{$config['type']}() . PHP_EOL . "<!--  【{$config['field']}】 end  !-->";
    }

    public function hide() {
        return;
    }

    public function hide_input() {
        return "<input type='hidden'   name='{$this->config['field']}' value='<?php echo \$info?\$info['{$this->config['field']}']:'{$this->config['val']}'; ?>'>";
    }

    public function input() {
        return "<?php echo form_input(array('field'=>'{$this->config['field']}','fname'=>'{$this->config['name']}','defaultvalue'=>\$info?\$info['{$this->config['field']}']:'{$this->config['val']}','col'=>4)); ?>";
    }

    public function sel_dot() {
        return "<?php echo  
sel_dot([
        'col'=>8,
        'name'=>'{$this->config['name']}',
        'lat_name'=>'{$this->config['lat_name']}',
        'lng_name'=>'{$this->config['lng_name']}',
        'lat_val'=>\$info['{$this->config['lat_name']}'],
        'lng_val'=>\$info['{$this->config['lng_name']}'],
        'msg'=>'',
        'other'=>'',
    ]); ?>";
    }

    public function select_data_goods() {
        return "<?php echo  select_data_goods([
        'col'=>4,
         'field'=>'{$this->config['field']}',
         'val'=>\$info['{$this->config['field']}'],
        'name'=>'{$this->config['name']}',
    ]); ?>";
    }

    public function select_data_user() {
        return "<?php echo  select_data_user([
        'col'=>4,
         'field'=>'{$this->config['field']}',
         'val'=>\$info['{$this->config['field']}'],
        'name'=>'{$this->config['name']}',
    ]); ?>";
    }

    public function select_areas() {
        return "<?php echo  select_areas([
        'col'=>4,
         'province_name'=>'{$this->config['province_name']}',
          'city_name'=>'{$this->config['city_name']}',
          'country_name'=>'{$this->config['country_name']}',
          'province_val'=>\$info['{$this->config['province_name']}'],
          'city_val'=>\$info['{$this->config['city_name']}'],
          'country_val'=>\$info['{$this->config['country_name']}'],
          'name'=>'{$this->config['name']}',
    ]); ?>";
    }
    
    public function select_model(){
      return  "<?php    echo select(array('field'=>'{$this->config['field']}','items'=>(new \app\\{$this->config['app']}\model\\{$this->config['model']}())->getOption(),'fname'=>'{$this->config['name']}','defaultvalue'=>\$info?\$info['{$this->config['field']}']:'{$this->config['val']}','col'=>4)); ?>";

    }

    public function radio() {
        $ps = explode(',', $this->config['param']);
        if (!$ps[0]) {
            return '';
        }
        $html = '<?php $itms = $all_lan["'.$this->config['field'].'"]; ';
       
        return $html . "  echo radio(array('field'=>'{$this->config['field']}','items'=>\$itms,'fname'=>'{$this->config['name']}','defaultvalue'=>\$info?\$info['{$this->config['field']}']:'{$this->config['val']}',)); ?>";
    }

    public function editor() {
        return "<?php echo editor(array('field'=>'{$this->config['field']}','fname'=>'{$this->config['name']}','defaultvalue'=>\$info?htmlspecialchars_decode(\$info['{$this->config['field']}'], ENT_QUOTES):'{$this->config['val']}',)); ?>";
    }

    public function ffile() {
        return "<?php echo ffile(array('field'=>'{$this->config['field']}','fname'=>'{$this->config['name']}','defaultvalue'=>\$info?\$info['{$this->config['field']}']:'{$this->config['val']}','col'=>4)); ?>";
    }

    public function images() {
        return "<?php echo photo(array('field'=>'{$this->config['field']}','fname'=>'{$this->config['name']}','val'=>json_decode(\$info['{$this->config['field']}'],true),'col'=>4,'select_num'=>5)); ?>";
    }

    public function textarea() {
        return "<?php echo textarea(array('field'=>'{$this->config['field']}','fname'=>'{$this->config['name']}','defaultvalue'=>\$info?\$info['{$this->config['field']}']:'{$this->config['val']}','col'=>4)); ?>";
    }

    public function thumb() {
        return "<?php echo photo(array('field'=>'{$this->config['field']}','fname'=>'{$this->config['name']}','defaultvalue'=>\$info?\$info['{$this->config['field']}']:'{$this->config['val']}','col'=>4)); ?>";
    }

    public function ftime() {
        return "<?php echo ftime(array('field'=>'{$this->config['field']}','fname'=>'{$this->config['name']}','defaultvalue'=>\$info?\$info['{$this->config['field']}']:'{$this->config['val']}','col'=>4)); ?>";
    }

    public function fdate() {
        return "<?php echo fdate(array('field'=>'{$this->config['field']}','fname'=>'{$this->config['name']}','defaultvalue'=>\$info?\$info['{$this->config['field']}']:'{$this->config['val']}','col'=>4)); ?>";
    }

    public function datetime() {
        return "<?php echo datetime(array('field'=>'{$this->config['field']}','fname'=>'{$this->config['name']}','defaultvalue'=>\$info?\$info['{$this->config['field']}']:'{$this->config['val']}','col'=>4)); ?>";
    }

    public function selec() {
        if ($this->config['param']) {
            $html = "<?php \$itms =  \$all_lan['{$this->config['field']}'][\$v['{$this->config['field']}']]; ?>";
        }
        $ext[] = "'requestFnName'=>'{$this->config['field']}'";
        if ($this->config['other']['guanlian']) {
            $ext[] = "'callfname'=>'{$this->config['other']['guanlian']}'";
        }
        if ($this->config['other']['callback']) {
            $ext[] = "'requestUrl'=>url('{$this->config['other']['callback']}')";
        }


        if ($this->config['other']['source']) {
            $ps = explode('/', $this->config['other']['source']);
            $source = '<?php   $itms = array(); ';

            if (!$ps[3]) {
                $source .= '$itms =' . "(new \app\\{$ps[0]}\\model\\{$ps[1]}())->{$ps[2]}();";
            } else {
                $source .= 'if($info[\'' . $ps[3] . '\']){ ';
                $source .= '$itms =' . "(new \\app\\{$ps[0]}\\model\\{$ps[1]}())->{$ps[2]}" . '($in[\'' . $ps[3] . '\']); ';
                $source .= '} ';
            }
            $source .= ' ?> ';
        }
        if ($ext) {
            $ext = implode(',', $ext);
        }

        return $html . $source . "<?php  echo select(array('field'=>'{$this->config['field']}','items'=>\$itms,'fname'=>'{$this->config['name']}','defaultvalue'=>\$info?\$info['{$this->config['field']}']:'{$this->config['val']}','col'=>4,$ext)); ?>";
    }

    public function rangedate() {
        return "<?php echo rangedate(array('field'=>'{$this->config['field']}','fname'=>'{$this->config['name']}','defaultvalue'=>\$info?\$info['{$this->config['field']}']:'{$this->config['val']}','col'=>4)); ?>";
    }

}
