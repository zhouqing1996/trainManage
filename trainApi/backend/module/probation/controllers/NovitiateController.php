<?php

namespace backend\module\probation\controllers;
use Yii;
use yii\web\Controller;
use backend\models\probation\TrainModel;

/**
 * Default controller for the `system` module
 */
class NovitiateController extends Controller
{
	//学生实习信息列表
    public function actionTraindata()
    {
        $model = new TrainModel();
        $power = Yii::$app->request->post("power");
        if ($power["userkind"] == '1') {
            if ($power['super'] == '1') {
                $list=$model->getList('root',1);
            } else {
                $list=$model->getList('teacher',$power["username"]);
            }           
        } else {
            $list=$model->getList('company',$power["username"]);
        }
        return array("msg"=>"学生实习信息列表","data"=>$list);
    }

    //修改打卡
    public function actionEditmap()
    {
    	$model = new TrainModel();
        $rowId = Yii::$app->request->post("rowId");
        $form = Yii::$app->request->post("form");
        $lng = Yii::$app->request->post("lng");
        $lat = Yii::$app->request->post("lat");
        $address = Yii::$app->request->post("address");
        return array("msg"=>"修改打卡","data"=>$model->editmap($rowId,$form,$lng,$lat,$address));
    }
}