<?php

namespace backend\module\student\controllers;
use Yii;
use yii\web\Controller;
use backend\models\student\StudentModel;

/**
 * Default controller for the `finance` module
 */
class IndexController extends Controller
{
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
            ]
        ];
    }

    public function actionIndex()
    {
        return "学生首页";
    }

    //获取学生信息
    public function actionGetstinfo()
    {
    	$model = new StudentModel();
        $username = Yii::$app->request->get("username");
        return array("msg"=>"获取学生信息","data"=>$model->getstinfo($username));
    }

    //修改学生信息
    public function actionModifyinfo()
    {
    	$model = new StudentModel();
        $username = Yii::$app->request->get("username");
        $contactPhone = Yii::$app->request->get("contactPhone");
        $email = Yii::$app->request->get("email");
        return array("msg"=>"修改学生信息","data"=>$model->modifyinfo($username,$contactPhone,$email));
    }

    //获取招聘信息列表
    public function actionGetrecruit()
    {
    	$model = new StudentModel();
        $username = Yii::$app->request->get("username");
        return array("msg"=>"获取招聘信息列表","data"=>$model->getrecruit($username));
    }

    //获取问卷列表
    public function actionGetsurvey()
    {
        $model = new StudentModel();
        $username = Yii::$app->request->get("username");
        return array("msg"=>"获取问卷列表","data"=>$model->getsurvey($username));
    }

    //获取消息列表
    public function actionGetremind()
    {
        $model = new StudentModel();
        $username = Yii::$app->request->get("username");
        return array("msg"=>"获取消息列表","data"=>$model->getremind($username));
    }

    //学生实习申请
    public function actionApply()
    {
    	$model = new StudentModel();
        $id = Yii::$app->request->get("id");
        $username = Yii::$app->request->get("username");
        return array("msg"=>"学生实习申请","data"=>$model->apply($id,$username));
    }

    //学生取消申请
    public function actionCancelapply()
    {
    	$model = new StudentModel();
        $id = Yii::$app->request->get("id");
        $username = Yii::$app->request->get("username");
        return array("msg"=>"学生取消申请","data"=>$model->cancelapply($id,$username));
    }

    //学生申请列表
    public function actionApplylist()
    {
    	$model = new StudentModel();
        $username = Yii::$app->request->get("username");
        return array("msg"=>"学生申请列表","data"=>$model->applylist($username));
    }

    //添加总结
    public function actionAddsummary()
    {
    	$model = new StudentModel();
        $username = Yii::$app->request->get("username");
        $subject = Yii::$app->request->get("subject");
        $content = Yii::$app->request->get("content");
        $date = Yii::$app->request->get("date");
        $type = Yii::$app->request->get("type");
        $recruitId = Yii::$app->request->get("recruitId");
        return array("msg"=>"添加总结","data"=>$model->addsummary($username,$subject,$content,$date,$type,$recruitId));
    }

    //获取总结
    public function actionGetsummary()
    {
    	$model = new StudentModel();
        $username = Yii::$app->request->get("username");
        $type = Yii::$app->request->get("type");
        return array("msg"=>"添加总结","data"=>$model->getsummary($username,$type));
    }

    //学生打卡
    public function actionClock()
    {
    	$model = new StudentModel();
        $username = Yii::$app->request->get("username");
        $recruitId = Yii::$app->request->get("recruitId");
        $time = Yii::$app->request->get("time");
        $lng = Yii::$app->request->get("lng");
        $lat = Yii::$app->request->get("lat");
        $address = Yii::$app->request->get("address");
        return array("msg"=>"学生打卡","data"=>$model->clock($username,$recruitId,$time,$lng,$lat,$address));
    }

    //打卡详细
    public function actionClocklist()
    {
    	$model = new StudentModel();
        $username = Yii::$app->request->get("username");
        $recruitId = Yii::$app->request->get("recruitId");
        return array("msg"=>"打卡详细","data"=>$model->clocklist($username,$recruitId));
    }

    //当日打卡详细
    public function actionDayclcok()
    {
        $model = new StudentModel();
        $username = Yii::$app->request->get("username");
        $recruitId = Yii::$app->request->get("recruitId");
        return array("msg"=>"当日打卡详细","data"=>$model->dayclcok($username,$recruitId));
    }


}
