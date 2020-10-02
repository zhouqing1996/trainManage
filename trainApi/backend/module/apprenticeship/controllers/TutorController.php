<?php
namespace backend\module\apprenticeship\controllers;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Request;
use yii\data\Pagination;
use yii\db\Query;
use common\models\SysPower;
use common\models\TutorRelease;
use common\models\TutorInfo;
use common\models\CompanyInfo;
use common\models\TutorTime;
use common\models\MajorInfo;
use common\models\ClassInfo;
use common\models\StudentInfo;
use common\models\TutorArrange;

class TutorController extends Controller
{
    public function actionGettutor(){
        $tutor = TutorInfo::find()
             ->andWhere(['status' => 1])
             ->orderBy(['companyAccount'=>SORT_ASC])
             ->all();
        $tutor2 = [];
        foreach ($tutor as $key => $value) {
            $arr = [];
            $arr['companyAccount'] = $value['companyAccount'];
            $arr['craft'] = $value['craft'];
            $arr['identity'] = $value['identity'];
            $arr['tutorName'] = $value['tutorName'];
            $arr['id'] = CompanyInfo::find()->where(['comAccount'=>$value['companyAccount']])->select('id')->one()['id'];
            array_push($tutor2,$arr);
        }
        $tutor3 = [];
        foreach ($tutor2 as $key => $value) {
            $tutor3[$value['id']][] = $value;
        }

        $nowtime = date("Y-m-d H:i:s");
        $year = explode("-", explode(" ", $nowtime)[0]) [0];
        $month = explode("-", explode(" ", $nowtime)[0]) [1];
        $grade = $month>6?[$year,$year-1,$year-2]:[$year-1,$year-2,$year-3];
        
        $data['tutor'] = $tutor2;
        $data['grade'] = $grade;
        return array("data"=> $data,"msg"=>"success");
    }

    public function actionAddtutor(){
        $major = Yii::$app->request->post("major");
        $company = Yii::$app->request->post("company");
        $tutor = Yii::$app->request->post("tutor");
        $edit = Yii::$app->request->post("edit");
        $rowId = Yii::$app->request->post("rowId");
        $time = date("Y-m-d H:i:s");
        if ($edit == 0) {
            return \Yii::$app->db->createCommand()->insert('tutor_release',
                array(  
                    'time'=>$time, 
                    'comId'=>$company,
                    'majorId'=>$major,
                    'tutor'=>count($tutor)==1?$tutor[0]:implode(",", (array)$tutor),
                ))->execute();
        } else {
            return \Yii::$app->db->createCommand()->update('tutor_release', 
                array(  
                    'time'=>$time, 
                    'comId'=>$company,
                    'majorId'=>$major,
                    'tutor'=>count($tutor)==1?$tutor[0]:implode(",", (array)$tutor),
                ), "id=:id",array(':id'=>$rowId))->execute();
        }
    }

    public function actionGetinfo(){
        $tutor =  TutorRelease::find()->where(['status' => 1])->orderBy(['time'=>SORT_DESC])->all();
        $time = TutorTime::find()->where(['status'=>1])->orderBy(['publishEndTme'=>SORT_DESC])->one();
        $publishEndTme = $time['publishEndTme'];
        $applyEndTme = $time['applyEndTme'];
        $year = explode("-", explode(" ", $publishEndTme)[0]) [0];
        $month = explode("-", explode(" ", $publishEndTme)[0]) [1];
        $nowtime = date("Y-m-d H:i:s");
        $tutor2 = [];
        foreach ($tutor as $key => $value) {
            $arr = [];
            $arr['id'] = $value['id'];
            $arr['time'] = $value['time'];
            $arr['comId'] = $value['comId'];
            $arr['grade'] = $value['grade'];
            $arr['comName'] = CompanyInfo::find()->where(['id'=>$value['comId']])->select('comName')->one()['comName'];
            $arr['majorId'] = $value['majorId'];
            $arr['major'] = MajorInfo::find()->where(['id'=>$value['majorId']])->select('major')->one()['major'];
            $year2 = explode("-", explode(" ", $value['time'])[0]) [0];
            $month2 = explode("-", explode(" ", $value['time'])[0]) [1];
            if ($year > $year2) {
                $arr['status'] = '历史记录';
            } else {
                if ($month > 6 && $month2<6) {
                    $arr['status'] = '历史记录';
                } else {
                    if(strtotime($nowtime) <= strtotime($publishEndTme)){
                        $arr['status'] = '发布中';
                    }else if(strtotime($publishEndTme) < strtotime($nowtime) && strtotime($nowtime) <= strtotime($applyEndTme)){
                        $arr['status'] = '学生申请中';
                    }else if(strtotime($applyEndTme) < strtotime($nowtime)){
                        $arrange = TutorArrange::find()->andWhere(['status' => 1])->andWhere(['releaseId' => $value['id']])->all();
                        if ($arrange) {
                            $arr['status'] = '已安排';
                        } else {
                            $arr['status'] = '未安排';
                        }
                    }
                }
                
            }
            $mytutor = [];
            if (strpos($value['tutor'],',')) {
                $mytutor = explode(',',$value['tutor']);
            } else {
                array_push($mytutor,$value['tutor']);
            }
            $arr2 = [];
            foreach ($mytutor as $key2 => $value2) {
                $arr3['tutor'] = TutorInfo::find()->where(['identity' => $value2])->one();
                $arr3['arranst'] = [];
                $arranst = TutorArrange::find()->andWhere(['status' => 1])->andWhere(['tutorIdentity' => $value2])->select('student')->one()['student'];
                if ($arranst) {
                    if (strpos($arranst,',')) {
                        $st = explode(',',$arranst);
                        foreach ($st as $key3 => $value3) {
                            array_push($arr3['arranst'],StudentInfo::find()->where(['identity' => $value3])->one());
                        }
                    } else {
                        array_push($arr3['arranst'],StudentInfo::find()->where(['identity' => $arranst])->one());
                    }
                }
                array_push($arr2,$arr3);
            }
            $arr['arrange'] = $arr2;
            array_push($tutor2,$arr);
        }
        $data['info'] = $tutor2;
        $data['time'] = $time;
        return array("data"=> $data,"msg"=>"success");
    }

    public function actionGetst(){
        $id = Yii::$app->request->post("id");
        $grade = Yii::$app->request->post("grade");
        $majorId = Yii::$app->request->post("majorId");
        $tutor = TutorArrange::find()->andWhere(['status' => 1])->andWhere(['releaseId' => $id])->all();
        $data = [];
        //该年级专业所有学生
        $class = ClassInfo::find()->andWhere(['grade' => $grade])->andWhere(['majorId' => $majorId])->select('id')->all();
        $allstu = [];
        if ($class) {
           foreach ($class as $key => $value) {
                $st = StudentInfo::find()->andWhere(['status' => 1])->andWhere(['classId' => $value['id']])->all();
                if ($st) {
                    foreach ($st as $key2 => $value2) {
                        $ar['value'] =  $value2['identity'];
                        $ar['label'] =  $value2['stuName'];
                        array_push($allstu,$ar);
                    }
                }
            }
        }

        foreach ($tutor as $key => $value) {
            $arr['tutor'] = TutorInfo::find()->where(['identity' => $value['tutorIdentity']])->one();
            $arr['arranst'] = [];
            $arr['allst'] = [];
            if ($value['student']) {
                if (strpos($value['student'],',')) {
                    $st = explode(',',$value['student']);
                    foreach ($st as $key2 => $value2) {
                        array_push($arr['arranst'],StudentInfo::find()->where(['identity' => $value2])->one());
                    }
                } else {
                    array_push($arr['arranst'],StudentInfo::find()->where(['identity' => $value])->one());
                }
            }
            if ($value['applyst']) {
                if (strpos($value['applyst'],',')) {
                    $st = explode(',',$value['applyst']);
                    foreach ($st as $key2 => $value2) {
                        $st2 = StudentInfo::find()->where(['identity' => $value2])->one();
                        $arr2['value'] =  $st2['identity'];
                        $arr2['label'] =  $st2['stuName'].'(申请)';
                        array_push($arr['allst'],$arr2);
                    }
                } else {
                    $st = StudentInfo::find()->where(['identity' => $value2])->one();
                    $arr2['value'] =  $st['identity'];
                    $arr2['label'] =  $st['stuName'].'(申请)';
                    array_push($arr['allst'],$arr2);
                }
            }
            foreach ($allstu as $key => $value) {
                if (!in_array($value, $arr['allst'])) {
                    array_push($arr['allst'],$value);
                }
            }
    
            array_push($data,$arr);
        }
        return array("data"=> $data,"msg"=>"success");
    }

    public function actionArrangest(){
        $st = Yii::$app->request->post("st");
        $tutor = Yii::$app->request->post("tutor");
        foreach ($tutor as $key => $value) {
            if (count($st[$key]) != 0) {
                \Yii::$app->db->createCommand()->update('tutor_arrange', 
                    array(  
                        'student'=>count($st[$key])==1?$st[$key][0]:implode(",", (array)$st[$key])
                    ), "tutorIdentity=:tutorIdentity",array(':tutorIdentity'=>$value))->execute();
            }
        }
        return array("data"=> '',"msg"=>"success");
    }
}