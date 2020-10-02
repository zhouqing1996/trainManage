<?php
namespace backend\module\requirement\controllers;

use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\SurveyInfo;
use common\models\SurveyQuestions;
use common\models\AnswerSource;

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
            ->andWhere(['type' => 0])
            ->andWhere(['username' => $username])
            ->orderBy('addTime desc')
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
            ->andWhere(['type' => 0])
            ->andWhere(['username' => $username])
            ->orderBy('addTime desc');

        $countQuery = clone $query1;
        $totalCount = $countQuery->count();
        $pages = new Pagination(['totalCount' => $countQuery->count(),'pageSize'=>'8']);
        $models = $query1->offset($pages->offset)
            ->limit($pages->limit)
            ->all();   
        $pageNum = ceil($totalCount/8);
        return array("data"=>[$query,$ansNum,$pageNum],"msg"=>"success");
    }

    public function actionTemplateshow()
    {   
        $request = \Yii::$app->request;
        $type = $request->post('type');
        // $page = $request->post('page');

        if ($type==0) {
            $query = (new Query())
            ->select('*')
            ->from('survey_info')
            ->andWhere(['status' => 1])
            ->andWhere(['or','type = 1','type = 2','type = 3','type = 4','type = 5'])
            ->all();

            $query1 = (new Query())
                ->select('*')
                ->from('survey_info')
                ->andWhere(['status' => 1])
                ->andWhere(['or','type = 1','type = 2','type = 3','type = 4','type = 5']);
        }else{
            $query = (new Query())
            ->select('*')
            ->from('survey_info')
            ->andWhere(['status' => 1])
            ->andWhere(['type' => $type])
            ->all();

            $query1 = (new Query())
                ->select('*')
                ->from('survey_info')
                ->andWhere(['status' => 1])
                ->andWhere(['type' => $type]);
        }

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
                    return $result2;
                }else{
                    return false;
                }
            }
    }

    public function actionInsertsurvey2()
    {
        $request = \Yii::$app->request;
        $username = $request->post('username');
        $queData = $request->post('quData');
        $recruitId = $request->post('recruitId');
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
                            'pushId'=>$recruitId,
                        )
                    )->execute();
            $id=\Yii::$app->db->getLastInsertID();
            if ($result1==1) {
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
                    return $result2;
                }else{
                    return false;
                }
            }
    }

    //删除数据，根据专业Id来删除数据
    public function actionDeletesurvey()
    {
        $request = \Yii::$app->request;
        $surveyId = $request->post('id');
        $result = \Yii::$app->db->createCommand()
                  ->update('survey_info',['status'=>0],'id=:surveyId',
                          [':surveyId' => $surveyId])->execute();
        
        if ($result==1) {
            $result1 = \Yii::$app->db->createCommand()
              ->update('survey_questions',['status'=>0],
                       'surveyId=:surveyId',[':surveyId' => $surveyId])
              ->execute();
            
            $result2 = \Yii::$app->db->createCommand()
                ->update('survey_answers',['status'=>0],
                        'surveyId=:surveyId',[':surveyId' => $surveyId])
                ->execute();

            $result3 = \Yii::$app->db->createCommand()
                ->update('answer_source',['status'=>0],
                        'surveyId=:surveyId',[':surveyId' => $surveyId])
                ->execute();
            return $result3;
            // return array("data"=>$result2,"msg"=>"success");
        }        
    }

    //获取编辑数据
    public function actionGeteditsurvey()
    {
        $request = \Yii::$app->request;
        $id = $request->post('id');
        $surveyInfo = SurveyInfo::find()
             ->andWhere(['id' => $id])
             ->andWhere(['<>','status', 0])
             ->one();
        
        $questions = SurveyQuestions::find()
             ->andWhere(['surveyId' => $id])
             ->andWhere(['status' => 1])
             ->all();
        $queData=[];
        $queData['title']=$surveyInfo['title'];
        $queData['endTime']=$surveyInfo['endTime'];
        // return array("data"=>$questions,"msg"=>"success");

        if ($questions==null) {
            return "failure";
        } else {
            foreach ($questions as $key => $value) {
                $options=explode('---',$value['options']);
                $questions[$key]['options']=$options;
            }
            $queData['questions']=$questions;
        }
        return array("data"=>$queData,"msg"=>"success");
    }

    //编辑问卷信息
    public function actionEditsurvey()
    {
        $request = \Yii::$app->request;
        $username = $request->post('username');
        $queData = $request->post('quData');
        $surveyId = $queData['id'];
        $title = $queData['title'];
        $questions = $queData['questions'];
        $endTime = $queData['time'];
        $type = $queData['type'];
        $result = \Yii::$app->db->createCommand()->update('survey_info',
            ['title'=>$title,
             'endTime'=>$endTime,
             'type'=>$type,
             'status'=>1],'id=:surveyId',[':surveyId' => $surveyId])->execute();

        $result1 = \Yii::$app->db->createCommand()
                  ->update('survey_questions',['status'=>0],'surveyId=:surveyId',
                          [':surveyId' => $surveyId])->execute();
        foreach ( $questions as $key => $value) {
            $topic = $value['topic'];
            $type = $value['type'];
            $isMandatory = $value['isMandatory'];
            $optionArr=[];
            $options='';
            if ($type=='radio'||$type=='checkbox'||$type=='rate') {
                $optionArr = $value['options'];
                $options=implode('---',$optionArr);
            }else if ($type=='textarea') {
                $options = '';
            }
            $result2 = \Yii::$app->db->createCommand()
                ->insert('survey_questions',
                    array(
                        'surveyId'=>$surveyId, 
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
            // return array("data"=>[$key,$value],"msg"=>"success");
        }else{
            return false;
        }
    }

    public function actionEditsurvey2()
    {
        $request = \Yii::$app->request;
        $username = $request->post('username');
        $queData = $request->post('quData');
        $surveyId = $queData['id'];
        $title = $queData['title'];
        $questions = $queData['questions'];
        $endTime = $queData['time'];
        $type = $queData['type'];
        $result = \Yii::$app->db->createCommand()->update('survey_info',
            ['title'=>$title,
             'endTime'=>$endTime,
             'type'=>$type,
             'status'=>1],'id=:surveyId',[':surveyId' => $surveyId])->execute();

        $result1 = \Yii::$app->db->createCommand()
                  ->update('survey_questions',['status'=>0],'surveyId=:surveyId',
                          [':surveyId' => $surveyId])->execute();
        foreach ( $questions as $key => $value) {
            $topic = $value['topic'];
            $type = $value['type'];
            $isMandatory = $value['isMandatory'];
            $optionArr=[];
            $options='';
            if ($type=='radio'||$type=='checkbox'||$type=='rate') {
                $optionArr = $value['options'];
                $options=implode('---',$optionArr);
            }else if ($type=='textarea') {
                $options = '';
            }
            $result2 = \Yii::$app->db->createCommand()
                ->insert('survey_questions',
                    array(
                        'surveyId'=>$surveyId, 
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

    public function actionEdittemp()
    {
        $request = \Yii::$app->request;
        $username = $request->post('username');
        $queData = $request->post('quData');
        $surveyId = $queData['id'];
        $title = $queData['title'];
        $questions = $queData['questions'];
        $endTime = $queData['time'];
        $result = \Yii::$app->db->createCommand()->update('survey_info',
            ['title'=>$title,
             'endTime'=>$endTime,
             'status'=>1],'id=:surveyId',[':surveyId' => $surveyId])->execute();

        $result1 = \Yii::$app->db->createCommand()
                  ->update('survey_questions',['status'=>0],'surveyId=:surveyId',
                          [':surveyId' => $surveyId])->execute();
        foreach ( $questions as $key => $value) {
            $topic = $value['topic'];
            $type = $value['type'];
            $isMandatory = $value['isMandatory'];
            $optionArr=[];
            $options='';
            if ($type=='radio'||$type=='checkbox'||$type=='rate') {
                $optionArr = $value['options'];
                $options=implode('---',$optionArr);
            }else if ($type=='textarea') {
                $options = '';
            }
            $result2 = \Yii::$app->db->createCommand()
                ->insert('survey_questions',
                    array(
                        'surveyId'=>$surveyId, 
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

    //发布问卷
    public function actionPublishsurvey()
    {
        $request = \Yii::$app->request;
        $id = $request->post('id');
        $status = $request->post('status');
        
        $result = \Yii::$app->db->createCommand()->update('survey_info',
            ['status'=>$status],'id=:id',[':id' => $id])->execute();
        return $result;
    }

    //编辑模板信息
    public function actionEdittemplate()
    {
        $request = \Yii::$app->request;
        $queData = $request->post('quData');
        $surveyId = $queData['id'];
        $title = $queData['title'];
        $templateType = $queData['type'];
        $questions = $queData['questions'];
        $endTime = $queData['time'];
        $result = \Yii::$app->db->createCommand()->update('survey_info',
            ['title'=>$title,
             'type'=>$templateType,
             'endTime'=>$endTime,
             'status'=>1],'id=:surveyId',[':surveyId' => $surveyId])->execute();

        $result1 = \Yii::$app->db->createCommand()
                  ->update('survey_questions',['status'=>0],'surveyId=:surveyId',
                          [':surveyId' => $surveyId])->execute();
        foreach ($questions as $key => $value) {
            $topic = $value['topic'];
            $type = $value['type'];
            $optionArr=[];
            $options='';
            if ($type=='radio'||$type=='checkbox') {
                $optionArr = $value['options'];
                $options=implode('---',$optionArr);
            }elseif ($type=='textarea') {
                $options = '';
            }
            $result2 = \Yii::$app->db->createCommand()
                ->insert('survey_questions',
                    array(
                        'surveyId'=>$surveyId, 
                        'topic'=>$topic, 
                        'type'=>$type, 
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

    //插入答卷数据，不存在相同数据则插入
    public function actionSubmit()
    {
        $request = \Yii::$app->request;
        $surveyId = $request->post('id');
        $userName = $request->post('username');
        $answer = $request->post('answer');
        $ip = $request->post('ip');
        $cityname = $request->post('cityname');
        $subTime = date('Y-m-d h:i:s', time());
        $stuId = $request->post('stuId');
        if (!$stuId) {
            // $data = AnswerSource::find()
            //     ->andWhere(['surveyId'=>$surveyId])
            //     ->andWhere(['ip'=>$ip])
            //     ->one();
            $data = null;
            if ($data == null) {
                $result = \Yii::$app->db->createCommand()->insert('answer_source',
                        array(
                            // 'userName'=>$userName, 
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
                            )
                        )->execute();
                    }
                }
                return $result1;
            }else{
                return false;
            }
        }else{
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
    


        // $result = SurveyInfo::find()->andWhere(['title'=>$title])->andWhere(['status'=>1])->one();
        // if ( $result == null) {
        //     $result1 = \Yii::$app->db->createCommand()->insert('survey_info',
        //                 array(
        //                     'title'=>$title, 
        //                     'endTime'=>$endTime,
        //                 )
        //             )->execute();
        //     if ($result1==1) {
        //         $data = SurveyInfo::find()->andWhere(['title'=>$title])->andWhere(['status'=>1])->one();
        //         $id = $data['id'];
        //         foreach ($questions as $key => $value) {
        //             $topic = $value['topic'];
        //             $type = $value['type'];
        //             $optionArr=[];
        //             $options='';
        //             if ($type=='radio'||$type=='checkbox') {
        //                 $optionArr = $value['options'];
        //                 $options=implode('---',$optionArr);
        //             }elseif ($type=='textarea') {
        //                 $options = '';
        //             }
        //             $result2 = \Yii::$app->db->createCommand()
        //                 ->insert('survey_questions',
        //                     array(
        //                         'surveyId'=>$id, 
        //                         'topic'=>$topic, 
        //                         'type'=>$type, 
        //                         'options'=>$options,
        //                         'status'=>1
        //                         )
        //                 )->execute();
        //         }
        //         if ($result2==1) {
        //             return $result2;
        //         }else{
        //             return false;
        //         }
        //     }
        // } else {
        //     return false;
        // }
    }

     //编辑模板类别信息
    public function actionEditclass()
    {
        $request = \Yii::$app->request;
        $queData = $request->post('quData');
        $surveyId = $queData['id'];
        $type = $queData['type'];
        $result = \Yii::$app->db->createCommand()->update('survey_info',
            ['type'=>$type],'id=:surveyId',[':surveyId' => $surveyId])->execute();

        return $result;
    }
}