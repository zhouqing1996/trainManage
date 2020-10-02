<?php
namespace backend\module\requirement\controllers;

use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\SurveyInfo;
use common\models\SurveyQuestions;

class TemplateController extends Controller
{
    public function actionIndex()
    {
        return "问卷模板首页";
    }

    public function actionGettemplate()
    {
        $request = \Yii::$app->request;
        $query = (new Query())
            ->select('*')
            ->from('survey_info')
            ->andWhere(['status' => 1])
            ->andWhere(['or','type = 1','type = 2','type = 3','type = 4','type = 5'])
            // ->andWhere(['type' => [1,2,3,4,5,6])
            // ->andWhere(['<>','type', 0])
            // ->andWhere(['<>','type', 7])
            // ->andWhere(['<>','type', 10])
            // ->andWhere(['<>','type', 21])
            ->orderBy('id DESC')
            ->all();

        $query1 = (new Query())
            ->select('*')
            ->from('survey_info')
            ->andWhere(['status' => 1])
            ->andWhere(['or','type = 1','type = 2','type = 3','type = 4','type = 5'])
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

    public function actionGettemplate2()
    {
        $request = \Yii::$app->request;
        $query = (new Query())
            ->select('*')
            ->from('survey_info')
            ->andWhere(['type' => 21])
            ->andWhere(['status' => 1])
            ->all();

        $query1 = (new Query())
            ->select('*')
            ->from('survey_info')
            ->andWhere(['type' => 21])
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

    // public function actionTemplateshow()
    // {   
    //     $request = \Yii::$app->request;
    //     $type = $request->post('type');
    //     $page = $request->post('page');

    //     if ($type==0) {
    //         $query = (new Query())
    //         ->select('*')
    //         ->from('survey_info')
    //         ->andWhere(['status' => 1])
    //         ->andWhere(['<>','type', 0])
    //         ->all();

    //         $query1 = (new Query())
    //             ->select('*')
    //             ->from('survey_info')
    //             ->andWhere(['status' => 1])
    //             ->andWhere(['<>','type', 0]);
    //     }else{
    //         $query = (new Query())
    //         ->select('*')
    //         ->from('survey_info')
    //         ->andWhere(['status' => 1])
    //         ->andWhere(['type' => $type])
    //         ->all();

    //         $query1 = (new Query())
    //             ->select('*')
    //             ->from('survey_info')
    //             ->andWhere(['status' => 1])
    //             ->andWhere(['type' => $type]);
    //     }

    //     $countQuery = clone $query1;
    //     $totalCount = $countQuery->count();
    //     $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>'8']);
    //     $models = $query1->offset($pages->offset)
    //         ->limit($pages->limit)
    //         ->all();   
    //     $pageNum = ceil($totalCount/8);
    //     return array("data"=>[$query,$pageNum],"msg"=>"success");
    // }

    public function actionInserttem()
    {
        $request = \Yii::$app->request;
        $username = $request->post('username');
        $queData = $request->post('quData');
        $title = $queData['title'];
        $temType = $queData['type'];
        $questions = $queData['questions'];
        $endTime = $queData['time'];
        $addTime = date('Y-m-d h:i:s', time());
        $result1 = \Yii::$app->db->createCommand()->insert('survey_info',
                        array(
                            'title'=>$title, 
                            'addTime'=>$addTime,
                            'endTime'=>$endTime,
                            'type'=>$temType,
                            'username'=>$username,
                        )
                    )->execute();
            if ($result1==1) {
                $data = SurveyInfo::find()->andWhere(['title'=>$title])
                        ->andWhere(['status'=>1])
                        ->andWhere(['<>','type', 0])
                        ->one();
                $id = $data['id'];

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
                                'isMandatory'=>$isMandatory,
                                'options'=>$options,
                                'status'=>1
                                )
                        )->execute();
                }
                if ($result2==1) {
                    return $result2;
                }else{
                    return false;
                }
            }
    }

    public function actionInserttem2()
    {
        $request = \Yii::$app->request;
        $username = $request->post('username');
        $queData = $request->post('quData');
        $title = $queData['title'];
        $temType = $queData['type'];
        $questions = $queData['questions'];
        $endTime = $queData['time'];
        $addTime = date('Y-m-d h:i:s', time());
        $result1 = \Yii::$app->db->createCommand()->insert('survey_info',
                        array(
                            'title'=>$title, 
                            'addTime'=>$addTime,
                            'endTime'=>$endTime,
                            'type'=>$temType,
                            'username'=>$username,
                        )
                    )->execute();
            if ($result1==1) {
                $data = SurveyInfo::find()->andWhere(['title'=>$title])
                        ->andWhere(['status'=>1])
                        ->andWhere(['<>','type', 0])
                        ->one();
                $id = $data['id'];

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
                                'isMandatory'=>$isMandatory,
                                'options'=>$options,
                                'status'=>1
                                )
                        )->execute();
                }
                if ($result2==1) {
                    return $result2;
                }else{
                    return false;
                }
            }
    }

    //删除数据，根据专业Id来删除数据
    // public function actionDeletesurvey()
    // {
    //     $request = \Yii::$app->request;
    //     $surveyId = $request->post('id');
    //     $result = \Yii::$app->db->createCommand()
    //               ->update('survey_info',['status'=>0],'id=:surveyId',
    //                       [':surveyId' => $surveyId])->execute();
        
    //     if ($result==1) {
    //         $result1 = \Yii::$app->db->createCommand()
    //           ->update('survey_questions',['status'=>0],
    //                    'surveyId=:surveyId',[':surveyId' => $surveyId])
    //           ->execute();
            
    //         $result2 = \Yii::$app->db->createCommand()
    //             ->update('survey_answers',['status'=>0],
    //                     'surveyId=:surveyId',[':surveyId' => $surveyId])
    //             ->execute();
    //         return $result2;
    //         // return array("data"=>$result2,"msg"=>"success");
    //     }        
    // }

    //获取编辑数据
    // public function actionGeteditsurvey()
    // {
    //     $request = \Yii::$app->request;
    //     $id = $request->post('id');
    //     $surveyInfo = SurveyInfo::find()->where(['id' => $id])->one();
    //     $questions = SurveyQuestions::find()->andWhere(['surveyId' => $id])->andWhere(['status' => 1])->all();
    //     $queData=[];
    //     $queData['title']=$surveyInfo['title'];
    //     $queData['endTime']=$surveyInfo['endTime'];
    //     if ($questions==null) {
    //         return "failure";
    //     } else {
    //         foreach ($questions as $key => $value) {
    //             $options=explode('---',$value['options']);
    //             $questions[$key]['options']=$options;
    //         }
    //         $queData['questions']=$questions;
    //     }
    //     return array("data"=>$queData,"msg"=>"success");
    // }

    //编辑模板信息
    // public function actionEdittemplate()
    // {
    //     $request = \Yii::$app->request;
    //     $queData = $request->post('quData');
    //     $surveyId = $queData['id'];
    //     $title = $queData['title'];
    //     $templateType = $queData['type'];
    //     $questions = $queData['questions'];
    //     $endTime = $queData['time'];
    //     $result = \Yii::$app->db->createCommand()->update('survey_info',
    //         ['title'=>$title,
    //          'type'=>$templateType,
    //          'endTime'=>$endTime,
    //          'status'=>1],'id=:surveyId',[':surveyId' => $surveyId])->execute();

    //     $result1 = \Yii::$app->db->createCommand()
    //               ->update('survey_questions',['status'=>0],'surveyId=:surveyId',
    //                       [':surveyId' => $surveyId])->execute();
    //     foreach ($questions as $key => $value) {
    //         $topic = $value['topic'];
    //         $type = $value['type'];
    //         $optionArr=[];
    //         $options='';
    //         if ($type=='radio'||$type=='checkbox') {
    //             $optionArr = $value['options'];
    //             $options=implode('---',$optionArr);
    //         }elseif ($type=='textarea') {
    //             $options = '';
    //         }
    //         $result2 = \Yii::$app->db->createCommand()
    //             ->insert('survey_questions',
    //                 array(
    //                     'surveyId'=>$surveyId, 
    //                     'topic'=>$topic, 
    //                     'type'=>$type, 
    //                     'options'=>$options,
    //                     'status'=>1
    //                     )
    //             )->execute();
    //     }
    //     if ($result2==1) {
    //         return $result2;
    //     }else{
    //         return false;
    //     }
    // }



     //编辑模板类别信息
    // public function actionEditclass()
    // {
    //     $request = \Yii::$app->request;
    //     $queData = $request->post('quData');
    //     $surveyId = $queData['id'];
    //     $type = $queData['type'];
    //     $result = \Yii::$app->db->createCommand()->update('survey_info',
    //         ['type'=>$type],'id=:surveyId',[':surveyId' => $surveyId])->execute();

    //     return $result;
    // }
}