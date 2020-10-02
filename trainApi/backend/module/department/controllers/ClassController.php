<?php
namespace backend\module\department\controllers;

use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\VwClassInfo;
use common\models\ClassInfo;


class ClassController extends Controller
{
    public function actionIndex()
    {
        return "班级管理首页";
    }

    public function actionGetdata()
    {
        $request = \Yii::$app->request;
        $classInfo = VwClassInfo::find()
            ->andWhere(['status' => 1])
            ->orderBy('majorId')
            ->all();
        return array("data"=>$classInfo,"msg"=>"success");
    }

    //插入数据，不存在相同数据则插入
    public function actionInsertclass()
    {
        $request = \Yii::$app->request;
        $grade = $request->post('grade');
        $majorId = $request->post('major');
        $className = $request->post('class');
        $result = ClassInfo::find()->andWhere(['grade'=>$grade])->andWhere(['majorId'=>$majorId])->andWhere(['className'=>$className])->andWhere(['status'=>1])->one() == null;
        if ($result) {
            return \Yii::$app->db->createCommand()->insert('class_info',
                        array(
                            'grade'=>$grade, 
                            'majorId'=>$majorId, 
                            'className'=>$className, 
                            'status'=>1
                        )
                    )->execute();
        } else {
            return "failure";
        }
    }

    //删除数据，根据专业Id来删除数据
    public function actionDeleteclass()
    {
        $request = \Yii::$app->request;
        $id = $request->post('classId');
        $result = \Yii::$app->db->createCommand()->update('class_info',
                    ['status'=>0],'id=:id',[':id' => $id])->execute();
        return $result;
    }
}