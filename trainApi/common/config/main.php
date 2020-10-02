<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        // 
        // 声明phpspreadsheet类库的路径
        '@Psr/SimplaeCache' =>'@vendor/psr/simple-cache-1.0.1\src',
        '@PhpOffice' => '@vendor/phpoffice/PhpSpreadsheet/src'
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'aliyun' => [
          'class' => 'saviorlv\aliyun\Sms',
          'accessKeyId' => 'LTAIdPtPHCdocn4I',
          'accessKeySecret' => '7jLsjTMkn4mGpdHt0MpW0WQMNXYdtm'
        ],
        'mailer' => [ 
            'class' => 'yii\swiftmailer\Mailer', 
            'viewPath' => '@common/mail', 
            'useFileTransport' => false, 
            'transport' => [ 
                'class' => 'Swift_SmtpTransport', 
                'host' => 'smtp.qq.com', 
                'username' => '614490691@qq.com',//发送者邮箱地址
                'password' => 'dakkqlryzhbobfah', //SMTP密码
                'port' => '25', 
                'encryption' => 'tls', 
            ], 
            'messageConfig'=>[ 
                'charset'=>'UTF-8', 
                'from'=>['614490691@qq.com'=>'system'] 
            ], 
         ],
    ],
];
