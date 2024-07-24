<?php

class Wechat {

    private $data = array();

    public function __construct($token) {
        $this->auth($token) || exit;
        if (request()->isGet()) {
            echo ($_GET['echostr']);
            exit;
        } else {
            $xml = file_get_contents("php://input");
            $xml = new SimpleXMLElement($xml);
            $xml || exit;
            foreach ($xml as $key => $value) {
                $this->data[$key] = strval($value);
            }
        }
    }

    public function request() {
        return $this->data;
    }

    public function response($content, $type = 'text', $flag = 0) {
        $this->data = array(
            'ToUserName' => $this->data['FromUserName'],
            'FromUserName' => $this->data['ToUserName'],
            'CreateTime' => $_SERVER['REQUEST_TIME'],
            'MsgType' => $type
        );
        //类型对应的函数
        $this->$type($content);
        $this->data['FuncFlag'] = $flag;
        $xml = new SimpleXMLElement('<xml></xml>');
        $this->data2xml($xml, $this->data);
        exit($xml->asXMl());
    }

    //回复文本内容
    private function text($content) {
        $content = str_replace("\r\n", "\n", $content);
        $content = htmlspecialchars_decode($content);
        preg_match_all('/\[U\+(\\w{4,})\]/i', $content, $matchArray);
        if (!empty($matchArray[1])) {
            foreach ($matchArray[1] as $emojiUSB) {
                $content = str_ireplace("[U+{$emojiUSB}]", utf8_bytes(hexdec($emojiUSB)), $content);
            }
        }
        $this->data['Content'] = $content;
    }
    //回复图片
    private function image($content) {
        $this->data['Image']['MediaId'] = $content;
    }

    //回复音乐
    private function music($music) {
        list($music['Title'], $music['Description'], $music['MusicUrl'], $music['HQMusicUrl']) = $music;
        $this->data['Music'] = $music;
    }

    //回复新闻
    private function news($news) {
        $articles = array();
        foreach ($news as $key => $value) {
            list($articles[$key]['Title'], $articles[$key]['Description'], $articles[$key]['PicUrl'], $articles[$key]['Url']) = $value;
            if ($key >= 9) {
                break;
            }
        }
        $this->data['ArticleCount'] = count($articles);
        $this->data['Articles'] = $articles;
    }

    /**
     * 多客服
     * @param type $content
     * @param type $type
     * @param type $flag
     */
    public function kfresponse($type = 'text', $flag = 0) {
        $this->data = array(
            'ToUserName' => $this->data['FromUserName'],
            'FromUserName' => $this->data['ToUserName'],
            'CreateTime' => NOW_TIME,
            'MsgType' => "transfer_customer_service"
        );
        //类型对应的函数
        $this->data['FuncFlag'] = $flag;
        $xml = new SimpleXMLElement('<xml></xml>');
        $this->data2xml($xml, $this->data);
        exit($xml->asXMlang());
    }

    private function data2xml($xml, $data, $item = 'item') {
        foreach ($data as $key => $value) {
            is_numeric($key) && $key = $item;
            if (is_array($value) || is_object($value)) {
                $child = $xml->addChild($key);
                $this->data2xml($child, $value, $item);
            } else {
                if (is_numeric($value)) {
                    $child = $xml->addChild($key, $value);
                } else {
                    $child = $xml->addChild($key);
                    $node = dom_import_simplexml($child);
                    $node->appendChild($node->ownerDocument->createCDATASection($value));
                }
            }
        }
    }

    private function auth($token) {
        $data = array(
            $_GET['timestamp'],
            $_GET['nonce'],
            $token
        );
        $sign = $_GET['signature'];
        sort($data, SORT_STRING);
        $signature = sha1(implode($data));
        return $signature === $sign;
    }

    protected function respImage($mid) {
        if (empty($mid)) {
            return error(-1, 'Invaild value');
        }
        $response = array();
        $response['FromUserName'] = $this->message['to'];
        $response['ToUserName'] = $this->message['from'];
        $response['MsgType'] = 'image';
        $response['Image']['MediaId'] = $mid;
        return $response;
    }

    protected function respVoice($mid) {
        if (empty($mid)) {
            return error(-1, 'Invaild value');
        }
        $response = array();
        $response['FromUserName'] = $this->message['to'];
        $response['ToUserName'] = $this->message['from'];
        $response['MsgType'] = 'voice';
        $response['Voice']['MediaId'] = $mid;
        return $response;
    }

    protected function respVideo(array $video) {
        if (empty($video)) {
            return error(-1, 'Invaild value');
        }
        $response = array();
        $response['FromUserName'] = $this->message['to'];
        $response['ToUserName'] = $this->message['from'];
        $response['MsgType'] = 'video';
        $response['Video']['MediaId'] = $video['MediaId'];
        $response['Video']['Title'] = $video['Title'];
        $response['Video']['Description'] = $video['Description'];
        return $response;
    }

    protected function respMusic(array $music) {
        if (empty($music)) {
            return error(-1, 'Invaild value');
        }
        global $_W;
        $music = array_change_key_case($music);
        $response = array();
        $response['FromUserName'] = $this->message['to'];
        $response['ToUserName'] = $this->message['from'];
        $response['MsgType'] = 'music';
        $response['Music'] = array(
            'Title' => $music['title'],
            'Description' => $music['description'],
            'MusicUrl' => tomedia($music['musicurl'])
        );
        if (empty($music['hqmusicurl'])) {
            $response['Music']['HQMusicUrl'] = $response['Music']['MusicUrl'];
        } else {
            $response['Music']['HQMusicUrl'] = tomedia($music['hqmusicurl']);
        }
        if ($music['thumb']) {
            $response['Music']['ThumbMediaId'] = $music['thumb'];
        }
        return $response;
    }

    protected function respNews(array $news) {
        if (empty($news) || count($news) > 10) {
            return error(-1, 'Invaild value');
        }
        $news = array_change_key_case($news);
        if (!empty($news['title'])) {
            $news = array($news);
        }
        $response = array();
        $response['FromUserName'] = $this->message['to'];
        $response['ToUserName'] = $this->message['from'];
        $response['MsgType'] = 'news';
        $response['ArticleCount'] = count($news);
        $response['Articles'] = array();
        foreach ($news as $row) {
            $response['Articles'][] = array(
                'Title' => $row['title'],
                'Description' => ($response['ArticleCount'] > 1) ? '' : $row['description'],
                'PicUrl' => tomedia($row['picurl']),
                'Url' => $this->buildSiteUrl($row['url']),
                'TagName' => 'item'
            );
        }
        return $response;
    }

    protected function respCustom(array $message = array()) {
        $response = array();
        $response['FromUserName'] = $this->message['to'];
        $response['ToUserName'] = $this->message['from'];
        $response['MsgType'] = 'transfer_customer_service';
        if (!empty($message['TransInfo']['KfAccount'])) {
            $response['TransInfo']['KfAccount'] = $message['TransInfo']['KfAccount'];
        }
        return $response;
    }

}

if (!function_exists('utf8_bytes')) {

    function utf8_bytes($cp) {
        if ($cp > 0x10000) {
            return chr(0xF0 | (($cp & 0x1C0000) >> 18)) .
                    chr(0x80 | (($cp & 0x3F000) >> 12)) .
                    chr(0x80 | (($cp & 0xFC0) >> 6)) .
                    chr(0x80 | ($cp & 0x3F));
        } else if ($cp > 0x800) {
            return chr(0xE0 | (($cp & 0xF000) >> 12)) .
                    chr(0x80 | (($cp & 0xFC0) >> 6)) .
                    chr(0x80 | ($cp & 0x3F));
        } else if ($cp > 0x80) {
            return chr(0xC0 | (($cp & 0x7C0) >> 6)) .
                    chr(0x80 | ($cp & 0x3F));
        } else {
            return chr($cp);
        }
    }

}
?>
