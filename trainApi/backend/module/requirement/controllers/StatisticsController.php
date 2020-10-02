<?php
namespace backend\module\requirement\controllers;

use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\SurveyInfo;
use common\models\SurveyQuestions;
use common\models\SurveyAnswers;
use common\models\AnswerSource;

class StatisticsController extends Controller
{
    public function actionIndex()
    {
        return "数据统计首页";
    }

    public function actionGetdata()
    {
        $request = \Yii::$app->request;
        $surveyId = $request->post('surveyId');
        $stuId = $request->post('stuId');
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
                $optionInfo = [];
                $questionId = $value['id'];
                $isMandatory = $value['isMandatory'];
                $answerInfo = SurveyAnswers::find()
                    ->select('content')
                    ->andWhere(['questionId' => $questionId])
                    ->andWhere(['status' => 1])
                    ->all();

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
                    
                    if ($queType=='radio') {
                        foreach ($answerInfo as $key1 => $value1) {
                            $optionInfo[$value1['content']] += 1;
                        }
                    }elseif ($queType=='checkbox') {
                        foreach ($answerInfo as $key1 => $value1) {
                            $ans = explode('---', $value1['content']);
                            foreach ($ans as $key2 => $value2) {
                                $optionInfo[$value2] += 1;
                            }
                        }
                    }elseif ($queType=='input'||$queType=='textarea') {
                        foreach ($answerInfo as $key1 => $value1) {
                            $optionInfo[$key1] = $value1['content'];
                        }
                    }elseif ($queType=='rate') {
                        foreach ($answerInfo as $key1 => $value1) {
                            $optionInfo[$value1['content']] += 1;
                            $sum += $value1['content'];
                            $num +=1;
                        }
                    }
                    if ($queType=='rate') {
                        $ave =$sum/$num;
                        $info['answerInfo'][$key] = [];
                        $info['answerInfo'][$key]['opt'] = $optionInfo;
                        $info['answerInfo'][$key]['ave'] = $ave;
                    }else{
                        $info['answerInfo'][$key] = $optionInfo;
                    }
                }
                
            }
            return array("data"=>$info,"msg"=>"success");
        }else{
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
                $optionInfo = [];
                $questionId = $value['id'];
                $isMandatory = $value['isMandatory'];
                $answerInfo = SurveyAnswers::find()
                    ->select('content')
                    ->andWhere(['questionId' => $questionId])
                    ->andWhere(['status' => 1])
                    ->andWhere(['stuId' => $stuId])
                    ->all();

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
                    
                    if ($queType=='radio') {
                        foreach ($answerInfo as $key1 => $value1) {
                            $optionInfo[$value1['content']] += 1;
                        }
                    }elseif ($queType=='checkbox') {
                        foreach ($answerInfo as $key1 => $value1) {
                            $ans = explode('---', $value1['content']);
                            foreach ($ans as $key2 => $value2) {
                                $optionInfo[$value2] += 1;
                            }
                        }
                    }elseif ($queType=='input'||$queType=='textarea') {
                        foreach ($answerInfo as $key1 => $value1) {
                            $optionInfo[$key1] = $value1['content'];
                        }
                    }elseif ($queType=='rate') {
                        foreach ($answerInfo as $key1 => $value1) {
                            $optionInfo[$value1['content']] += 1;
                            $sum += $value1['content'];
                            $num +=1;
                        }
                    }
                    if ($queType=='rate') {
                        $ave =$sum/$num;
                        $info['answerInfo'][$key] = [];
                        $info['answerInfo'][$key]['opt'] = $optionInfo;
                        $info['answerInfo'][$key]['ave'] = $ave;
                    }else{
                        $info['answerInfo'][$key] = $optionInfo;
                    }
                }
                
            }
            return array("data"=>$info,"msg"=>"success");
        }
        
    }

    public function actionGetpaper()
    {
        $request = \Yii::$app->request;
        $surveyId = $request->post('surveyId');
        $stuId = $request->post('stuId');
        $paperInfo = [];
        $paperContent = [];
        if (!$stuId) {
            $paperSource = AnswerSource::find()
            ->andWhere(['surveyId' => $surveyId])
            ->andWhere(['status' => 1])
            // ->orderBy('subtime')
            ->all();
        
            foreach ($paperSource as $key => $value) {
                $paperContent[$key] = [];
                $ip = $value['ip'];
                $content = SurveyAnswers::find()
                    ->andWhere(['surveyId' => $surveyId])
                    ->andWhere(['ip' => $ip])
                    ->andWhere(['status' => 1])
                    ->all();
                $paperContent[$key] = $content;
            }
            $paperInfo['source'] = $paperSource;
            $paperInfo['content'] = $paperContent;
            return array("data"=>$paperInfo,"msg"=>"success");
        }else{
            $paperSource = AnswerSource::find()
                ->andWhere(['surveyId' => $surveyId])
                ->andWhere(['status' => 1])
                ->andWhere(['stuId' => $stuId])
                ->all();
        
            foreach ($paperSource as $key => $value) {
                $paperContent[$key] = [];
                $ip = $value['ip'];
                $content = SurveyAnswers::find()
                    ->andWhere(['surveyId' => $surveyId])
                    ->andWhere(['ip' => $ip])
                    ->andWhere(['status' => 1])
                    ->andWhere(['stuId' => $stuId])
                    ->all();
                $paperContent[$key] = $content;
            }
            $paperInfo['source'] = $paperSource;
            $paperInfo['content'] = $paperContent;
            return array("data"=>$paperInfo,"msg"=>"success");
        }
        
    }
}