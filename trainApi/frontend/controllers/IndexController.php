<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use frontend\models\wechart\index\Login;
use frontend\models\wechart\index\Logout;
use yii\filters\auth\QueryParamAuth;
use frontend\models\wechart\index\SignupForm;
use frontend\models\web\RegisterForm;
use common\models\SysWebUserTb;
/**
 * Site controller
 */
class IndexController extends Controller
{                                                                                                     
    /**
     * {@inheritdoc}
     * 若要开启access_token，则打开下authenticator
     */
    public function behaviors()
    {
        return [
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
                    'index',
                    'signup',
                    'register'
                ]
            ],
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    'Origin' => ['http://phpitem.grouptong.top'],
                    'Access-Control-Request-Method' => ['*'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age' => 3600,
                    'Access-Control-Request-Headers'=>['x-requested-with','content-type']
                ],
            ], 
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
        $request = \Yii::$app->request;
        $username = $request->post('username');
        $query = SysWebUserTb::find()
            ->select('*')
            ->andWhere(['USER' => $username])
            ->all();
        if(!$query){
            return array("msg"=>"绑定失败，您尚未注册！","data"=>false);
        }else{
            $model = new Login();
            if ($model->load(Yii::$app->request->post(),"")) {
                $user=$model->login();
                if ($user) {
                    return array("msg"=>"绑定成功","data"=>$user);
                } else {
                    return "绑定失败";
                }
            } else {
                return "请输入用户名和密码";
            }
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

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post(),"")) {
            if ($user = $model->signup()) {
                return array("msg"=>"注册成功","data"=>$model);
            }
        }else{
            return "请输入注册用户名和密码";
        }
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionRegister()
    {
        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post(),"")) {
            if ($user = $model->register()) {
                if($user==="has")
                    return "用户名已存在";
                else
                    return array("msg"=>"注册成功","data"=>$user);
            }
        }else{
            return "请输入注册用户名和密码";
        }
    }
}
