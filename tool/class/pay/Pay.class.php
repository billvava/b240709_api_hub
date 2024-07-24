<?php

/**
 * 支付类
 * 2021/1/15 By MaxYeung
 */
// 调用示例 可直接复制修改参数使用
// tool()->classs('pay/Pay');
// $Pay = new \Pay('minprofy_pay', [
//     'notify_url' => C('wapurl') . "/mall/pay/fuyou"
// ]);
// // setConfig为覆盖配置方法 不需要可省略
// $result = $Pay->setConfig('notify_url', C('wapurl') . "/mall/pay/fuyou2")->payForMini([
//     'openid' => 'openid',
//     'ordernum' => 123456,
//     'total' => 0.01,
//     'orderid' => 100
// ]);
class Pay {

    /**
     * 操作类名
     *
     * @var [type]
     */
    public $class;

    /**
     * 配置参数
     * 优先级 类方法检测 > 方法映射检测
     * @var [type]
     */
    public $config = [];

    /**
     * 初始化方法
     *
     * @param   [type]  $class     类文件名
     * @param   [type]  $options   初始化参数
     *
     * @return  []                [return description]
     */
    public function __construct($class = '', array $options = []) {
        $config = include_once INCLUDE_PATH . 'class/pay/Config.php';
        $is = INCLUDE_PATH . 'class/pay/driver/' . $class . '.class.php';
        if (!file_exists($is)) {
            throw new \think\Exception('支付类不存在', 10006);
        }
        $name = $class;
        if (in_array($class, ['wx_app', 'wx_js'])) {
            $name = "minpro_pay";
        }
        if (in_array($class, ['fy_miniprogram', 'fy_offiaccount'])) {
            $name = "minprofy_pay";
        }
        if (isset($config[$name]['config']) && is_array($config[$name]['config'])) {
            $this->options = array_merge($config[$name]['config'], $options);
        }
        $this->class = $class;
        $this->config = $config;
    }

    /**
     * 设置配置文件
     *
     * @param   [type]  $key    配置参数key
     * @param   [type]  $value  配置参数val
     *
     * @return  [type]          [return description]
     */
    public function setConfig($key, $value) {

        if (!is_array($key)) {
            $key = array($key => $value);
        }
        foreach ($key as $k => $v) {
            $this->options[$k] = $v;
        }
        return $this;
    }

    /**
     * 获取配置文件
     *
     * @param   [type]  $key  配置参数key
     *
     * @return  [type]        [return description]
     */
    public function getConfig($key) {
        return $this->options[$key];
    }

    /**
     * 方法统一处理
     *
     * @param   [type]  $method  方法名
     * @param   [type]  $args    参数
     *
     * @return  [type]           结果数据集
     */
    public function __call($method, $args) {
        tool()->classs('pay/driver/' . $this->class);
        $handler = new $this->class($this->options);
        if (!method_exists($handler, $method)) {
            if (!method_exists($handler, $this->config[$this->class]['action'][$method])) {
                throw new \think\Exception($this->class . ': 未定义' . $method . '方法', 10006);
            }
            $method = $this->config[$this->class]['action'][$method];
        }
        return $this->resultHandle(call_user_func_array([$handler, $method], $args), $method);
    }

    /**
     * 返回统一处理
     *
     * @param   [type]  $data    返回数据集
     * @param   [type]  $method  方法名
     *
     * @return  [type]           统一命名数据集
     */
    public function resultHandle($data, $method) {
        $configResult = $this->config[$this->class]['result'][$method];
        if (!isset($configResult)) {
            return $data;
        }
        $result = [];
        foreach ($configResult as $k => $v) {
            $result[$k] = $this->fieldHandle($data, $v);
        }
        return $result;
    }

    /**
     * 返回值字段映射统一处理
     *
     * @param   [type]  $data   返回数据集
     * @param   [type]  $key    映射字段名
     * @param   [type]  $level  检索层级
     *
     * @return  [type]          映射字段返回值
     */
    public function fieldHandle($data, $key, $level = 0) {
        $result = '';
        $name = explode('.', $key);
        if (isset($data[$name[$level]])) {
            if (is_array($data[$name[$level]]) && isset($name[$level + 1])) {
                $result = $this->fieldHandle($data[$name[$level]], $key, $level + 1);
            } else {
                $result = $data[$name[$level]];
            }
        }
        return $result;
    }

}
