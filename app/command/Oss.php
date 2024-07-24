<?php

declare (strict_types=1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class Oss extends Command
{

    protected function configure()
    {
        // 指令配置
        $this->setName('oss')
            ->setDescription('the hello command');
    }

    protected function execute(Input $input, Output $output)
    {
        set_time_limit(0);
        //oss-cn-shenzhen.aliyuncs.com
        //oss-cn-shenzhen-internal.aliyuncs.com
        $wang = 'oss-cn-shenzhen.aliyuncs.com';
        $wang = 'oss-cn-shenzhen-internal.aliyuncs.com';


        $ossClient = new \OSS\OssClient('LTAILufOqv1s4LZ1', '', $wang);
        $nextMarker = '';
        $aliyun_oss_bucket = "xfzhibo";
        try {
            $options = array(
                'prefix' => '',
                'delimiter' => '/',
                'marker' => $nextMarker,
                'max-keys' => 1000
            );
            $listObjectInfo = $ossClient->listObjects($aliyun_oss_bucket, $options);

        } catch (OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
        // 先获取子目录
        $prefixList = $listObjectInfo->getPrefixList();
        $dirs = [];
        if (!empty($prefixList)) {
            foreach ($prefixList as $prefixInfo) {
                $dirs[] = $prefixInfo->getPrefix();
            }
        }

        //再统计每个子目录的文件大小
        foreach ($dirs as $dir) {
            $nextMarker = '';
            $total = 0;
            while (true) {
                try {
                    $options = array(
                        'prefix' => $dir,
                        'delimiter' => '',
                        'marker' => $nextMarker,
                        'max-keys' => 1000
                    );
                    $listObjectInfo = $ossClient->listObjects($aliyun_oss_bucket, $options);

                } catch (OssException $e) {
                    printf(__FUNCTION__ . ": FAILED\n");
                    printf($e->getMessage() . "\n");
                    return;
                }
                $nextMarker = $listObjectInfo->getNextMarker();
                $listObject = $listObjectInfo->getObjectList();
                if (!empty($listObject)) {
                    foreach ($listObject as $objectInfo) {
                        $size = $objectInfo->getSizeStr();
                        $total = $total + $size;
                    }
                }

                if ($listObjectInfo->getIsTruncated() !== "true") {
                    break;
                }
            }
            $output->writeln("{$dir}：" . $this->ByteSize($total));


        }
        // 指令输出
    }

    private function ByteSize($file_size)

    {

        $file_size = $file_size - 1;

        if ($file_size >= 1099511627776) $show_filesize = number_format(($file_size / 1099511627776), 2) . " TB";

        elseif ($file_size >= 1073741824) $show_filesize = number_format(($file_size / 1073741824), 2) . " GB";

        elseif ($file_size >= 1048576) $show_filesize = number_format(($file_size / 1048576), 2) . " MB";

        elseif ($file_size >= 1024) $show_filesize = number_format(($file_size / 1024), 2) . " KB";

        elseif ($file_size > 0) $show_filesize = $file_size . " b";

        elseif ($file_size == 0 || $file_size == -1) $show_filesize = "0 b";

        return $show_filesize;

    }

    private function unfreeze()
    {
        $ossClient = new \OSS\OssClient('LTAILufOqv1s4LZ1', '', 'oss-cn-shenzhen-internal.aliyuncs.com');
        $nextMarker = '';
        $aliyun_oss_bucket = "xfbase";
//        http://static.tx520.cn/nvren/image/202004/b8947f986b7172c8226f5dd219b24e04.jpg
        //http://static.tx520.cn/dingdongsir/image/202101/
        while (true) {
            try {
//           202101 202012  202011 202010 202009  202008 202007 202006
                $options = array(
                    'prefix' => 'dingdongsir/image/202006/',
                    'delimiter' => '',
                    'marker' => $nextMarker,
                    'max-keys' => 1000
                );
                $listObjectInfo = $ossClient->listObjects($aliyun_oss_bucket, $options);
            } catch (OssException $e) {
                printf(__FUNCTION__ . ": FAILED\n");
                printf($e->getMessage() . "\n");
                return;
            }
            // 得到nextMarker，从上一次listObjects读到的最后一个文件的下一个文件开始继续获取文件列表。
            $nextMarker = $listObjectInfo->getNextMarker();
            $listObject = $listObjectInfo->getObjectList(); // 文件列表。
            if (!empty($listObject)) {
                foreach ($listObject as $objectInfo) {
                    $obj = $objectInfo->getKey();
                    $type = $objectInfo->getStorageClass();
                    $copyOptions = array(
                        'headers' => array(
                            'x-oss-storage-class' => 'Standard',
                            'x-oss-metadata-directive' => 'REPLACE',
                        ),
                    );
//                            $output->writeln($type);
                    if ($type == 'Archive') {
                        $meta = $ossClient->getObjectMeta($aliyun_oss_bucket, $obj);
                        if (array_key_exists("x-oss-restore", $meta) &&
                            strcmp($meta["x-oss-restore"], "ongoing-request=\"true\"") == 0) {
                            $ossClient->restoreObject($aliyun_oss_bucket, $obj);
                            $output->writeln("{$obj}解冻");
                        } else {

                            $ossClient->copyObject(
                                $aliyun_oss_bucket, $obj, $aliyun_oss_bucket, $obj, $copyOptions);
                            $output->writeln("{$obj}转换类型");
                        }
                    }

                    //解冻
//                    if ($type == 'Archive') {
//                        $ossClient->restoreObject($aliyun_oss_bucket, $obj);
//                        $output->writeln("{$obj}解冻");
//                    }
                    //更改储存类型
//                    if ($type == 'Archive') {
//                        $ossClient->copyObject(
//                                $aliyun_oss_bucket, $obj, $aliyun_oss_bucket, $obj, $copyOptions);
//                        $output->writeln("{$obj}更改成功");
//                    }
                }
            }
            if ($listObjectInfo->getIsTruncated() !== "true") {
                break;
            }
        }
        // 指令输出
        $output->writeln('okkkkkkkk');
    }
}
