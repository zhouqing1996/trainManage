<?php

namespace backend\module\gengangpractice\controllers;
use Yii;
use yii\web\Controller;
use backend\models\gengangpractice\ClockModel;

/**
 * Default controller for the `finance` module
 */
class ClockController extends Controller
{

    public function actionIndex()
    {
        return "签到首页";
    }

    //根据查询条件获取签到列表
    public function actionClocklist()
    {
        $model = new ClockModel();
        $power = Yii::$app->request->post("power");
        $user = Yii::$app->request->post("user");
        $date = Yii::$app->request->post("date");
        $sno = Yii::$app->request->post("sno");
        $class = Yii::$app->request->post("class");
        if ($power['userkind'] == '1') {
            if ($power['super'] == '1') {
                $list=$model->clocklist('root',$date,$sno,$class,$user);
            } else {
                $list=$model->clocklist('teacher',$date,$sno,$class,$user);
            }           
        }
        return array("msg"=>"根据查询条件获取签到列表","data"=>$list);
    }

    //根据查询条件获取签到列表
    public function actionClocklist2()
    {
        $model = new ClockModel();
        $rowId = Yii::$app->request->post("rowId");
        $date = Yii::$app->request->post("date");
        $sno = Yii::$app->request->post("sno");
        return array("msg"=>"根据查询条件获取签到列表","data"=>$model->clocklist2($rowId,$date,$sno));
    }

    //获取专业和班级
    public function actionGetclass()
    {
        $model = new ClockModel();
        $power = Yii::$app->request->post("power");
        $user = Yii::$app->request->post("user");
        $recruitId = Yii::$app->request->post("recruitId");
        return array("msg"=>"获取专业和班级","data"=>$model->getclass($recruitId,$power,$user));
    }
}

?>
