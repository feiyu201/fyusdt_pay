<?php
namespace gateways\fyusdt_pay\controller;
use gateways\fyusdt_pay\controller\ConfigController;
use \think\Controller;
use gateways\fyusdt_pay\FyusdtPayPlugin;
class IndexController extends Controller
{
    /**
     * 异步回调
     */
    public function notify_handle()
    {
        $get_name = new FyusdtPayPlugin();
        $name = $get_name->info['name'];
        if( $this->xy($_GET)){
            $data = ["invoice_id" => $_GET["ddh"], "trans_id" => $_GET["transaction_id"], "currency" => "USDT", "payment" => "$name", "amount_in" => $_GET["money"], "paid_time" => date("Y-m-d H:i:s")];

           $Order = new \app\home\controller\OrderController();
           $Order->orderPayHandle($data);
            echo "ok";
            return;
        }else{
            echo "no";
        }
        return;
    }

    /**
     * 同步回调
     */
    public function return_handle()
    {
        $get_name = new FyusdtPayPlugin();
        $name = $get_name->info['name'];
      
if( $this->xy($_GET)){
    $data = ["invoice_id" => $_GET["ddh"], "trans_id" => $_GET["transaction_id"], "currency" => "USDT", "payment" => "$name", "amount_in" => $_GET["money"], "paid_time" => date("Y-m-d H:i:s")];

    $Order = new \app\home\controller\OrderController();
    $Order->orderPayHandle($data);
    echo "ok";
 return redirect(config("return_url"));
}


       return redirect(config('return_url'));
    }

    public function cancel_handle()
    {
        //echo "取消支付";
        return redirect(config('return_url'));
    }
    public function xy($data1){
        $Con = new ConfigController();
        $config = $Con->getConfig();
        $token=$config['token'];
        $data = array(
                "appid" => $data1['appid'],//你的支付ID
                "ddh" => $data1['ddh'], //唯一标识 可以是用户ID,用户名,session_id(),订单ID,ip 付款后返回
                "money" => $data1['money'],//金额
                "note" => $data1['note'],//自定义参数
                "name" => $data1['name'],//产品名称
                "fromuser"=> $data1['fromuser'],//付款方
                "transaction_id"=> $data1['transaction_id'],//转账id
                "zt"=> $data1['zt'],//付款状态
                "paytime"=> $data1['paytime'],//付款时间
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
            }
        
        if(empty($token)){
            return false;
        }
        //echo $sign."<br><br>";
    // echo $sign."<br>";
        $jmsign=md5($sign.$token);
        $fhsign=$data1['sign'];
      //echo "这里是对比:获取的".$fhsign."-----现在加密的".$jmsign."<br>".md5($sign.$token)."<br>".$sign.$token."<br>";
        //die;
        if($fhsign==$jmsign){
         
            return true;
        }
        return false;
    }

}