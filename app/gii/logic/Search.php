<?php

namespace app\gii\logic;
class Search
{
    public $config;
    public $star;
    public $end;

    public function returnStr($config)
    {

        $this->config = array(
            'field' => $config['field'],
            'name' => $config['name'],
            'type' => $config['type'],
            'val' => "input('get.{$config['field']}')",
            'param' => $config['param'],
            'other' => $config['other'],
        );


        $this->star = '<?php if($searchArr[\'' . $this->config['field'] . '\']){ ?>';
        $this->end = '<?php } ?>';

        return  $this->{$config['type']}() .  PHP_EOL;
    }


    public function text()
    {
        return "<div class='layui-inline' >
	<label class='layui-form-label'>{$this->config['name']}</label>
	<div class='layui-input-inline'>
		<input type='text' name='{$this->config['field']}'  value='<?php echo input('get.{$this->config['field']}'); ?>' class='layui-input'>

	</div>
</div>" . PHP_EOL;

    }

    public function text_old()
    {
        return "<input type='text' name='{$this->config['field']}' value='<?php echo input('get.{$this->config['field']}'); ?>'  placeholder='{$this->config['name']}'  class='layui-input'>"
            . "" . PHP_EOL;
    }

    function rangedate()
    {
        return "<?php echo list_rangedate(array('field'=>'{$this->config['field']}','fname'=>'{$this->config['name']}','defaultvalue'=>input('get.{$this->config['field']}'))); ?>";
    }


    public function selec()
    {
        if ($this->config['param']) {
            $html = "<?php \$itms =  \$all_lan['{$this->config['field']}']; ?>";

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

        return $html . $source . "<?php  echo new_list_select(array('field'=>'{$this->config['field']}','items'=>\$itms,'fname'=>'{$this->config['name']}','defaultvalue'=>{$this->config['val']},$ext)); ?>";

    }


    //用户搜索
    public function user_selec()
    {
        if ($this->config['param']) {
            $html = "<?php \$itms =  \$all_lan['{$this->config['field']}']; ?>";
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

        return $html . $source . "<?php echo list_select_user(array('url' => url('user/Index/getuser'),'field' => '{$this->config['field']}', 'name' => '{$this->config['name']}','msg' => '{$this->config['msg']}','val' =>{$this->config['val']}, 'val_name' => {$this->config['val']} ? getname({$this->config['val']}) : '' )); ?>";


    }


}
