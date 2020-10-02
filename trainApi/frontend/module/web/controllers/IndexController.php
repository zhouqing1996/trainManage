<?php

namespace frontend\module\web\controllers;
use Yii;
use yii\web\Controller;
use frontend\models\web\MeetsList;
/**
 * Default controller for the `finance` module
 */
class IndexController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return "个人中心web接口";
    }

    public function actionGetMeetList()
    {
        $model = new MeetsList(); 
        if ($model->load(Yii::$app->request->post(),"")) {
            $message=$model->getMeetList();
            if ($message) {
                return array("msg"=>"获取成功","data"=>$message);
            } else {
                return array("msg"=>"获取失败","data"=>false);
            }
        } else {
            return "请确认输入信息是否完整";
        }
    }
}
