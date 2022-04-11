<?php
$domain = configuration('domain');
return array (
        //异步通知地址
		'notify_url' => "{$domain}/gateway/fyusdt_pay/index/notify_handle",
		//同步跳转
		'return_url' => "{$domain}/gateway/fyusdt_pay/index/return_handle",
        //取消支付地址
		'cancel_url' => "{$domain}/gateway/fyusdt_pay/index/cancel_handle",
);