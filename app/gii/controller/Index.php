<?php

declare (strict_types = 1);

namespace app\gii\controller;

class Index extends Common {

    public function index() {

        return $this->display('index');
    }

}
