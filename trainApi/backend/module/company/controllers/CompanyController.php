<?php
namespace backend\module\company\controllers;

use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\CompanyInfo;

class CompanyController extends Controller
{
    public function actionIndex()
    {
        return "企业管理首页";
    }

    public function actionGetdata()
    {

        $request = \Yii::$app->request;
        $query = (new Query())
            ->select('*')
            ->from('company_info')
            ->andWhere(['status' => 1])
            ->all();
            
        $query1 = (new Query())
            ->select('*')
            ->from('company_info')
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
    //获取公司简介信息
    public function actionGetdetail()
    {
        $request = \Yii::$app->request;
        $companyId = $request->post('companyId');
        $query = (new Query())
            ->select('*')
            ->from('company_info')
            ->andWhere(['id' => $companyId ])
            ->andWhere(['status' => 1])
            ->one();
        return array("data"=>$query,"msg"=>"success");
    }

     //插入数据，不存在相同数据则插入
    public function actionInsertcompany()
    {
        $request = \Yii::$app->request;
        $companyName = $request->post('companyName');
        $companyAccount = $request->post('companyAccount');
        $companyAddress = $request->post('companyAddress');
        $companyPhone = $request->post('companyPhone');
        $companyEmail = $request->post('companyEmail');
        $companyIntro = $request->post('companyIntro');

        $result1 = CompanyInfo::find()
            ->andWhere(['comAccount'=>$companyAccount])
            ->andWhere(['status'=>1])
            ->one();

        if ($result1 == null) {
            $result2 = \Yii::$app->db->createCommand()->insert('company_info',
                array(
                    'comName'=>$companyName, 
                    'comAccount'=>$companyAccount,
                    'comAddress'=>$companyAddress,
                    'comPhone'=>$companyPhone,
                    'comEmail'=>$companyEmail,
                    'introdution'=>$companyIntro,
                    'status'=>1
                )
            )->execute();
            if ($result2 == 1) {
                $password_hash=\Yii::$app->security->generatePasswordHash(123456);
                $user = \Yii::$app->db->createCommand()->insert('users',
                    array(
                        'username'=>$companyAccount,   
                        'password_hash'=>$password_hash,
                        'status'=>1)
                    )->execute();
                if($user == 1){
                    $power = \Yii::$app->db->createCommand()->insert('sys_power',
                        array(
                            'username'=>$companyAccount,  
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

    //删除数据，根据专业Id来删除数据
    public function actionDeletecompany()
    {
        $request = \Yii::$app->request;
        $companyId = $request->post('companyId');
        $result = \Yii::$app->db->createCommand()
            ->update('company_info',['status'=>0],'id=:companyId',
            [':companyId' => $companyId])->execute();
        return $result;
    }

    //获取编辑数据
    public function actionGeteditcompany()
    {
        $request = \Yii::$app->request;
        $companyId = $request->post('companyId');
        $result = CompanyInfo::find()->where(['id' => $companyId])->one();
        if ($result==null) {
            return "failure";
        } else {
            //$teacherInfo = TeacherInfo::find()->where(['majorId' => $majorId])->all();
            return array("data"=>[$result],"msg"=>"success");
        }
    }
    public function actionGeteditacompany()
    {
        $request = \Yii::$app->request;
        $companyAccount = $request->post('companyAccount');
        $result = CompanyInfo::find()->where(['comAccount' => $companyAccount])->one();
        if ($result==null) {
            return "failure";
        } else {
            //$teacherInfo = TeacherInfo::find()->where(['majorId' => $majorId])->all();
            return array("data"=>[$result],"msg"=>"success");
        }
    }

    //修改数据
    public function actionAltercompany()
    {
        $request = \Yii::$app->request;
        $companyId = $request->post('companyId');
        $companyName = $request->post('companyName');
        $companyAddress = $request->post('companyAddress');
        $companyPhone = $request->post('companyPhone');
        $companyEmail = $request->post('companyEmail');
        $companyIntro = $request->post('companyIntro');
        $result = \Yii::$app->db->createCommand()->update('company_info',
            ['id'=>$companyId,
             'comName'=>$companyName,
             'comAddress'=>$companyAddress,
             'comPhone'=>$companyPhone,
             'introdution'=>$companyIntro,
             'comEmail'=>$companyEmail],'id=:companyId',[':companyId' => $companyId])->execute();
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