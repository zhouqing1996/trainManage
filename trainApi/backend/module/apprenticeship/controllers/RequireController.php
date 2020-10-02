<?php
namespace backend\module\apprenticeship\controllers;

use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\SurveyInfo;
use common\models\SurveyQuestions;
use common\models\SurveyAnswers;
use common\models\AnswerSource;
use common\models\CompanyInfo;

class RequireController extends Controller
{
    public function actionIndex()
    {
        return "问卷管理首页";
    }

    public function actionGetdata()
    {

        $request = \Yii::$app->request;
        $username = $request->post('username');
        $page = $request->post('page');
        $query = (new Query())
            ->select('*')
            ->from('survey_info')
            ->andWhere(['<>','status', 0])
            ->andWhere(['type' => 30])
            ->andWhere(['username' => $username])
            ->orderBy('id DESC')
            ->all();

        $query1 = (new Query())
            ->select('*')
            ->from('survey_info')
            ->andWhere(['<>','status', 0])
            ->andWhere(['type' => 30])
            ->andWhere(['username' => $username])
            ->orderBy('id DESC');

        $countQuery = clone $query1;
        $totalCount = $countQuery->count();
        $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>'8']);
        $models = $query1->offset($pages->offset)
            ->limit($pages->limit)
            ->all();   
        $pageNum = ceil($totalCount/8);
        return array("data"=>[$query,$pageNum],"msg"=>"success");
    }

    //插入数据，不存在相同数据则插入
    public function actionInsertsurvey()
    {
        $request = \Yii::$app->request;
        $username = $request->post('username');
        $queData = $request->post('quData');
        $title = $queData['title'];
        $temType = $queData['type'];
        $questions = $queData['questions'];
        $addTime = date('Y-m-d h:i:s', time());
        $result1 = \Yii::$app->db->createCommand()->insert('survey_info',
                        array(
                            'title'=>$title, 
                            'addTime'=>$addTime,
                            'type'=>$temType,
                            'username'=>$username,
                            'status'=>4
                        )
                    )->execute();
            $id=\Yii::$app->db->getLastInsertID();
            if ($result1==1) {
                // $data = SurveyInfo::find()->andWhere(['title'=>$title])
                //         ->andWhere(['in' , 'type' , [0,10,20]])
                //         ->one();
                // $id = $data['id'];

                foreach ($questions as $key => $value) {
                    $topic = $value['topic'];
                    $type = $value['type'];
                    $isMandatory = $value['isMandatory'];
                    $optionArr=[];
                    $options='';
                    if ($type=='radio'||$type=='checkbox'||$type=='rate') {
                        $optionArr = $value['options'];
                        $options=implode('---',$optionArr);
                    }elseif ($type=='textarea') {
                        $options = '';
                    }

                    $result2 = \Yii::$app->db->createCommand()
                        ->insert('survey_questions',
                            array(
                                'surveyId'=>$id, 
                                'topic'=>$topic, 
                                'type'=>$type, 
                                'options'=>$options,
                                'isMandatory'=>$isMandatory,
                                'status'=>1
                                )
                        )->execute();
                }
                if ($result2==1) {
                    return array("data"=>$id,"msg"=>"success");
                }else{
                    return $result2;
                }
            }
    }

    public function actionSubmit()
    {
        $request = \Yii::$app->request;
        $surveyId = $request->post('id');
        $userName = $request->post('username');
        $answer = $request->post('answer');
        $questions = $request->post('questions');
        $ip = $request->post('ip');
        $cityname = $request->post('cityname');
        $subTime = date('Y-m-d h:i:s', time());
        $stuId = $request->post('stuId');

        if (!$stuId) {
            // $data = AnswerSource::find()
            //     ->andWhere(['surveyId'=>$surveyId])
            //     ->andWhere(['ip'=>$ip])
            //     ->one();
            // return array("data"=>$questions,"msg"=>"success");
            $data = null;
            if ($data == null) {
                foreach ($questions as $key => $value) {
                    if ($value['type']=='input1') {
                        $result1 = \Yii::$app->db->createCommand()
                        ->insert('survey_questions',
                            array(
                                'surveyId'=>$surveyId, 
                                'topic'=>$value['topic'], 
                                'type'=>'input', 
                                'status'=>1,
                                'direction'=>$userName
                                )
                        )->execute();
                    }else{
                        
                    }
                }

                $query = (new Query())
                    ->select('*')
                    ->from('survey_questions')
                    ->andWhere(['status' => 1])
                    ->andWhere(['surveyId' => $surveyId])
                    ->all();
                // return array("data"=>$query,"msg"=>"success");
                $result2 = \Yii::$app->db->createCommand()->insert('answer_source',
                    array(
                        'surveyId'=>$surveyId, 
                        'ip'=>$ip,
                        'city'=>$cityname,
                        'subTime'=>$subTime,
                        'userName'=>$userName,
                        'status'=>1,
                    )
                )->execute();

                foreach ($answer as $key => $value) {
                    if ($value==null) {
                    }else{
                        $content='';
                        $questionId=$query[$key]['id'];
                        // if (is_array($value)) {
                        //     $content=implode('---',$value);
                        // }else{
                        //     $content=$value;
                        // }
                        $content=$value;
                        $result3 = \Yii::$app->db->createCommand()->insert('survey_answers',
                            array(
                                'ip'=>$ip, 
                                'surveyId'=>$surveyId, 
                                'userName'=>$userName,
                                'questionId'=>$questionId,
                                'content'=>$content,
                                'status'=>1,
                            )
                        )->execute();
                    }
                }
                return $result3;
            }else{
                return false;
            }
        }else{
            return array("data"=>is_array($questions),"msg"=>"success");
            $result = \Yii::$app->db->createCommand()->insert('answer_source',
                    array(
                        'stuId'=>$stuId, 
                        'surveyId'=>$surveyId, 
                        'ip'=>$ip,
                        'city'=>$cityname,
                        'subTime'=>$subTime,
                        'status'=>1,
                    )
                )->execute();
            foreach ($answer as $key => $value) {
                if ($value===null) {
                }else{
                    $content='';
                    $questionId=$key;
                    if (is_array($value)) {
                        $content=implode('---',$value);
                    }else{
                        $content=$value;
                    }
                    $result1 = \Yii::$app->db->createCommand()->insert('survey_answers',
                        array(
                            'ip'=>$ip, 
                            'surveyId'=>$surveyId, 
                            'questionId'=>$questionId,
                            'content'=>$content,
                            'status'=>1,
                            'stuId'=>$stuId
                        )
                    )->execute();
                }
            }
            return $result1;
        }
    }

    public function actionGetcomdata()
    {

        $request = \Yii::$app->request;
        $username = $request->post('username');
        // $page = $request->post('page');
        $flag = $request->post('flag');
        $comArr = (new Query())
            ->select('*')
            ->from('survey_info')
            ->andWhere(['type' => $flag])
            ->andWhere(['status' => 2])
            ->all();
        
        $surveycom = [];
        foreach ($comArr as $key => $value) {
            $com = $value['thanksMsg'];
            $var = explode(",",$com);
            foreach ($var as $key1 => $value1) {
                if ($value1 == $username) {
                    array_push($surveycom, $value);
                }
            }
        }
        return array("data"=>$surveycom,"msg"=>"success");

        // $query = (new Query())
        //     ->select('*')
        //     ->from('survey_info')
        //     ->andWhere(['<>','status', 0])
        //     ->andWhere(['type' => $flag])
        //     ->andWhere(['username' => $username])
        //     ->orderBy('id DESC')
        //     ->all();
        // $ansNum = [];
        // foreach ($query as $key => $value) {
        //     $surveyId = $value['id'];
        //     $answer = AnswerSource::find()->andWhere(['surveyId'=>$surveyId])
        //                 ->andWhere(['status'=>1])
        //                 ->groupBy('ip')
        //                 ->all();
        //     $ansNum[$key] = count($answer);
        // }

        // $query1 = (new Query())
        //     ->select('*')
        //     ->from('survey_info')
        //     ->andWhere(['<>','status', 0])
        //     ->andWhere(['type' => $flag])
        //     ->andWhere(['username' => $username])
        //     ->orderBy('id DESC');

        // $countQuery = clone $query1;
        // $totalCount = $countQuery->count();
        // $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>'8']);
        // $models = $query1->offset($pages->offset)
        //     ->limit($pages->limit)
        //     ->all();   
        // $pageNum = ceil($totalCount/8);
        // return array("data"=>[$query,$ansNum,$pageNum],"msg"=>"success");
    }

    //插入答卷数据，不存在相同数据则插入
    // public function actionSubmit()
    // {
    //     $request = \Yii::$app->request;
    //     $surveyId = $request->post('id');
    //     $answer = $request->post('answer');
    //     foreach ($answer as $key => $value) {
    //         if ($value===null) {
    //         }else{
    //             $content='';
    //             $questionId=$key;
    //             if (is_array($value)) {
    //                 $content=implode('---',$value);
    //             }else{
    //                 $content=$value;
    //             }
    //             $result1 = \Yii::$app->db->createCommand()->insert('survey_answers',
    //                 array(
    //                     'surveyId'=>$surveyId, 
    //                     'questionId'=>$questionId,
    //                     'content'=>$content,
    //                     'status'=>1,
    //                 )
    //             )->execute();
    //         }
    //     }
    //     return $result1;
    // }

    // public function actionGetanswer()
    // {
    //     $request = \Yii::$app->request;
    //     $surveyId = $request->post('id');
    //     $answerInfo = SurveyAnswers::find()
    //         ->select('*')
    //         ->andWhere(['surveyId' => $surveyId])
    //         ->andWhere(['<>','status', 0])
    //         ->all();
    //     return array("data"=>$answerInfo,"msg"=>"success");
    // }

    public function actionStatistics()
    {
        $request = \Yii::$app->request;
        $surveyId = $request->post('surveyId');
        $stuId = $request->post('stuId');
        $userArr=[];
        if (!$stuId) {
            $info = [];
            $surveyInfo = SurveyInfo::find()
                ->select('*')
                ->andWhere(['id' => $surveyId])
                ->andWhere(['<>','status', 0])
                ->one();
            $questionInfo = SurveyQuestions::find()
                ->select('*')
                ->andWhere(['surveyId' => $surveyId])
                ->andWhere(['status' => 1])
                ->all();
            
            $info['surveyInfo'] = $surveyInfo;
            $info['questions'] = $questionInfo;
            $info['answerInfo'] = [];
            $info['answerNum'] = [];
            foreach ($questionInfo as $key => $value) {
                $userArr[$key]=[];
                $optionInfo = [];
                $questionId = $value['id'];
                $isMandatory = $value['isMandatory'];
                $answerInfo = SurveyAnswers::find()
                    ->select('*')
                    ->andWhere(['questionId' => $questionId])
                    ->andWhere(['status' => 1])
                    ->all();

                foreach ($answerInfo as $key1 => $value1) {
                    $username = $value1['userName'];
                    $comInfo = CompanyInfo::find()
                        ->select('*')
                        ->andWhere(['comAccount' => $username])
                        ->andWhere(['status' => 1])
                        ->one(); 
                    array_push($userArr[$key], $comInfo['comName']);
                }
                $info['answerNum'][$key] = 0;
                if ($isMandatory==true) {
                    $info['answerNum'][$key] = count($answerInfo);
                }else{
                    foreach ($answerInfo as $key2 => $value2) {
                        if (!$value2['content']) {
                            $info['answerNum'][$key] += 1; 
                        }
                    }
                }
                if (count($answerInfo)==0) {
                    $info = false;
                }else{
                    $queType = $value['type'];
                    $option = $value['options'];
                    $option = explode('---', $option);
                    $info['questions'][$key]['options'] = $option;
                    $sum = 0;
                    $ave = 0;
                    $num = 0;
                    if ($queType=='rate') {
                        for ($i=1; $i <= $option[0]; $i++) { 
                            $optionInfo[$i] = 0;
                        }
                    }else{
                        for ($i=0; $i < count($option); $i++) { 
                            $optionInfo[$i] = 0;
                        }
                    }
                    
                    if ($queType=='input') {
                        foreach ($answerInfo as $key1 => $value1) {
                            $optionInfo[$key1] = $value1['content'];
                        }
                    }
                    $info['answerInfo'][$key] = $optionInfo;
                }
                
            }
            return array("data"=>[$info,$userArr],"msg"=>"success");
        }
        
    }
}