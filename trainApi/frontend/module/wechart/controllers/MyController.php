<?php
namespace frontend\module\wechart\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\Cors;
use yii\filters\auth\QueryParamAuth;
use frontend\models\wechart\my\AlterInfor;
use frontend\models\wechart\my\AlterAccount;
use frontend\models\wechart\my\ExpertInfor;
/**
 * Site controller
 */
class MyController extends Controller
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
            'authenticator' => [ 
                'class' => QueryParamAuth::className() ,
                'tokenParam' => 'access_token',  //例如改为‘access_token’,get方式请求
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

    public function actionIndex()
    {
        return array("data"=>"欢迎到来--我的模块","msg"=>"233333");
    }

    //驼峰命名法的方法，用-链接
    public function actionAlterInfor()
    {
        $model = new AlterInfor();
        $access_token=Yii::$app->request->get('access_token');
 
        if ($model->load(Yii::$app->request->post(),"")) {

            $user=$model->alter($access_token);
            if ($user) {
                return array("msg"=>"修改成功","data"=>$user);
            } else {
                return "修改失败，请检查格式是否正确";
            }

        } else {
            return "请输入要修改的信息";
        }
    }

    //驼峰命名法的方法，用-链接
    public function actionExpertInfor()
    {
        $model = new ExpertInfor();
        $access_token=Yii::$app->request->get('access_token');
 
        if ($model->load(Yii::$app->request->post(),"")) {

            $user=$model->expert($access_token);
            if ($user) {
                return array("msg"=>"修改成功","data"=>$user);
            } else {
                return "修改失败，请检查格式是否正确";
            }

        } else {
            return "请输入要修改的信息";
        }
    }

    public function actionAlterAccount()
    {
        $model = new AlterAccount();
        $access_token=Yii::$app->request->get('access_token');

        if ($model->load(Yii::$app->request->post(),"")) {

            $user=$model->alter($access_token);
            if ($user) {
                return array("msg"=>"修改成功，请重新登入","data"=>$user);
            } else {
                return "修改失败，请检查格式是否正确";
            }
        } else {
            return "请输入要修改的信息";
        }
    }

    public function actionGetMoneyRecordList()
    {
        return "获取缴费列表";
    }
}
