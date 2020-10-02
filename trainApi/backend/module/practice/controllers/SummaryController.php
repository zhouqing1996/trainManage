<?php

namespace backend\module\practice\controllers;
use Yii;
use yii\web\Controller;
use backend\models\practice\SummaryModel;

/**
 * Default controller for the `finance` module
 */
class SummaryController extends Controller
{

    public function actionIndex()
    {
        return "总结首页";
    }

    //获取所有企业
    public function actionGetcom()
    {
        $model = new SummaryModel();
        return array("msg"=>"获取所有企业","data"=>$model->getcom());
    }

    //获取实习信息和学生
    public function actionGetmajorst()
    {
        $model = new SummaryModel();
        $id = Yii::$app->request->post("id");
        return array("msg"=>"获取实习信息和学生","data"=>$model->getmajorst($id));
    }

}

?>
