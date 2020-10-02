<?php

namespace backend\module\system\controllers;

use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use backend\models\system\FuncModel;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use common\models\VwSysPower;
use common\models\TeacherInfo;
use common\models\SysPower;
use common\models\MemberUserInfo;
use common\models\VwTeacherInfo;

class PowerController extends Controller
{
    function header(){
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:*');
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
    }

    public  function actions(){
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }

    public function actionShowpowerdata()
    {
        $query = SysPower::find()
            ->select('*')
            ->andWhere( ['or', ['super'=>'1'], ['and', ['super'=>'2'], ['userkind'=>'1']]])
            ->andWhere(['status'=>1])
            ->all();
        $teacherInfo=[];
        foreach ($query as $key => $value) {
            $job_num=$value['username'];
            $teacher = VwTeacherInfo::find()
                ->select('*')
                ->andWhere(['job_num'=>$job_num])
                ->andWhere(['status'=>1])
                ->all();
            $teacherInfo[$key]=$teacher;
        }

        return array("data"=>[$query,$teacherInfo],"msg"=>"success"); 
    }

    //添加管理员,不存在相同数据则插入
    public function actionAddpowerdata(){
        $request = \Yii::$app->request;
        $userId= $request->post('userId');
        $username = $request->post('username');
        $majorId= $request->post('majorId');
        $super= $request->post('super');
        $major = $request->post('major')?1:0;
        $company = $request->post('company')?1:0;
        $practice= $request->post('practice')?1:0;
        $requirement= $request->post('requirement')?1:0;
        $tracking= $request->post('tracking')?1:0;
        $statistics= $request->post('statistics')?1:0;
        $system= $request->post('system')?1:0;

        $_data=SysPower::find()->andWhere(['username'=>$username])->andWhere(['status'=>1])->one();
        if ($_data == null) {
            $temp=\Yii::$app->db->createCommand()->insert('sys_power',
                array(
                 'username'=>$username, 
                 'majorId'=>$majorId,
                 'super'=>$super,
                 'userkind'=>1,
                 'major'=>$major,
                 'company'=>$company,
                 'practice'=>$practice,
                 'requirement'=>$requirement,
                 'tracking'=>$tracking,
                 'statistics'=>$statistics,
                 'system'=>$system,
                 'status'=>1
                 )
                )->execute();
            return $temp;
        }else{
            return array("data"=>$_data,"msg"=>"数据不为空");
        }
    }

	 //更改信息，根据userid来修改数据
    public function actionUpdatepower(){
        $request = \Yii::$app->request;
        $userId= $request->post('userId');
        $username = $request->post('username');
        $majorId= $request->post('majorId');
        $super= $request->post('super');
        $major = $request->post('major')?1:0;
        $company = $request->post('company')?1:0;
        $practice= $request->post('practice')?1:0;
        $requirement= $request->post('requirement')?1:0;
        $tracking= $request->post('tracking')?1:0;
        $apprenticeship= $request->post('apprenticeship')?1:0;
        $system= $request->post('system')?1:0;

        $_data=SysPower::find()->andWhere(['username'=>$username])->andWhere(['status'=>1])->one();

        if ($_data == null) {
            return "修改失败";
        } else {
            $temp=\Yii::$app->db->createCommand()->update('sys_power',
                [
                'super'=>$super,
                'major'=>$major,
                'company'=>$company,
                'practice'=>$practice,
                'requirement'=>$requirement,
                'tracking'=>$tracking,
                'apprenticeship'=>$apprenticeship,
                'system'=>$system],'username=:username',[':username' => $username])->execute();
            return $temp;
        }
    }
      //移交权限，根据当前id修改userid
    public function actionMovepower(){
        $request = \Yii::$app->request;
        $currentId = $request->post('USERID');
        $userId = $request->post('USER');
        $majorId = $request->post('majorId');

        $_data=SysPower::find()->andwhere(['id'=>$userId])
        ->andWhere(['status'=>1])
        ->one();

        $_data->status="0";;
        $temp=$_data->save();

        $_new=new SysPower();
        $_new->username=$currentId;
        $_new->majorId=$majorId;
        $_new->super=$_data->super;
        $_new->userkind=$_data->userkind;
        $_new->majorId=$_data->majorId;
        $_new->company=$_data->company;
        $_new->major=$_data->major;
        $_new->practice=$_data->practice;
        $_new->requirement=$_data->requirement;
        $_new->tracking=$_data->tracking;
        $_new->statistics=$_data->statistics;
        $_new->system=$_data->system;
        $_new->status="1";
        $temp=$_new->save();

        if($temp)
            return array("data"=>$temp,"msg"=>"更改成功");
        else
            return "更改失败";
    }
   //根据userID删除，将status改为0
    public function actionDeletepowerdata(){
        $this->header();
        $request = \Yii::$app->request;
        $userId= $request->post('userId');
        if(!isset($userId))
            return "删除失败";

        $temp=\Yii::$app->db->createCommand()->update('sys_power',array('status' => "0"),"id=:userId",array(':userId'=>$userId))->execute();

        if($temp)
            return array("data"=>$temp,"msg"=>"删除成功");
        else
            return "删除失败";
    }

    //返回data对象
    // function data()
    // {
    //     $modelClass = new SysPower();
    //     $request = \Yii::$app->request;
    //     $userId = $request->post('USERID');
    //     $name = $request->post('NAME');
    //     $data = $modelClass::find()->
    //     andWhere(['STATUS'=>1])->
    //     andWhere([empty($userId)?'not like':'like','USERID',empty($userId)?'null':$userId])->
    //     andWhere([empty($name)?'not like':'like','NAME',empty($name)?'null':$name]);
    //     return $data;
    // }
    //查询管理员，可以根据userid,NAME,CARDID查询
    public function actionQuerypowerdata()
    {
        $this->header();
        $data = $this->data()->all();
        return array("data"=>$data,"msg"=>"success");
    }

    //查找非管理员成员
    public function actionGetMemberNoAdmin()
    {
        $this->header();
        $inputUser = \Yii::$app->request->post('inputUser');

        $teachers = VwTeacherInfo::find()
            ->andWhere(['status'=>1])
            ->andWhere(['teacherName'=>$inputUser])
            ->all();
        $result=[];
        foreach ($teachers as $key => $value) {  
            $job_num = $value['job_num'];
            $query = SysPower::find()
                ->andWhere(['status'=>1])
                ->andWhere(['username'=>$job_num])
                ->one();
            if($query==null){
                array_push($result, $value);
            }
        }
        return array("data"=>$result,"msg"=>"success");       
    }

}