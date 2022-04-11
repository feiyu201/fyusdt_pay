<?php
namespace gateways\fyusdt_pay;
use app\admin\lib\Plugin;
use gateways\fyusdt_pay\controller\ConfigController;
use think\Exception;
class FyusdtPayPlugin extends Plugin
{
    public $info = array(
        'name'        => 'FyusdtPay',//Demo插件英文名，改成你的插件英文就行了
        'title'       => 'USDT支付',
        'description' => 'USDT支付插件',
        'status'      => 1,
        'author'      => '欢聚网络',
        'version'     => '1.0',
        'module'        => 'gateways',
        'help_url'  => 'https://www.hjyusdt.com/',
        'author_url'=>'https://www.hjyvps.com'
    );

    public $hasAdmin = 0;//插件是否有后台管理界面

    public function install()
    {
        return true;//安装成功返回true，失败false
    }

    // 插件卸载
    public function uninstall()
    {
        return true;//卸载成功返回true，失败false
    }
    public function FyusdtPayHandle($param)
    {
        $ddh=$param['out_trade_no'];
        $money=$param['total_fee'];
        $name=$param['product_name'];
        $url=$this->jm($ddh,$money,$name,$name);
        if($url=="-1"){
            return  json(['msg'=>'请先将USDT相关信息配置写入','status'=>400]);
        }
        echo "<div class=\"pay-text\"><span class=\"fs-28 text-primary\">请勿关闭,到账自动关闭,避免不到账</span></div>";
        return ["type" => "jump", "data" => $url];
    }
    public function jm($ddh,$money,$name,$note){
        $Con = new ConfigController();
        $config = $Con->getConfig();
        $pay_url=$config['pay_url'];
        $appid=$config['appid'];
        $token=$config['token'];
        $notify_url=$config['notify_url'];
        $return_url=$config['return_url'];
        $data = array(
            "appid" => "$appid",//你的支付ID
            "ddh" => "$ddh", //唯一标识 可以是用户ID,用户名,session_id(),订单ID,ip 付款后返回
            "money" => "$money",//金额1200元
            "note" => "$note",//自定义参数
            "notify_url" => "$notify_url",//通知地址
            "return_url" => "$return_url",//跳转地址
            "name" => "$name",//跳转地址
        ); //构造需要传递的参数

        ksort($data); //重新排序$data数组
        reset($data); //内部指针指向数组中的第一个元素
        $sign = ''; //初始化需要签名的字符为空
        $urls = ''; //初始化URL参数为空
        foreach ($data AS $key => $val) { //遍历需要传递的参数
            if ($val == ''||$key == 'sign') continue; //跳过这些不参数签名
            if ($sign != '') { //后面追加&拼接URL
                $sign .= "&";
                $urls .= "&";
            }
            $sign .= "$key=$val"; //拼接为url参数形式
            $urls .= "$key=" . urlencode($val); //拼接为url参数形式并URL编码参数值

        }
        if($pay_url==""){
            $pay_url="https://api.hjyusdt.com/api.php";
        }
        if($token==""){
            return "-1";
        }
        $query = $urls . '&sign=' . md5($sign .$token); //创建订单所需的参数
        $url = "{$pay_url}?{$query}"; //支付页面

        return $url;
    }
}