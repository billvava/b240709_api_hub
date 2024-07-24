<?php

namespace app\gii\controller;

use Kkokk\Poster\PosterManager;
use Kkokk\Poster\Exception\Exception;
use think\facade\View;

/**
 * 2020/12/25 By MaxYeung
 */
class Poster extends Common {

    /**
     * 预览页
     *
     * @return  [type]  [return description]
     */
    public function index() {
       
        // 参考数据
        // $buildIm = [
        //     'w' => 590,
        //     'h' => 1045,
        //     'rgba' => [255,255,255,127],
        //     'alpha' => false
        // ];
        // $buildImage = [
        //     [
        //         'src' => 'http://xfchen.xinhu.wang/yangn/huike/image/202012/851c6f507dd6024d7378830c8d685f4d.jpg',
        //         'dst_x' => 0,
        //         'dst_y' => 65,
        //         'src_x' => 0,
        //         'src_y' => 0,
        //         'src_w' => 590,
        //         'src_h' => 588,
        //         'alpha' => false,
        //         'type' => 'normal'
        //     ]
        // ];
        // $buildText = [
        //     [
        //         'content' => '测试名称',
        //         'dst_x' => 'center',
        //         'dst_y' => 710,
        //         'font' => 24,
        //         'rgba' => [51, 51, 51, 1],
        //         'max_w' => '',
        //         'font_family' => '',
        //     ],
        //     [
        //         'content' => '地址',
        //         'dst_x' => 'center',
        //         'dst_y' => 760,
        //         'font' => 14,
        //         'rgba' => [153, 153, 153, 1],
        //         'max_w' => '',
        //         'font_family' => '',
        //     ]
        // ];
        // $buildQr = [
        //     [
        //         'text' => 'test',
        //         'dst_x' => 'center',
        //         'dst_y' => 810,
        //         'src_x' => 0,
        //         'src_y' => 0,
        //         'size' => 4,
        //         'margin' => 1,
        //     ]
        // ];
        return View::fetch();
    }

    /**
     * 预览
     *
     * @return  [type]  [return description]
     */
    public function preview() {
        try {
            $model = PosterManager::Poster('./img/posterPreview');
            $buildIm = $this->strToArr($this->in['buildIm']);
            $buildImage = $this->in['buildImage'];
            $buildText = $this->in['buildText'];
            $buildQr = $this->in['buildQr'];
            if ($buildIm) {
                $model = $model->buildIm($buildIm['w'], $buildIm['h'], $buildIm['rgba'], $buildIm['alpha']);
            }
            if ($buildImage) {
                foreach ($buildImage as $image) {
                    // 过滤参数
                    $image = $this->strToArr($image);
                    $model = $model->buildImage($image['src'], $image['dst_x'], $image['dst_y'], $image['src_x'], $image['src_y'], $image['src_w'], $image['src_h'], $image['alpha'], $image['type']);
                }
            }
            if ($buildText) {
                foreach ($buildText as $text) {
                    // 过滤参数
                    $text = $this->strToArr($text);
                    $model = $model->buildText($text['content'], $text['dst_x'], $text['dst_y'], $text['font'], $text['rgba'], $text['max_w'], $text['font_family']);
                }
            }
            if ($buildQr) {
                foreach ($buildQr as $qr) {
                    // 过滤参数
                    $qr = $this->strToArr($qr);
                    $model = $model->buildQr($qr['text'], $qr['dst_x'], $qr['dst_y'], $qr['src_x'], $qr['src_y'], $qr['size'], $qr['margin']);
                }
            }
            $result = $model->getPoster();
            json_msg(1, '', '', [
                'width' => $buildIm['w'],
                'height' => $buildIm['h'],
                'url' => C('siteurl') . ltrim($result['url'], '.') . '?time=' . time()
            ]);
        } catch (Exception $e) {
            json_msg(0, $e->getMessage());
        }
    }

    /**
     * 生成代码
     *
     * @return  [type]  [return description]
     */
    public function build() {
        try {
            $str = '// 头部引入命名空间<br/>';
            $str .= '// use Kkokk\Poster\PosterManager;<br/>';
            $str .= '// use Kkokk\Poster\Exception\Exception;<br/>';
            $str .= 'try {<br/>';
            $str .= $this->space_repeat() . '$posterModel = PosterManager::Poster("图片保存路径");<br/>';
            $buildIm = $this->in['buildIm'];
            $buildImage = $this->in['buildImage'];
            $buildText = $this->in['buildText'];
            $buildQr = $this->in['buildQr'];
            if ($buildIm) {
                $str .= $this->space_repeat() . '$posterModel = $posterModel->buildIm(';
                $imParam = $this->handleParam([$buildIm], false);
                $str .= rtrim($imParam, ',');
                $str .= ');<br/>';
            }
            if ($buildImage) {
                foreach ($buildImage as $image) {
                    $str .= $this->space_repeat() . '$posterModel = $posterModel->buildImage(';
                    $imageParam = $this->handleParam($image);
                    $str .= rtrim($imageParam, ',');
                    $str .= ');<br/>';
                }
            }
            if ($buildText) {
                foreach ($buildText as $text) {
                    $str .= $this->space_repeat() . '$posterModel = $posterModel->buildText(';
                    $textParam = $this->handleParam($text);
                    $str .= rtrim($textParam, ',');
                    $str .= ');<br/>';
                }
            }
            if ($buildQr) {
                foreach ($buildQr as $qr) {
                    $str .= $this->space_repeat() . '$posterModel = $posterModel->buildQr(';
                    $qrParam = $this->handleParam($qr);
                    $str .= rtrim($qrParam, ',');
                    $str .= ');<br/>';
                }
            }
            $str .= $this->space_repeat() . '$posterResult = $posterModel->getPoster();<br/>';
            $str .= '} catch (Exception $e){<br/>';
            $str .= $this->space_repeat() . '$e->getMessage();<br/>}';
            json_msg(1, '', '', $str);
        } catch (Exception $e) {
            json_msg(0, $e->getMessage());
        }
    }

    /**
     * 空格缩进
     *
     * @param   [type]  $lenth  [$lenth description]
     *
     * @return  [type]          [return description]
     */
    private function space_repeat($lenth = 4) {
        return str_repeat('&nbsp;', $lenth);
    }

    /**
     * 处理参数
     *
     * @param   [type]$data         [$data description]
     * @param   [type]$arrBoundary  [$arrBoundary description]
     * @param   true                [ description]
     *
     * @return  [type]              [return description]
     */
    private function handleParam($data, $arrBoundary = true) {
        $param = '';
        foreach ($data as $v) {
            if (is_array($v)) {
                if ($arrBoundary) {
                    $param .= '[' . rtrim($this->handleParam($v), ',') . '],';
                } else {
                    $param .= rtrim($this->handleParam($v), ',') . ',';
                }
            } else if (is_string($v)) {
                if (substr($v, 0, 1) == '[' && substr($v, -1, 1) == ']') {
                    $param .= $v . ',';
                } else {
                    $param .= '"' . $v . '",';
                }
            } else if (is_bool($v)) {
                $param .= ($v ? 'true' : 'false') . ',';
            } else {
                $param .= $v . ',';
            }
        }
        return $param;
    }

    /**
     * 数组格式转数组
     *
     * @param   [type]  $data  [$data description]
     *
     * @return  [type]         [return description]
     */
    private function strToArr($data) {
        $result = [];
        foreach ($data as $k => $v) {
            if (substr($v, 0, 1) == '[' && substr($v, -1, 1) == ']') {
                $newParam = ltrim($v, '[');
                $newParam = rtrim($newParam, ']');
                $newParam = explode(',', $newParam);
                foreach ($newParam as $key => $val) {
                    $newParam[$key] = (int) $val; // 传参只认数字类型
                }
                $result[$k] = $newParam;
            } else {
                $result[$k] = $v;
            }
        }
        return $result;
    }

}
