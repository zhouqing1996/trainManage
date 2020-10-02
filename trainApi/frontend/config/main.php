<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    // 'catchAll' => [
    //     'index/contact',
    //     'message' => '维护中使用，将所有连接都捕捉到这',
    // ],
    'modules' => [
        'wechart' => [
            'class' => 'frontend\module\wechart\Module',
        ],
        'web' => [
            'class' => 'frontend\module\web\Module',
        ]
    ],
    'components' => [
        'request' => [
            'enableCsrfValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'response' => [   
            'format' => yii\web\Response::FORMAT_JSON, 
            'charset' => 'UTF-8',  
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                //default为gii默认的控制器名称

                if(Yii::$app->controller->id!='default'){
                    if($response->getStatusCode()==200){
                        $response->data = [
                            'code' => $response->getStatusCode(),
                            'data' => is_array($response->data)?$response->data['data']:null,
                            'message' =>is_array($response->data)?$response->data['msg']:$response->data
                        ];                          
                    }else{
                        $response->data = [
                            'code' => $response->getStatusCode(),
                            'data' => $response->data,
                            'message' => "服务器错误"
                        ];
                    }
                  
                }
            },                           
        ],
        'user' => [
            'identityClass' => 'common\models\Users',
            'enableAutoLogin' => true,
            'enableSession'=>false
            // restful不要用session或者cookie认证 'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => true,//是否显示index.php入口文件，需要相关服务器配置
            'enableStrictParsing' => false,//是否严格匹配大小写
            // 'suffix' => '.html',//后缀
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['wechart/index','wechart/club','wechart/my','wechart/find','wechart/message'],
                     // 'extraPatterns'=>[
                     //     'POST getdatas' => 'getdatas'
                     // ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['web/index','web/my','web/finance'],
                     // 'extraPatterns'=>[
                     //     'POST payMoney' => 'payMoney'
                     // ],
                ]
            ],
        ],
    ],
    'params' => $params,
];
