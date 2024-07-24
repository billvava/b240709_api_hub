<?php

/**
 * php memcahce原子锁
 */
class MyQueue {

    public static $server;
    public static $mem;
    private $Qname;  //key
    private $Maxnum;
    private $start_time;

    public function __construct($name = '', $maxqueue = 1, $expire = 0) {
        if ($name) {
            $this->Qname = $name;
        } else {
            $this->Qname = 'memqueue';
        }
        $this->Maxnum = $maxqueue != null ? $maxqueue : 1;
        if (is_null($this->mem)) {
            $this->mem = new \Memcache;
            if (is_null($this->server)) {
                $this->server = $this->mem->connect('127.0.0.1', 11211);
            }
        }
        $this->retrynum = 10000; //加锁不成功重试次数;
        $this->expire = $expire;
        $this->sleep_time = 10000;
        $this->lock = false;
    }

    /**
     * [lock 线程上锁 原子性操作]
     * @return [type] [description]
     */
    public function lock($timeout = '') {
        $i = 0;
       
        while (1) {
            if ($this->mem->add($this->Qname . '_lock', 1, false, $this->expire)) {
                //上锁成功
                return true;
                break;
            }
            $i++;
            if ($timeout) {
                if (!$this->start_time) {
                    $this->start_time = time();
                }
                if (time() - $this->start_time > $timeout) {
                    //超过设定时间
                    return false;
                }
            } else {
                usleep($this->sleep_time);
                if ($i > $this->retryNum) {
                    //超时
                    return false;
                    break;
                }
            }
        }
    }

    public function unLock() {
        $this->mem->delete($this->Qname . '_lock');
    }

}
