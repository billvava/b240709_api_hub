<?php
declare (strict_types = 1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class Timer extends Command
{


    public function __construct() {
        $this->model = new \app\common\model\User();

    }
    /**
     * @var int
     */
    protected $timer;
    /**
     * @var int|float
     */
    protected $interval = 1;
    protected function configure()
    {
        // 指令配置
        $this->setName('timer')
            ->addArgument('status', Argument::REQUIRED, 'start/stop/reload/status/connections')
            ->addOption('d', null, Option::VALUE_NONE, 'daemon（守护进程）方式启动')
            ->addOption('i', null, Option::VALUE_OPTIONAL, '多长时间执行一次')
            ->setDescription('开启/关闭/重启 定时任务');

    }

    protected function init(Input $input, Output $output)
    {
        global $argv;
        if ($input->hasOption('i'))
            $this->interval = floatval($input->getOption('i'));
        $argv[1] = $input->getArgument('status') ?: 'start';
        if ($input->hasOption('d')) {
            $argv[2] = '-d';
        } else {
            unset($argv[2]);
        }
    }


    protected function execute(Input $input, Output $output)
    {
        $this->init($input, $output);
        //创建定时器任务
        $task = new Worker();
        $task->count = 1;
        $task->onWorkerStart = [$this, 'start'];
        $task->runAll();

    }

    public function stop()
    {
        //手动暂停定时器
        \Workerman\Lib\Timer::del($this->timer);
    }

    public function start()
    {

        $this->Timer= \Workerman\Lib\Timer::add($this->interval, function () {
            $this->model -> kaijiang();
            //每隔1秒执行一次
            //亲测有效，只需在这个方法写逻辑代码......
        });
    }

}
