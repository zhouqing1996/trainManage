<?php

namespace backend\module\probation\controllers;
use Yii;
use yii\web\Controller;
use backend\models\probation\RemindModel;

/**
 * Default controller for the `finance` module
 */
class RemindController extends Controller
{
    //获取总结提醒数据
    public function actionGetremind()
    {
        $model = new RemindModel();
        return array("msg"=>"获取总结提醒数据","data"=>$model->getremind());
    }

    //获取总结提醒数据
    public function actionGetremind2()
    {
        $model = new RemindModel();
        $recruitId = Yii::$app->request->post("recruitId");
        return array("msg"=>"获取总结提醒数据","data"=>$model->getremind2($recruitId));
    }

    //获取推送的实习单位
    public function actionGetpush()
    {
        $model = new RemindModel();
        return array("msg"=>"获取推送的实习单位","data"=>$model->getpush());
    }

    //添加提醒
    public function actionAddremind()
    {
        $model = new RemindModel();
        $form = Yii::$app->request->post("form");
        return array("msg"=>"添加提醒","data"=>$model->addremind($form));
    }

    //添加提醒
    public function actionAddremind2()
    {
        $model = new RemindModel();
        $form = Yii::$app->request->post("form");
        $recruitId = Yii::$app->request->post("recruitId");
        return array("msg"=>"添加提醒","data"=>$model->addremind2($form,$recruitId));
    }

    //编辑提醒
    public function actionEditremind()
    {
        $model = new RemindModel();
        $form = Yii::$app->request->post("form");
        return array("msg"=>"编辑提醒","data"=>$model->editremind($form));
    }

    //编辑提醒
    public function actionEditremind2()
    {
        $model = new RemindModel();
        $form = Yii::$app->request->post("form");
        $recruitId = Yii::$app->request->post("recruitId");
        return array("msg"=>"编辑提醒","data"=>$model->editremind2($form,$recruitId));
    }

    //删除提醒
    public function actionDeletremind()
    {
        $model = new RemindModel();
        $id = Yii::$app->request->post("id");
        return array("msg"=>"删除提醒","data"=>$model->deletremind($id));
    }

}

?>
