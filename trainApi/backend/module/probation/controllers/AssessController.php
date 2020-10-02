<?php
namespace backend\module\probation\controllers;

use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\RecruitmentInfo; 
use common\models\AnswerSource;
use common\models\Summary;

class AssessController extends Controller
{
	public function actionIndex()
    {
        return "评价管理首页111";
    }

    public function actionGetdata()
    {
        $request = \Yii::$app->request;
        $now =  date('Y-m-d', time());
        $practiceInfo = RecruitmentInfo::find()
             ->andWhere(['status' => 3])
             ->andWhere(['NOT', ['arrangeStuId' => null]])
             ->andWhere(['<=', 'recruitStart', $now])
             ->andWhere(['>=', 'recruitEnd', $now])
             ->orderBy(['majorId'=>SORT_ASC])
             ->all();
        $stuInfo = [];
        $summary = [];
        $i = 0;
        foreach ($practiceInfo as $key => $value) {
        	$stuIdArr=explode(',',$value['arrangeStuId']);
        	foreach ($stuIdArr as $key1 => $value1) {
        		$stuInfo[$i] = (new Query())
            		->select('*')
            		->from('vw_student_info')
            		->andWhere(['id' => $value1])
                    ->all();
                $summary[$i] = (new Query())
            		->select('*')
            		->from('summary')
            		->andWhere(['stuId' => $value1])
                    ->andWhere(['in', 'type', [1, 2, 3]]) 
            		->andWhere(['status' => 1])
                    ->all();
                $i++;
        	}
        }
        $info = [];
        $info['stuInfo'] = $stuInfo;
        $info['summary'] = $summary;
        return array("data"=> $info,"msg"=>"success");
    }

    public function actionGetdata2()
    {
        $recruitId = \Yii::$app->request->post("recruitId");
        $practiceInfo = RecruitmentInfo::find()->where(['id'=>$recruitId])->select('arrangeStuId')->one()['arrangeStuId'];
        $stu = strpos($practiceInfo,',')?explode(',',$practiceInfo):[$practiceInfo];
        $stuInfo = [];
        $summary = [];
        $i = 0;
        foreach ($stu as $key => $value) {
            $stuInfo[$i] = (new Query())
                ->select('*')
                ->from('vw_student_info')
                ->andWhere(['id' => $value])
                ->all();
            $summary[$i] = (new Query())
                ->select('*')
                ->from('summary')
                ->andWhere(['stuId' => $value])
                ->andWhere(['in', 'type', [1, 2, 3]]) 
                ->andWhere(['status' => 1])
                ->all();
            $i++;
        }
        $info = [];
        $info['stuInfo'] = $stuInfo;
        $info['summary'] = $summary;
        return array("data"=> $info,"msg"=>"success");
    }

    public function actionSummary()
    {
        $request = \Yii::$app->request;
        $stuId = $request->post('stuId');
        $summary = (new Query())
            ->select('*')
            ->from('summary')
            ->andWhere(['stuId' => $stuId])
            ->andWhere(['in','type',[1,2,3]])
            ->andWhere(['status' => 1])
            ->all();

        $majorEvaluate = (new Query())
            ->select('*')
            ->from('summary')
            ->andWhere(['stuId' => $stuId])
            ->andWhere(['type' => 4])
            ->andWhere(['status' => 1])
            ->one();
        $comEndEva = (new Query())
            ->select('*')
            ->from('summary')
            ->andWhere(['stuId' => $stuId])
            ->andWhere(['type' => 5])
            ->andWhere(['status' => 1])
            ->one();

        $stuSurvey = (new Query())
            ->select('*')
            ->from('survey_info')
            ->andWhere(['NOT', ['forId' => null]])
            ->andWhere(['type' => 20])
            ->andWhere(['<>','status', 0])
            ->all();

        $stuSurvey1=[];
        foreach ($stuSurvey as $key => $value) {
            $forId = explode(',',$value['forId']);
            foreach ($forId as $key1 => $value1) {
                if ($value1==$stuId) {
                    array_push($stuSurvey1, $value);
                }
            }
        }
        return array("data"=>[$summary,$majorEvaluate,$comEndEva,$stuSurvey1] ,"msg"=>"success");
    }

    public function actionEndsummary()
    {
        $request = \Yii::$app->request;
        $stuId = $request->post('stuId');

        $majorEvaluate = (new Query())
            ->select('*')
            ->from('summary')
            ->andWhere(['stuId' => $stuId])
            ->andWhere(['type' => 4])
            ->andWhere(['status' => 1])
            ->one();
        $comEndEva = (new Query())
            ->select('*')
            ->from('summary')
            ->andWhere(['stuId' => $stuId])
            ->andWhere(['type' => 5])
            ->andWhere(['status' => 1])
            ->one();
        // $recruitment = RecruitmentInfo::find()
        //     ->select('*')
        //     ->andWhere(['status' => 3])
        //     ->all();
        // foreach ($recruitment as $key => $value) {
        //     # code...
        // }
        //     ->andWhere(['NOT', ['forId' => null]])
        //     ->andWhere(['type' => 20])
        //     ->andWhere(['status' => 1])
        //     ->all();
        $stuSurvey = (new Query())
            ->select('*')
            ->from('survey_info')
            // ->andWhere(['in', 'forId', $stuId) 
            ->andWhere(['NOT', ['forId' => null]])
            ->andWhere(['type' => 20])
            ->andWhere(['<>','status', 0])
            // ->andWhere(['status' => 1])
            ->all();

        $stuSurvey1=[];
        foreach ($stuSurvey as $key => $value) {
            $forId = explode(',',$value['forId']);
            // return array("data"=>[$majorEvaluate,$comEndEva,$forId] ,"msg"=>"success");
            foreach ($forId as $key1 => $value1) {
                if ($value1==$stuId) {
                    array_push($stuSurvey1, $value['id']);
                }
            }
        }
        return array("data"=>[$majorEvaluate,$comEndEva,$stuSurvey1] ,"msg"=>"success");
    }

    //编辑问卷信息
    public function actionInsertsummary()
    {
        $request = \Yii::$app->request;
        $summaryId = $request->post('summaryId');
        $evaluate = $request->post('evaluate');
        $flag = $request->post('flag');
        if ($flag==1) {
            $result = \Yii::$app->db->createCommand()->update('summary',
            ['teacherEvaluate'=>$evaluate],'id=:summaryId',[':summaryId' => $summaryId])->execute();
        }else{
            $result = \Yii::$app->db->createCommand()->update('summary',
            ['companyEvaluate'=>$evaluate],'id=:summaryId',[':summaryId' => $summaryId])->execute();
        }

        if ($result==1) {
            return $result;
            // return array("data"=>[$key,$value],"msg"=>"success");
        }else{
            return false;
        }
    }
    //编辑问卷信息
    public function actionInsertevaluate()
    {
        $request = \Yii::$app->request;
        $stuId = $request->post('stuId');
        $evaluate = $request->post('evaluate');
        $flag = $request->post('flag');
        if ($flag==4) {
            $res = Summary::find()->andWhere(['stuId'=>$stuId])->andWhere(['status'=>1])->andWhere(['type'=>4])->andWhere(['NOT', ['majorEvaluate' => null]])->one()['id'];
            if ($res == null) {
                $result=\Yii::$app->db->createCommand()->insert('summary',
                        array(
                            'stuId'=>$stuId, 
                            'majorEvaluate'=>$evaluate, 
                            'type'=>$flag, 
                            'status'=>1
                        )
                    )->execute();
                return $result;
            }else {
                $result = \Yii::$app->db->createCommand()->update('summary',
                ['majorEvaluate'=>$evaluate],'id=:res',[':res' => $res])->execute();
                return $result;
            }
        }else if($flag==5){
            $res = Summary::find()->andWhere(['stuId'=>$stuId])->andWhere(['status'=>1])->andWhere(['type'=>5])->andWhere(['NOT', ['comEndEva' => null]])->one()['id'];
            if ($res == null) {
                $result=\Yii::$app->db->createCommand()->insert('summary',
                        array(
                            'stuId'=>$stuId, 
                            'comEndEva'=>$evaluate, 
                            'type'=>$flag, 
                            'status'=>1
                        )
                    )->execute();
                return $result;
            }else {
                $result = \Yii::$app->db->createCommand()->update('summary',
                ['comEndEva'=>$evaluate],'id=:res',[':res' => $res])->execute();
                return $result;
            }
        }
    }

    public function actionGettable()
    {

        $request = \Yii::$app->request;
        $username = $request->post('username');
        $page = $request->post('page');
        $query = (new Query())
            ->select('*')
            ->from('survey_info')
            ->andWhere(['<>','status', 0])
            ->andWhere(['type' => 20])
            ->andWhere(['username' => $username])
            ->orderBy('addTime DESC')
            ->all();
        $ansNum = [];
        foreach ($query as $key => $value) {
            $surveyId = $value['id'];
            $answer = AnswerSource::find()->andWhere(['surveyId'=>$surveyId])
                        ->andWhere(['status'=>1])
                        ->groupBy('ip')
                        ->all();
            $ansNum[$key] = count($answer);
        }
        $query1 = (new Query())
            ->select('*')
            ->from('survey_info')
            ->andWhere(['<>','status', 0])
            ->andWhere(['type' => 20])
            ->andWhere(['username' => $username])
            ->orderBy('addTime DESC');

        $countQuery = clone $query1;
        $totalCount = $countQuery->count();
        $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>'8']);
        $models = $query1->offset($pages->offset)
            ->limit($pages->limit)
            ->all();   
        $pageNum = ceil($totalCount/8);
        return array("data"=>[$query,$ansNum,$pageNum],"msg"=>"success");
    }

    public function actionGettable2()
    {

        $request = \Yii::$app->request;
        $username = $request->post('username');
        $page = $request->post('page');
        $query = (new Query())
            ->select('*')
            ->from('survey_info')
            ->andWhere(['<>','status', 0])
            ->andWhere(['type' => 20])
            ->andWhere(['username' => $username])
            ->orderBy('addTime DESC')
            ->all();
        $ansNum = [];
        foreach ($query as $key => $value) {
            $surveyId = $value['id'];
            $answer = AnswerSource::find()->andWhere(['surveyId'=>$surveyId])
                        ->andWhere(['status'=>1])
                        ->all();
            $ansNum[$key] = count($answer);
        }
        $query1 = (new Query())
            ->select('*')
            ->from('survey_info')
            ->andWhere(['<>','status', 0])
            ->andWhere(['type' => 20])
            ->andWhere(['username' => $username])
            ->orderBy('addTime DESC');

        $countQuery = clone $query1;
        $totalCount = $countQuery->count();
        $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>'8']);
        $models = $query1->offset($pages->offset)
            ->limit($pages->limit)
            ->all();   
        $pageNum = ceil($totalCount/8);
        return array("data"=>[$query,$ansNum,$pageNum],"msg"=>"success");
    }

    public function actionGettable3()
    {
        $request = \Yii::$app->request;
        $username = $request->post('username');
        $power = $request->post('power');
        $recruitId = $request->post('recruitId');
        $query = (new Query())
            ->select('*')
            ->from('survey_info')
            ->andWhere(['<>','status', 0])
            ->andWhere(['type' => 20])
            ->andWhere(['username' => $username])
            ->orderBy('addTime DESC')
            ->all();
        $query1 = [];
        foreach ($query as $key => $value) {
            if ($value['pushId'] == $recruitId) {
                array_push($query1,$value);
            }
        }
        $ansNum = [];
        foreach ($query1 as $key => $value) {
            $surveyId = $value['id'];
            $answer = AnswerSource::find()->andWhere(['surveyId'=>$surveyId])
                        ->andWhere(['status'=>1])
                        ->all();
            $ansNum[$key] = count($answer);
        }
        return array("data"=>[$query1,$ansNum],"msg"=>"success");
    }
}