<?php
namespace backend\module\department\controllers;

use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\TeacherInfo;
use common\models\MajorInfo;
use common\models\VwMajorInfo;
use common\models\SysPower;

class MajorController extends Controller
{
    public function actionIndex()
    {
        return "专业管理首页";
    }

    public function actionGetdata()
    {
        $request = \Yii::$app->request;
        $majorInfo = MajorInfo::find()
            ->andWhere(['status' => 1])
            ->orderBy('major ASC')
            ->all();
        $managers = [];
        foreach ($majorInfo as $key => $value) {
            $managers[$key]=[];
            $majorId = $value['id'];
            $managerArr = SysPower::find()
                ->andWhere(['super' => 2])
                ->andWhere(['userkind' => '1'])
                ->andWhere(['majorId' => $majorId])
                ->andWhere(['major' => '1'])
                ->andWhere(['status' => 1])
                ->select('username')
                ->all();
            
            foreach ($managerArr as $key1 => $value1) {
                $username=$value1['username'];
                $teacher = TeacherInfo::find()
                    ->andWhere(['job_num' => $username])
                    ->andWhere(['status' => 1])
                    ->select('teacherName')
                    ->all();
                if ($teacher) {
                    array_push($managers[$key],$teacher[0]['teacherName']);
                }
            }
            $managers[$key] = implode(',', $managers[$key]);
        }

        $majorInfos = [];
        $majorInfos['major'] = $majorInfo;
        $majorInfos['manager'] = $managers;
        
        return array("data"=>$majorInfos,"msg"=>"success");
    }

    //插入数据，不存在相同数据则插入
    public function actionInsertmajor()
    {
        $request = \Yii::$app->request;
        $majorName = $request->post('majorName');
        $majorProfile = $request->post('majorProfile');

        if (MajorInfo::find()->andWhere(['major'=>$majorName])->andWhere(['status'=>1])->one() == null) {
            return \Yii::$app->db->createCommand()->insert('major_info',
                        array(
                            'major'=>$majorName, 
                            'profile'=>$majorProfile, 
                            'status'=>1
                        )
                    )->execute();
        } else {
            return "failure";
        }
    }

    //删除数据，根据专业Id来删除数据
    public function actionDeletemajor()
    {
        $request = \Yii::$app->request;
        $majorId = $request->post('majorId');
        $result = \Yii::$app->db->createCommand()->update('major_info',
                    ['status'=>0],'id=:majorId',[':majorId' => $majorId])->execute();
        return $result;
    }

    //获取编辑数据
    public function actionGeteditmajor()
    {
        $request = \Yii::$app->request;
        $majorId = $request->post('majorId');

        $majorInfo = MajorInfo::find()
            ->where(['id' => $majorId])
            ->andWhere(['status' => 1])
            ->one();
        if ($majorInfo==null) {
            return "failure";
        } else {
            return array("data"=>$majorInfo,"msg"=>"success");
        }
    }

    //个人用户
    public function actionGeteditamajor()
    {
        $request = \Yii::$app->request;
        $majorId = $request->post('majorId');
        $result = MajorInfo::find()->where(['id' => $majorId])->one();
        if ($result) {
            return array("data"=>$result,"msg"=>"success");
            
        } else {
            return "failure";
        }
    }

    //修改数据
    public function actionAltermajor()
    {
        $request = \Yii::$app->request;
        $majorId = $request->post('majorId');
        $majorName = $request->post('majorName');
        $profile = $request->post('profile');

        $result = \Yii::$app->db->createCommand()->update('major_info',
            ['major'=>$majorName,
             'profile'=>$profile,
             'status'=>1],'id=:majorId',[':majorId' => $majorId])->execute();
        return array("data"=>$result,"msg"=>"success");
    }

     public function actionAlteramajor()
    {
        $request = \Yii::$app->request;
        $majorId = $request->post('majorId');
        $majorName = $request->post('majorName');
        $profile = $request->post('majorProfile');
        $result = \Yii::$app->db->createCommand()->update('major_info',
            ['id'=>$majorId,
             'major'=>$majorName,
             'profile'=>$profile,],'id=:majorId',[':majorId' => $majorId])->execute();
        return array("data"=>$result,"msg"=>"success");
    }

    public function actionQuerymajor()
    {
        $request = \Yii::$app->request;
        $majorName = $request->post('majorName');
        $majorInfo = (new Query())
            ->select('*')
            ->from('major_info')
            ->andWhere(['major' => $majorName])
            ->andWhere(['status' => 1])
            ->all();
        $majorId = $majorInfo[0]['id'];
        $managers = [];
        $managerArr = VwMajorInfo::find()
            ->andWhere(['super' => 2])
            ->andWhere(['majorId' => $majorId])
            ->andWhere(['majorName' => '1'])
            ->andWhere(['majorStatus' => 1])
            ->select('teacherName')
            ->all();
        foreach ($managerArr as $key => $value) {
            array_push($managers,$value['teacherName']);
        }
        $managers = implode(',', $managers);

        $majorInfos = [];
        $majorInfos['major'] = $majorInfo;
        $majorInfos['manager'] = $managers;
        
        return array("data"=>$majorInfos,"msg"=>"success");
    }
}