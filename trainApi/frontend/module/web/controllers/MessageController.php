<?php
namespace frontend\module\web\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\Cors;
use yii\filters\auth\QueryParamAuth;
use frontend\models\wechart\message\MeetMessage;
/**
 * Site controller
 */
class MessageController extends Controller
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
                // 'actions' => [
                //     'logout' => ['get'],
                // ],
            ],
            //设置了access_token验证的，需要在请求url上带上access_token
            // 'authenticator' => [ 
            //     'class' => QueryParamAuth::className() ,
            //     'tokenParam' => 'access_token',  //例如改为‘access_token’,get方式请求
            // ],
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

    public function actionIndex()
    {
        return array("data"=>"欢迎到来--私信的模块","msg"=>"233333");
    }

    public function actionGetMessage()
    {
        $model = new MeetMessage(); 
        if ($model->load(Yii::$app->request->post(),"")) {
            $message=$model->getMessage();
            if ($message) {
                return array("msg"=>"获取成功","data"=>$message);
            } else {
                return "获取失败";
            }
        } else {
            return "请确认输入信息是否完整";
        }
    }
}
