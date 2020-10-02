<?php
namespace backend\models\probation;

use Yii;
use yii\base\Model;
use common\models\RecruitmentInfo;
use common\models\CompanyInfo;
use common\models\MajorInfo;
use common\models\TeacherInfo;
use common\models\StudentInfo;
use common\models\ClassInfo;
use common\models\Clock;
use common\models\VwStudentInfo;
use common\models\Remind;
date_default_timezone_set('PRC');

class RemindModel extends Model{

    public function getremind()
    {
        $list = Remind::find()->where(['status'=>1])->all();
        $newlist = [];
        foreach ($list as $key => $value) {
            $a = [];
            $a['id'] = $value['id'];
            $a['repeat'] = strpos($value['repeat'],',')?explode(',',$value['repeat']):[$value['repeat']];
            $a['content'] = $value['content'];
            $r = strpos($value['pushId'],',')?explode(',',$value['pushId']):[$value['pushId']];
            foreach ($r as $key2 => $value2) {
                $comId = RecruitmentInfo::find()->where(['id'=>$value2])->select('comId')->one()['comId'];
                $recruit[$key2] = CompanyInfo::find()->where(['id'=>$comId])->select('comName')->one()['comName'];
            }
            $a['recruit'] = $recruit;
            array_push($newlist,$a);
        }
        return $newlist;
    }

    public function getremind2($recruitId)
    {
        $remind = Remind::find()->andWhere(['status'=>1])->all();
        $list = [];
        foreach ($remind as $key => $value) {
            $pushId = strpos($value['pushId'],',')?explode(',',$value['pushId']):[$value['pushId']];
            foreach ($pushId as $key2 => $value2) {
                if ($value2 == $recruitId) {
                    array_push($list,$value);
                }
            } 
        }
        $newlist = [];
        foreach ($list as $key => $value) {
            $a = [];
            $a['id'] = $value['id'];
            $a['repeat'] = strpos($value['repeat'],',')?explode(',',$value['repeat']):[$value['repeat']];
            $a['content'] = $value['content'];
            $r = strpos($value['pushId'],',')?explode(',',$value['pushId']):[$value['pushId']];
            foreach ($r as $key2 => $value2) {
                $comId = RecruitmentInfo::find()->where(['id'=>$value2])->select('comId')->one()['comId'];
                $recruit[$key2] = CompanyInfo::find()->where(['id'=>$comId])->select('comName')->one()['comName'];
            }
            $a['recruit'] = $recruit;
            array_push($newlist,$a);
        }
        return $newlist;
    }

    public function getpush()
    {
        $com = RecruitmentInfo::find()->where(['status'=>3])->select('id,comId')->all();
        $push = [];
        foreach ($com as $key => $value) {
            $p = [];
            $p['label'] = $value['id'];
            $p['value'] = CompanyInfo::find()->where(['id'=>$value['comId']])->select('comName')->one()['comName'];
            array_push($push,$p);
        }
        return $push;
    }

    public function addremind($form)
    {
        $day = [];
        foreach ($form['checkedDays'] as $key => $value) {
            if ($value == '周一') {
                array_push($day,1);
            } else if ($value == '周二') {
                array_push($day,2);
            } else if ($value == '周三') {
                array_push($day,3);
            } else if ($value == '周四') {
                array_push($day,4);
            } else if ($value == '周五') {
                array_push($day,5);
            } else if ($value == '周六') {
                array_push($day,6);
            } else if ($value == '周日') {
                array_push($day,7);
            } 
        }
        \Yii::$app->db->createCommand()->insert('remind',
                array(  
                    'content'=>$form['content'], 
                    'repeat'=>implode(",", $day),
                    'pushId'=>implode(",", $form['push']),
                    'date'=>date("Y-m-d"),
                    'status'=>1
                ))->execute();
        return $this->getremind();
    }

    public function addremind2($form,$recruitId)
    {
        $day = [];
        foreach ($form['checkedDays'] as $key => $value) {
            if ($value == '周一') {
                array_push($day,1);
            } else if ($value == '周二') {
                array_push($day,2);
            } else if ($value == '周三') {
                array_push($day,3);
            } else if ($value == '周四') {
                array_push($day,4);
            } else if ($value == '周五') {
                array_push($day,5);
            } else if ($value == '周六') {
                array_push($day,6);
            } else if ($value == '周日') {
                array_push($day,7);
            } 
        }
        \Yii::$app->db->createCommand()->insert('remind',
                array(  
                    'content'=>$form['content'], 
                    'repeat'=>implode(",", $day),
                    'pushId'=>$recruitId,
                    'date'=>date("Y-m-d"),
                    'status'=>1
                ))->execute();
        return $this->getremind2($recruitId);
    }

    public function editremind($form)
    {
        $day = [];
        foreach ($form['checkedDays'] as $key => $value) {
            if ($value == '周一') {
                array_push($day,1);
            } else if ($value == '周二') {
                array_push($day,2);
            } else if ($value == '周三') {
                array_push($day,3);
            } else if ($value == '周四') {
                array_push($day,4);
            } else if ($value == '周五') {
                array_push($day,5);
            } else if ($value == '周六') {
                array_push($day,6);
            } else if ($value == '周日') {
                array_push($day,7);
            } 
        }
        \Yii::$app->db->createCommand()->update('remind', 
                array(  
                    'content'=>$form['content'], 
                    'repeat'=>implode(",", $day),
                    'pushId'=>implode(",", $form['push']),
                    'status'=>1
                ), "id=:id",array(':id'=>$form['id']))->execute();
        return $this->getremind();
    }

    public function editremind2($form,$recruitId)
    {
        $day = [];
        foreach ($form['checkedDays'] as $key => $value) {
            if ($value == '周一') {
                array_push($day,1);
            } else if ($value == '周二') {
                array_push($day,2);
            } else if ($value == '周三') {
                array_push($day,3);
            } else if ($value == '周四') {
                array_push($day,4);
            } else if ($value == '周五') {
                array_push($day,5);
            } else if ($value == '周六') {
                array_push($day,6);
            } else if ($value == '周日') {
                array_push($day,7);
            } 
        }
        \Yii::$app->db->createCommand()->update('remind', 
                array(  
                    'content'=>$form['content'], 
                    'repeat'=>implode(",", $day),
                    'status'=>1
                ), "id=:id",array(':id'=>$form['id']))->execute();
        return $this->getremind2($recruitId);
    }

    public function deletremind($id)
    {
        \Yii::$app->db->createCommand()->update('remind', 
                array(  
                    'status'=>0
                ), "id=:id",array(':id'=>$id))->execute();
        return $this->getremind();
    }

}

?>