<?php
namespace backend\module\company\controllers;

use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\CompanyInfo;
use common\models\TutorInfo;

class TutorController extends Controller
{
    public function actionIndex()
    {
        return "导师管理首页";
    }
//*************管理员登陆
     public function actionGetdataroot()
    {
        $request = \Yii::$app->request;
        $query = (new Query())
        ->select('*')
        ->from('tutor_info')
        ->andWhere(['status' => 1])
        ->all();
        return array("data"=>$query,"msg"=>"success");
    }
    public function actionQuerytutorroot()
    {
        $request = \Yii::$app->request;
        $tutorName = $request->post('tutorName');
        $query = (new Query())
            ->select('*')
            ->from('tutor_info')
            ->andWhere(['tutorName' => $tutorName])
            ->andWhere(['status' => 1])
            ->all();
        $query1 = (new Query())
            ->select('*')
            ->from('tutor_info')
            //->andWhere(['id' => $companyId])
            ->andWhere(['status' => 1]);

        $countQuery = clone $query1;
        $totalCount = $countQuery->count();
        $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>'8']);
        $models = $query1->offset($pages->offset)
            ->limit($pages->limit)
            ->all();   
        $pageNum = ceil($totalCount/8);
        return array("data"=>[$query,$pageNum],"msg"=>"success");
    }
    public function actionInserttutorroot()
    {
        $request = \Yii::$app->request;
        $tutorName = $request->post('tutorName');
        $tutorId = $request->post('identity');
        $tutorSex = $request->post('sex');
        $tutorCraft = $request->post('craft');
        $tutorPhone = $request->post('phone');
        $tutorCompany = $request->post('company');
        $tutorProfile = $request->post('profile');

        $result1 = TutorInfo::find()
            ->andWhere(['identity'=>$tutorId])
            ->andWhere(['status'=>1])
            ->one();

        if ($result1 == null) {
            $result2 = \Yii::$app->db->createCommand()->insert('tutor_info',
                array(
                    'identity'=>$tutorId,
                    'tutorName'=>$tutorName,
                    'sex'=>$tutorSex,
                    'craft'=>$tutorCraft,
                    'companyAccount'=>$tutorCompany,
                    'phone'=>$tutorPhone,
                    'profile'=>$tutorProfile,
                    'status'=>1
                )
            )->execute();
            if ($result2 == 1) {
                $password_hash=\Yii::$app->security->generatePasswordHash(123456);
                $user = \Yii::$app->db->createCommand()->insert('users',
                    array(
                        'username'=>$tutorId,   
                        'password_hash'=>$password_hash,
                        'status'=>1)
                    )->execute();
                if($user == 1){
                    $power = \Yii::$app->db->createCommand()->insert('sys_power',
                        array(
                            'username'=>$tutorId,  
                            'super'=>2,
                            'userkind'=>2, 
                            'company'=>1,
                            'practice'=>1,
                            'requirement'=>1,
                            'status'=>1)
                        )->execute();
                    if ($power == 1) {
                        return array("data"=>$power,"msg"=>"添加成功");
                    }else{
                        return false;
                    }
                }else{
                  return "添加权限失败";
                }
            }
        } else {
            return "failure";
        }
    }
    public function actionDeletetutorroot()
    {
        $request = \Yii::$app->request;
        $tutorId = $request->post('tutorId');
        $result = \Yii::$app->db->createCommand()
            ->update('tutor_info',['status'=>0],'identity=:tutorId',
            [':tutorId' => $tutorId])->execute();
        return $result;
    }
    public function actionGetedittutorroot()
    {
        $request = \Yii::$app->request;
        $tutorId = $request->post('identity');

        $tutorInfo = TutorInfo::find()
            ->where(['identity' => $tutorId])
            ->andWhere(['status' => 1])
            ->one();
        if ($tutorInfo==null) {
            return "failure";
        } else {
            return array("data"=>$tutorInfo,"msg"=>"success");
        }
    }
    public function actionAltertutorroot() 
    {
        $request = \Yii::$app->request;
        $tutorName = $request->post('tutorName');
        $tutorId = $request->post('identity');
        $tutorSex = $request->post('sex');
        $tutorCraft = $request->post('craft');
        $tutorPhone = $request->post('phone');
        $tutorCompany = $request->post('companyAccount');
        $tutorProfile = $request->post('profile');
        $result = \Yii::$app->db->createCommand()->update('tutor_info',
            ['identity'=>$tutorId,
            'tutorName'=>$tutorName,
            'sex'=>$tutorSex,
            'profile'=>$tutorProfile,
            'craft'=>$tutorCraft,
            'companyAccount'=>$tutorCompany,
            'phone'=>$tutorPhone],
            'identity=:tutorId',[':tutorId' => $tutorId])
        ->execute();
        return array("data"=>$result,"msg"=>"success");

    }

//**********公司登陆    
    public function actionGetdata()
    {
        $request = \Yii::$app->request;
        $companyAccount = $request->post('companyAccount');

        $query = (new Query())
            ->select('*')
            ->from('tutor_info')
            ->andWhere(['companyAccount' => $companyAccount])
            ->andWhere(['status' => 1])
            ->all();

        // $query1 = (new Query())
        //     ->select('*')
        //     ->from('tutor_info')
        //     ->andWhere(['companyAccount' => $companyAccount])
        //     ->andWhere(['status' => 1]);

        // $countQuery = clone $query1;
        // $totalCount = $countQuery->count();
        // $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>'8']);
        // $models = $query1->offset($pages->offset)
        //     ->limit($pages->limit)
        //     ->all();   
        // $pageNum = ceil($totalCount/8);
        // return array("data"=>[$query,$pageNum],"msg"=>"success");

        return array("data"=>$query,"msg"=>"success");
    }

    //插入数据，不存在相同数据则插入
    public function actionInserttutor()
    {
        $request = \Yii::$app->request;
        $name = $request->post('name');
        $identity = $request->post('identity');
        $craft = $request->post('craft');
        $companyAccount = $request->post('companyAccount');
        $sex = $request->post('sex');
        $phone = $request->post('phone');
        $profile = $request->post('profile');

        $result = TutorInfo::find()
            ->andWhere(['identity'=>$identity])
            ->andWhere(['status'=>1])
            ->one();
        if ($result == null) {
            $result1 = \Yii::$app->db->createCommand()->insert('tutor_info',
                        array(
                            'tutorName'=>$name, 
                            'identity'=>$identity, 
                            'craft'=>$craft, 
                            'companyAccount'=>$companyAccount, 
                            'sex'=>$sex, 
                            'phone'=>$phone, 
                            'profile'=>$profile, 
                            'status'=>1
                        )
                    )->execute();
            if ($result1 == 1){ 
                $password_hash = \Yii::$app->security->generatePasswordHash(123456);
                $user = \Yii::$app->db->createCommand()->insert('users',
                  array(
                    'username'=>$identity,   
                    'password_hash'=>$password_hash,
                    'status'=>1
                    )
                  )->execute();
                // return array("data"=>$user,"msg"=>"数据不为空");
                if($user == 1){
                  return array("data"=>$user,"msg"=>"添加成功");
                }else{
                  return "添加权限失败";
                }
            }else{ 
                return "导师添加失败";
            }
        } else {
            return "failure";
        }

    }
    //删除数据，根据专业Id来删除数据
    public function actionDeletetutor()
    {
        $request = \Yii::$app->request;
        $tutorId = $request->post('tutorId');
        $result = \Yii::$app->db->createCommand()
                ->update('tutor_info',['status'=>0],'id=:tutorId',
                          [':tutorId' => $tutorId])->execute();
        /*$result = \Yii::$app->db->createCommand()
                 ->update('sys_power',['comAccount'=>0,'status'=>0],
                          'companyId=:companyId',[':companyId' => $companyId])
                  ->execute();*/
         return $result;
    }

    public function actionGetedittutor()
    {
        $request = \Yii::$app->request;
        $tutorId = $request->post('tutorId');
        $result = TutorInfo::find()->where(['id' => $tutorId])->one();
        if ($result==null) {
            return "failure";
        } else {
            //$teacherInfo = TeacherInfo::find()->where(['majorId' => $majorId])->all();
            return array("data"=>$result,"msg"=>"success");
        }
    }

    //修改数据
    public function actionUpdatetutor()
    {
        $request = \Yii::$app->request;
        $tutorId = $request->post('tutorId');
        $companyAccount = $request->post('companyAccount');
        $name = $request->post('name');
        $identity = $request->post('identity');
        $craft = $request->post('craft');
        $sex = $request->post('sex');
        $phone = $request->post('phone');
        $profile = $request->post('profile');

        $result = \Yii::$app->db->createCommand()->update('tutor_info',
            ['tutorName'=>$name,
             'identity'=>$identity,
             'craft'=>$craft,
             'sex'=>$sex,
             'phone'=>$phone,
             'profile'=>$profile],'id=:tutorId',[':tutorId' => $tutorId])->execute();
        return array("data"=>$result,"msg"=>"success");
    }

    public function actionQuerycompany()
    {
        $request = \Yii::$app->request;
        $companyName = $request->post('companyName');
        $query = (new Query())
            ->select('*')
            ->from('company_info')
            ->andWhere(['comName' => $companyName])
            ->andWhere(['status' => 1])
            ->all();
       //$companyId = $query[0]['id'];
       /* $query = (new Query())
            ->select('*')
            ->from('vw_major_info')
            ->andWhere(['id' => $majorId])
            ->andWhere(['super' => 2])
            ->andWhere(['majorStatus' => 1])
            ->all();*/
        $query1 = (new Query())
            ->select('*')
            ->from('company_info')
            //->andWhere(['id' => $companyId])
            ->andWhere(['status' => 1]);

        $countQuery = clone $query1;
        $totalCount = $countQuery->count();
        $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>'8']);
        $models = $query1->offset($pages->offset)
            ->limit($pages->limit)
            ->all();   
        $pageNum = ceil($totalCount/8);
        return array("data"=>[$query,$pageNum],"msg"=>"success");
    }
}