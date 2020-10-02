<?php
namespace frontend\module\web\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\Cors;
use yii\filters\auth\QueryParamAuth;
use frontend\models\wechart\my\AlterInfor;
use frontend\models\wechart\my\ExpertInfor;
use frontend\models\wechart\my\AlterAccount;
use common\models\VwAttenderList;
use common\models\VwModuleParticipate;
use common\models\VwFinanceCertificate;
use common\models\ModulePay;
use common\models\ModuleLogistic;
use common\models\ModuleContribute;
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
            // 'authenticator' => [ 
            //     'class' => QueryParamAuth::className() ,
            //     'tokenParam' => 'access_token',  //例如改为‘access_token’,get方式请求
            // ],
            // 'corsFilter' => [
            //     'class' => \yii\filters\Cors::className(),
            //     'cors' => [
            //         'Origin' => ['http://phpitem.grouptong.top'],
            //         'Access-Control-Request-Method' => ['*'],
            //         'Access-Control-Allow-Credentials' => true,
            //         'Access-Control-Max-Age' => 3600,
            //         'Access-Control-Request-Headers'=>['x-requested-with','content-type']
            //     ],
            // ], 
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
        return array("data"=>"欢迎到来--个人中心","msg"=>"233333");
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

    public function actionGetMeetRecordList()
    {
        $userId=Yii::$app->request->post("userId");
        $kind=Yii::$app->request->post("kind");

        if(!$userId){
            return "查找失败";
        }
        if($kind==9)
            $_data =VwAttenderList::find()->where(['userId'=>$userId])->andWhere(["<>","status",0])->all();
        else
            $_data =VwModuleParticipate::find()->where(['expertId'=>$userId])->all();

        return array("msg"=>"获取成功","data"=>$_data);
    }


    public function actionGetMoneyRecordList()
    {
        $userId=Yii::$app->request->post("userId");
        if(!$userId){
            return "查找失败";
        }

        $_data =VwFinanceCertificate::find()->where(['USERID'=>$userId])->andWhere(['or',['STATUS'=>2],['STATUS'=>3],['STATUS'=>8],['STATUS'=>9]])->all();

        return array("msg"=>"获取成功","data"=>$_data);

    }

    public function actionGetPayInfo()
    {
        $meetId=Yii::$app->request->post("meetId");

        if(!$meetId){
            return "查找失败";
        }
        $_model=[];
        $_pay =ModulePay::find()->where(['meetId'=>$meetId])->one();
        $_model["pay"]=$_pay;

        $_logistic =ModuleLogistic::find()->where(['meetId'=>$meetId,"kind"=>1,"delFlag"=>1])->one();
        if($_logistic!=null){
            $_model["logistic"]=true;
        }else{
            $_model["logistic"]=false;
        }

        $_contribute =ModuleContribute::find()->where(['meetId'=>$meetId,"delFlag"=>1])->one();
        if($_contribute!=null){
            $_model["contribute"]=true;
        }else{
            $_model["contribute"]=false;
        }

        return array("msg"=>"获取成功","data"=>$_model);
    }

}
