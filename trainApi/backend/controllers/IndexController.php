<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use backend\models\home\Login;
use backend\models\home\Logout;
use yii\filters\auth\QueryParamAuth;

/**
 * Site controller
 */
class IndexController extends Controller
{                                                                                                   

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['*'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age' => 3600,
                    'Access-Control-Request-Headers'=>['x-requested-with','content-type']
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
            //设置了access_token验证的，需要在请求url上带上access_token
            'authenticator' => [ 
                'class' => QueryParamAuth::className() ,
                'tokenParam' => 'access_token',  //例如改为‘access_token’,get方式请求
                //设置不用验证access_token的方法
                'optional' => [
                    'login',
                ]
            ] 
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {   
        return array("data"=>"欢迎到来","msg"=>"22313");
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $model = new Login();
        if ($model->load(Yii::$app->request->post(),"")) {
            $user=$model->login();
            if ($user) {
                return array("msg"=>"登入成功","data"=>$user);
            } else {
                return array("msg"=>"登入失败","data"=>$user);
            }
        } else {
            return "请输入用户名和密码";
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        $model = new Logout();
        if ($model->load(Yii::$app->request->get(),"")) {
            $user=$model->logout();
            if ($user) {
                return "退出登入";
            } else {
                return "操作失败";
            }
        } else {
            return "无效access_token";
        }
    }
}
