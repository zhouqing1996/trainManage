<?php

namespace backend\module\app\controllers;

use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\StudentInfo;

/**
 * Default controller for the `home` module
 * 个人信息
 */
class IndexController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionGetdata()
    {
        return "首页";
    }
     
    public function actionStudentinfo()
    {
    	
    	$data = StudentInfo::find()->where(['stuName'=>'龙小心'])->all();
    	return array("data"=>$data,"msg"=>"学生信息");
    }

}
