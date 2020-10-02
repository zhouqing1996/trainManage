<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    // 'catchAll' => [
    //     'index/contact',
    //     'message' => '维护中使用，将所有连接都捕捉到这',
    // ],
    'modules' => [
        'department' => [
            'class' => 'backend\module\department\Module',
        ],
        'company' => [
            'class' => 'backend\module\company\Module',
        ],
        'home' => [
            'class' => 'backend\module\home\Module',
        ],
        'requirement' => [
            'class' => 'backend\module\requirement\Module',
        ],
        'tracking' => [
            'class' => 'backend\module\tracking\Module',
        ],
        'system' => [
            'class' => 'backend\module\system\Module',
        ],
        'practice' => [
            'class' => 'backend\module\practice\Module',
        ],
        'apprenticeship' => [
            'class' => 'backend\module\apprenticeship\Module',
        ],
        'student' => [
            'class' => 'backend\module\student\Module',
        ],
        'probation' => [
            'class' => 'backend\module\probation\Module',
        ],
		'gengangpractice' => [
            'class' => 'backend\module\gengangpractice\Module',
        ],

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
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
        //路由美化,改变url的访问形式,可以实现controllerMap+的功能
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => true,//是否显示index.php入口文件，需要相关服务器配置
            'enableStrictParsing' => false,//是否严格匹配大小写
            // 'suffix' => '.html',//后缀
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule', 
                    'controller' => 'index',
//                     'extraPatterns'=>[
//                         'GET getdatas' => 'getdatas'
//                     ],
                ],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['department/index','department/major','department/member'],
                    'extraPatterns'=>[
                        'POST getdata' => 'getdata',
                        'POST insertmajor' => 'insertmajor',
                        'POST deletemajor' => 'deletemajor',
                        'POST geteditmajor' => 'geteditmajor',
                        'POST altermajor' => 'altermajor',
                        'POST querymajor' => 'querymajor',
                        'GET getteacherdata' => 'getteacherdata',
                        'GET getstudentdata' => 'getstudentdata',
                        'POST deleteteacher' => 'deleteteacher',
                        'POST deletestudent' => 'deletestudent',
                        'POST queryteacher' => 'queryteacher',
                        'POST querystudent' => 'querystudent',
                        'POST insertteacher' => 'insertteacher',
                        'POST insertstudent' => 'insertstudent',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['requirement/require','requirement/template','requirement/publish'],
                    'extraPatterns'=>[
                        'Get getdata' => 'getdata',
                        'POST insertsurvey' => 'insertsurvey',
                        'POST deletesurvey' => 'deletesurvey',
                        'POST geteditsurvey' => 'geteditsurvey',
                        'POST editsurvey' => 'editsurvey',
                        'POST publishsurvey' => 'publishsurvey',
                        'POST editclass' => 'editclass',
                        'POST submit' => 'submit',
                    ]
                ],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['home/index']
                ],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['app/index','app/notice']
                ],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['system/index','system/system','system/power'],
                     'extraPatterns'=>[
                        'POST querydata' => 'querydata',
                        'POST showdata' => 'showdata',
                        'GET getuserdata' => 'getuserdata',
                        'GET deleteuserdata' => 'deleteuserdata',
                        'POST exportuserdata' => 'exportuserdata',
                        'POST querypowerdata'=>'querypowerdata',
                        'POST showpowerdata' => 'showpowerdata',
                        'POST updatepowerdata'=>'updatepowerdata',
                        'POST addpowerdata' => 'addpowerdata',
                        'GET deletepowerdata' => 'deletepowerdata',
                        'POST movepower'=>'movepower',
                    ]
                ],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['practice/recruitment'],
                    'extraPatterns'=>[
                        'POST data' => 'data',
                    ]
                ],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['student/index']
                ],

            ],
        ],
    ],
        // 'controllerMap' => [//可通过配置 controller map 来强制上述的控制器ID和类名对应
    //     // 用类名申明 "account" 控制器
    //     'account' => 'app\controllers\UserController',

    //     // 用配置数组申明 "article" 控制器
    //     'article' => [
    //         'class' => 'app\controllers\PostController',
    //         'enableCsrfValidation' => false,
    //     ],
    // ],
    'params' => $params,
    // 'layout' => false,//是否开启默认布局文件
    'defaultRoute' => 'index',//设置默认的控制器
];
