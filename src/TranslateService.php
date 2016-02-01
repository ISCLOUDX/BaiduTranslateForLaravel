<?php
/**
 * PowerBy http://www.iscloudx.com
 * 本版本写于2016/2/1
 */
namespace iscms\translate;


class TranslateService implements TranslateApi
{
    //初始化配置
    private $id,$key;
    //定义服务器地址
    private $url="http://api.fanyi.baidu.com/api/trans/vip/translate";
    /**
     * 你可以在这里写国际化
     * @var array
     */
    private $error=[
        '52000'=>'成功',
        '52001'	=>'请求超时',
        '52002'	=>'系统错误',
        '52003'	=>'未授权用户',
        '54000'	=>'必填参数为空',
        '58000'	=>'客户端IP非法',
        '54001'	=>'签名错误',
        '54003'	=>'访问频率受限',
        '58001'	=>'译文语言方向不支持',
        '54004'	=>'账户余额不足',
        '54005'	=>'长query请求频繁',
    ];
    //获取服务配置
    public function __construct()
    {
        $this->id=config('baidu-translate.BAIDU_TRANSLATE_ID');
        $this->key=config('baidu-translate.BAIDU_TRANSLATE_KEY');
    }
    /**
     * 翻译方法
     * @param $query
     * @param $from
     * @param $to
     * @return mixed|void
     */
    public function translate($query, $from='auto', $to='en'){
        $args = array(
            'q' => $query,
            'appid' => $this->id,
            'salt' => rand(10000,99999),
            'from' => $from,
            'to' => $to,
        );
        $args['sign'] = $this->buildSign($query, $this->id, $args['salt'], $this->key);
        $ret = $this->callOnce($this->url, $args);
        $ret = json_decode($ret, true);

        if (isset($ret['error_code'])){
            return ['result'=>$this->error[$ret['error_code']],'status'=>'error'];
        }

        return ['result'=>$ret['trans_result'][0]['dst'],'status'=>'success'];
    }

    /**
     * 签名构造
     * @param $query
     * @param $appID
     * @param $salt
     * @param $secKey
     * @return string
     */
    public function buildSign($query, $appID, $salt, $secKey)
    {
        $sign="{$appID}{$query}{$salt}{$secKey}";
        $result = md5($sign);
        return $result;
    }

    /**
     * 发起请求
     * @param $url
     * @param null $args
     * @param string $method
     * @param bool $withCookie
     * @param int $timeout
     * @param array $headers
     * @return mixed
     */

    public function callOnce($url, $args = null, $method = "post", $withCookie = false, $timeout = 30, $headers = array()){

        $ch = curl_init();
        if($method == "post")
        {
            $data = $this->convert($args);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_POST, 1);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if(!empty($headers))
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        if($withCookie)
        {
            curl_setopt($ch, CURLOPT_COOKIEJAR, $_COOKIE);
        }
        $r = curl_exec($ch);
        curl_close($ch);
        return $r;
    }

    /**
     * 转换参数
     * @param $args
     * @return string
     */
    public function convert(&$args){
        $data = '';
        if (is_array($args))
        {
            foreach ($args as $key=>$val)
            {
                if (is_array($val))
                {
                    foreach ($val as $k=>$v)
                    {
                        $data .= $key.'['.$k.']='.rawurlencode($v).'&';
                    }
                }
                else
                {
                    $data .="$key=".rawurlencode($val)."&";
                }
            }
            return trim($data, "&");
        }
        return $args;
    }
}