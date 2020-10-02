<?php

namespace backend\module\gengangpractice\controllers;
use Yii;
use yii\web\Controller;
use backend\models\gengangpractice\RecruitModel;
use common\models\RecruitmentInfo;
use common\models\StudentInfo;
/**
 * Default controller for the `system` module
 */
class RecruitmentController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return "招聘信息";
    }

    //招聘信息列表
    public function actionData()
    {
        $model = new RecruitModel();
        $power = Yii::$app->request->post("power");
      
        if ($power['userkind'] == '1') {
            if ($power['super'] == '1') {
                $list=$model->getList('root',1);
            } else {
                $list=$model->getList('teacher',$power['username']);
            }           
        } else {
            $list=$model->getList('company',$power['username']);
        }
    
   
        return array("msg"=>"招聘信息","data"=>$list);
    }

    //获取所有专业
    public function actionMajor()
    {
        $model = new RecruitModel();
        return array("msg"=>"专业","data"=>$model->getMajor());
    }

///    //获取所有的年级
    public function actionGrade()
    {
        $model=new RecruitModel();
        return array("msg"=>"年级","data"=>$model->getGrade());
    }

    //修改招聘信息（待审核的才能修改）
    public function actionModify()
    {
        $model = new RecruitModel();
        $row = Yii::$app->request->post("row");
        $id = $row["id"];
        $subject = $row["subject"];
        $content = $row["content"];
        $startDate = $row["date"][0];
        $endDate = $row["date"][1];
        $major = $row["major"];
        //
        $grade=$row["grade"];
        $num = $row["num"];
        return array("msg"=>"修改招聘信息","data"=>$model->modify($id,$subject,$content,$startDate,$endDate,$major,$grade,$num));
    }

    //删除招聘信息（已审核的不能删除）
    public function actionDelete()
    {
        $model = new RecruitModel();
        $id = Yii::$app->request->post("id");
        return array("msg"=>"删除招聘信息","data"=>$model->delete($id));
    }

    //修改状态
    public function actionModstatus()
    {
        $model = new RecruitModel();
        $id = Yii::$app->request->post("id");
        $status = Yii::$app->request->post("status");
        return array("msg"=>"修改状态","data"=>$model->modstatus($id,$status));
    }

    //获取所有企业
    public function actionCompany()
    {
        $model = new RecruitModel();
        $power = Yii::$app->request->post("power");
        return array("msg"=>"企业","data"=>$model->getCompany($power));
    }

    //发布招聘信息
    public function actionAddrecruit()
    {
        $model = new RecruitModel();
        $form = Yii::$app->request->post("form");
        $major = Yii::$app->request->post("major");
        $company = Yii::$app->request->post("company");
        //
        $grade = Yii::$app->request->post("grade");
        return array("msg"=>"发布招聘信息","data"=>$model->addRecruit($form,$major,$grade,$company));
    }

    //修改状态
    public function actionModifystatus()
    {
        $model = new RecruitModel();
        $id = Yii::$app->request->post("id");
        $status = Yii::$app->request->post("status");
        $date = Yii::$app->request->post("date");
        return array("msg"=>"修改状态","data"=>$model->modifystatus($id,$status,$date));
    }

    //获取所有教师
    public function actionGetteacher()
    {
        $model = new RecruitModel();
        return array("msg"=>"获取所有教师","data"=>$model->getteacher());
    }

    //获取申请实习的学生
    public function actionGettstudent()
    {
        $model = new RecruitModel();
        $id = Yii::$app->request->post("id");
        $major = Yii::$app->request->post("major");
        return array("msg"=>"获取申请实习的学生","data"=>$model->gettstudent($id,$major));
    }

    //安排实习学生
    public function actionArrangest()
    {
        $model = new RecruitModel();
        $id = Yii::$app->request->post("id");
        $student = Yii::$app->request->post("student");
        return array("msg"=>"安排实习学生","data"=>$model->arrangeSt($student,$id));
    }

    //添加地图等信息
    public function actionAddmap()
    {
        $model = new RecruitModel();
        $rowId = Yii::$app->request->post("rowId");
        $form = Yii::$app->request->post("form");
        $lng = Yii::$app->request->post("lng");
        $lat = Yii::$app->request->post("lat");
        $address = Yii::$app->request->post("address");
        return array("msg"=>"添加地图等信息","data"=>$model->addmap($rowId,$form,$lng,$lat,$address));
    }

    //获取申请实习的学生
    public function actionGetapplystudent()
    {
        $model = new RecruitModel();
        $id = Yii::$app->request->post("id");
        $major = Yii::$app->request->post("major");
        return array("msg"=>"获取申请实习的学生","data"=>$model->gettstudent($id,$major));
    }

    //获取申请实习的学生
    public function actionGetapplystu()
    {
        $stuInfos = [];
        $id = Yii::$app->request->post("id");
        foreach ($id as $key => $value) {
            $stuInfos[$key] = [];
            $stuInfo = [];
            $recruitment = RecruitmentInfo::find()->andWhere(['id' => $value])
                ->select('studentId')
                ->one();
            $stuArr = explode(',', $recruitment['studentId']);
            foreach ($stuArr as $key1 => $value1) {
                $stu = StudentInfo::find()->andWhere(['id' => $value1])
                    ->select('*')
                    ->one();
                array_push($stuInfo, $stu);
            }
            array_push($stuInfos[$key], $stuInfo);
        }
        
        return array("msg"=>"获取申请实习的学生","data"=>$stuInfos);
    }
}
