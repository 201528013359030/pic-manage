<?php

return [
    'adminEmail' => 'admin@example.com',
    'wechat' =>[
        'options'=>[
            'token'=>'qifahao_TELPO', //填写你设定的key
            'appid'=>'wx2841427ccd2e3857',//???发???凭???
            'appsecret'=>'439551496c4602a502bd1d2c4d370a76', //???发???凭???
            'debug'=>true,
            'logcallback'=>'./log.text' 
        ],
    ],
    'path'=>'http://'.$_SERVER['HTTP_HOST'],
    'menu'=>[
        '01'=>['main'=>'用户管理',
            'img'=>'images/mainmenu01.png',
            'sub'=>[
                '01'=>['name'=>'设置管理员','url'=>'index.php?r=admin/index&action=admin'],
                '02'=>['name'=>'设置普通用户','url'=>'index.php?r=admin/index&action=user'],
            ]
        ]
    ],
    'urlSubflag'=>'&subflag=0101',
];
