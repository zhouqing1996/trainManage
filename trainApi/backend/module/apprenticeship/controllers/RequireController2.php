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

    //插入答卷数据，不存在相同数据则插入
    public function actionSubmit()
    {
        $request = \Yii::$app->request;
        $surveyId = $request->post('id');
        $answer = $request->post('answer');
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
                        'surveyId'=>$surveyId, 
                        'questionId'=>$questionId,
                        'content'=>$content,
                        'status'=>1,
                    )
                )->execute();
            }
        }
        return $result1;
    }

    public function actionGetanswer()
    {
        $request = \Yii::$app->request;
        $surveyId = $request->post('id');
        $answerInfo = SurveyAnswers::find()
            ->select('*')
            ->andWhere(['surveyId' => $surveyId])
            ->andWhere(['<>','status', 0])
            ->all();
        return array("data"=>$answerInfo,"msg"=>"success");
    }
}