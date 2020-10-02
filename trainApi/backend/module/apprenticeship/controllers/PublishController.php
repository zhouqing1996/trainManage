<?php
namespace backend\module\requirement\controllers;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\SendEmail;
use common\models\SurveyInfo;
use common\models\CompanyInfo;
use common\models\RecruitmentInfo;

// use common\models\SurveyQuestions;

class PublishController extends Controller
{
    public function actionIndex()
    {
        return "问卷管理首页";
    }

    public function actionGetdata(){
    	$request = \Yii::$app->request;
        $query = (new Query())
            ->select('*')
            ->from('vw_class_info')
            ->andWhere(['status' => 1])
            ->all();
        $query1 = (new Query())
            ->select('*')
            ->from('vw_class_info')
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

    public function actionGetstudent(){
    	$request = \Yii::$app->request;
    	$classId = $request->post('classId');
    	$emailArr = [];
    	foreach ($classId as $key => $value) {
    		$query = (new Query())
            ->select('email')
            ->from('student_info')
            ->andWhere(['classId' => $value])
            ->andWhere(['status' => 1])
            ->all();
            foreach ($query as $key1 => $value1) {
            	array_push($emailArr, $value1['email']);
            }
    	}
        
        // $query1 = (new Query())
        //     ->select('*')
        //     ->from('student_info')
        //     ->andWhere(['classId' => $classId])
        //     ->andWhere(['status' => 1]);

        // $countQuery = clone $query1;
        // $totalCount = $countQuery->count();
        // $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>'8']);
        // $models = $query1->offset($pages->offset)
        //     ->limit($pages->limit)
        //     ->all();   
        // $pageNum = ceil($totalCount/8);
        return array("data"=>$emailArr,"msg"=>"success");
    }

     public function actionGetcompany(){
    	$request = \Yii::$app->request;
        $query = (new Query())
            ->select('*')
            ->from('company_info')
            ->andWhere(['status' => 1])
            ->all();
        return array("data"=>$query,"msg"=>"success");
    }

    public function actionGetteacher(){
    	$request = \Yii::$app->request;
        $query = (new Query())
            ->select('*')
            ->from('teacher_info')
            ->andWhere(['status' => 1])
            ->all();
        return array("data"=>$query,"msg"=>"success");
    }

    //发送邮件
    public function actionGetemail()
    {
    	$request = \Yii::$app->request;
    	$to = $request->post('address');
    	$content = $request->post('content');
        $return=SendEmail::sendMail($to,'您有一份问卷需要填写！',$content,'大同幼师学校');
        return array("msg"=>$return,"data"=>true);
    }

    public function actionSendemail()
    {
        $request = \Yii::$app->request;
        $student = $request->post('student');
        $content = $request->post('content');
        $emailArr = [];
        foreach ($student as $key => $value) {
            array_push($emailArr, $value['email']);
        }
        $return=SendEmail::sendMail($emailArr,'您有一份问卷需要填写！',$content,'大同幼师学校');
        return array("msg"=>$return,"data"=>true);
    }

    public function getdata($id)
    {
        $data = SurveyInfo::find()->where(['id'=>$id])->select('content,pushId')->one();
        if ($data['pushId']) {
            $r = strpos($data['pushId'],',')?explode(',',$data['pushId']):[$data['pushId']];
            foreach ($r as $key => $value) {
                $comId = RecruitmentInfo::find()->where(['id'=>$value])->select('comId')->one()['comId'];
                $push[$key] = CompanyInfo::find()->where(['id'=>$comId])->select('comName')->one()['comName'];
            }
        } else {
            $push = [];
        }
        $a['push'] = $push;
        $a['content'] = $data['content'];
        return $a;
    }

    //获取实习发送数据
    public function actionGetpractice()
    {
        $id = Yii::$app->request->post("id");
        $a = $this->getdata($id);
        return array("msg"=>'获取实习发送数据',"data"=>$a);
    }

    //提交
    public function actionSubmitpractice()
    {
        $id = Yii::$app->request->post("id");
        $practice = Yii::$app->request->post("practice");
        \Yii::$app->db->createCommand()->update('survey_info', 
                array(  
                    'content'=>$practice['content'], 
                    'pushId'=>implode(",", $practice['push'])
                ), "id=:id",array(':id'=>$id))->execute();
        return array("msg"=>'提交',"data"=>$this->getdata($id));
    }

    public function actionSubmitpractice2()
    {
        $id = Yii::$app->request->post("id");
        $content = Yii::$app->request->post("content");
        $pushId = Yii::$app->request->post("pushId");
        \Yii::$app->db->createCommand()->update('survey_info', 
                array(  
                    'content'=>$content, 
                    'pushId'=>$pushId
                ), "id=:id",array(':id'=>$id))->execute();
        return array("msg"=>'提交',"data"=>1);
    }

    //设置
    public function actionSetpractice()
    {
        $request = \Yii::$app->request;
        $id = $request->post("id");
        $practice = $request->post("practice");
        $practiceComArr = $practice['push'];
        $stuIdArr='';
        foreach ($practiceComArr as $key => $value) {
            $recruitment = RecruitmentInfo::find()->where(['id'=>$value])->select('*')->one();
            $stuIdArr .= ',';
            $stuIdArr .= $recruitment['arrangeStuId'];
        }
        \Yii::$app->db->createCommand()->update('survey_info', 
                array(  
                    'forId'=>$stuIdArr
                ), "id=:id",array(':id'=>$id))->execute();
        return array("msg"=>'提交',"data"=>$this->getdata($id));
    }

    public function actionSetpractice2()
    {
        $request = \Yii::$app->request;
        $id = $request->post("id");
        $student = $request->post("student");
        $stuId = [];
        foreach ($student as $key => $value) {
            array_push($stuId,$value['id']);
        }
        \Yii::$app->db->createCommand()->update('survey_info', 
                array(  
                    'forId'=>count($stuId)==1?$stuId[0]:implode(",", $stuId)
                ), "id=:id",array(':id'=>$id))->execute();
        return array("msg"=>'提交',"data"=>$this->getdata($id));
    }
}