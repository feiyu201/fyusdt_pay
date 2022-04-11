<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
return [
    'pay_url'          => [// 在后台插件配置表单中的键名 ,会是config[pay_url]
        'title' => '支付接口',
        'type'  => 'text', // 表单的类型：text,password,textarea,checkbox,radio,select等
        'value' => 'https://api.hjyusdt.com/api.php', // 表单的默认值
        'tip'   => '支付地址', //表单的帮助提示
    ],
    'appid'      => [
        'title' => 'appID',
        'type'  => 'text',
        'value' => '',
        'tip'   => '',
    ],
    'token'      => [
        'title' => '商户token',
        'type'  => 'text',
        'value' => '',
        'tip'   => '',
    ],
];
