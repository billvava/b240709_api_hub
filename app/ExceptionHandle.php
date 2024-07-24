<?php

namespace app;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\Response;
use Throwable;

/**
 * 应用异常处理类
 */
class ExceptionHandle extends Handle {

    /**
     * 不需要记录信息（日志）的异常类列表
     * @var array
     */
    protected $ignoreReport = [
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        DataNotFoundException::class,
        ValidateException::class,
    ];

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     *
     * @access public
     * @param  Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void {
        // 使用内置的方式记录异常日志
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     * @param \think\Request   $request
     * @param Throwable $e
     * @return Response
     */
    public function render($request, Throwable $e): Response {
        // 添加自定义异常处理机制


        if ($e instanceof ValidateException) {
            $this->error($e->getError());
        }

        if (!($e instanceof HttpResponseException) && $request->isPost()) {
            $flag = 0;
            if (config('my.show_product_err') == 0 && !(new \app\common\lib\Util())->check_debug()) {
                $flag = 1;
            }

            log_err($e->getFile() ." ". $e->getLine() . PHP_EOL . $e->getMessage());

            if ($flag == 0) {
                echo json_encode([
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'error' => $e->getMessage()]);
            } else {
                header("HTTP/1.1 500 Internal Server Error");
                echo json_encode([
                    'msg' => 'System error, please check the log'
                        ]);
                die;
            }
        }


        // 其他错误交给系统处理
        return parent::render($request, $e);
    }

    /**
     * 操作错误跳转的快捷方法
     * @access protected
     * @param  mixed     $msg 提示信息
     * @param  string    $url 跳转的URL地址
     * @param  mixed     $data 返回的数据
     * @param  integer   $wait 跳转等待时间
     * @param  array     $header 发送的Header信息
     * @return void
     */
    protected function error($msg = '', string $url = null, $data = '', int $wait = 3, array $header = []) {
        if (is_null($url)) {
            $url = request()->isAjax() ? '' : 'javascript:history.back(-1);';
        } elseif ($url) {
            $url = (strpos($url, '://') || 0 === strpos($url, '/')) ? $url : app()->route->buildUrl($url);
        }

        $result = [
            'status' => 0,
            'info' => $msg,
            'data' => $data,
            'url' => $url,
            'wait' => $wait,
        ];
        
        if (input('is_api') == '1' || request()->isAjax()) {
            unset($result['url']);
            echo json_encode($result);
            die;
        }

        $response = view(app()->getRootPath() . 'view/public/error.php', $result);
        throw new HttpResponseException($response);
    }

}
