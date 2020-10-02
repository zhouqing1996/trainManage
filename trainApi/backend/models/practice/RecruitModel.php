<?php
namespace backend\models\practice;

use Yii;
use yii\base\Model;
use common\models\RecruitmentInfo;
use common\models\CompanyInfo;
use common\models\MajorInfo;
use common\models\TeacherInfo;
use common\models\StudentInfo;
use common\models\ClassInfo;
date_default_timezone_set('PRC');
/**
 * Login form
 */
class RecruitModel extends Model{
	/**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function dataHandle($list)
    {
        $list2 = [];
        $aaa = [];
        foreach ($list as $key => $value) {
            $a = [];
            $a['id'] = $value['id'];
            //用作不是超管的企业进来只能看到相应信息 v-if='props.row.comId == $$store.getters.comId'
            $a['comId'] = $value['comId'];
            $a['subject'] = $value['subject'];
            $a['content'] = $value['content'];
            $a['time'] = $value['time'];
            // $a['majorId'] = $value['majorId'];
            $a['num'] = $value['num'];
            $a['startDate'] = $value['startDate'];
            $a['endDate'] = $value['endDate'];          
            $date = [];
            array_push($date, $value['startDate']);
            array_push($date, $value['endDate']);
            $a['date'] = $date;
            $a['studentEnddate'] = $value['studentEnddate'];
            $a['status'] = $value['status'];
            $a['recruitStart'] = $value['recruitStart'];
            $a['recruitEnd'] = $value['recruitEnd'];
            $a['clockMor'] = $value['clockMor'];
            $a['clockEve'] = $value['clockEve'];
            $a['lng'] = $value['lng'];
            $a['lat'] = $value['lat'];
            $a['address'] = $value['address'];
            $a['scope'] = $value['scope'];
            $a['arrangeStuId'] = $value['arrangeStuId'];
            $a['comName'] = CompanyInfo::find()->where(['id'=>$value['comId']])->select('comName')->one()['comName'];
            $a['major'] = MajorInfo::find()->where(['id'=>$value['majorId']])->select('major')->one()['major'];
            ///
            //$a['grade'] = $value['grade'];

            $a['teacher'] = TeacherInfo::find()->where(['id'=>$value['teacherId']])->one();
            $student = explode(",", $value['arrangeStuId']);
            $b = [];
            $starr = StudentInfo::find()->where(['in','id',$student])->all();
            foreach ($starr as $key2 => $st) {
                if (array_key_exists($st['id'], $aaa)) {
                     array_push($b,$aaa[$st['id']]);
                     continue;
                }              
                $class = [];
                $class['id'] = $st['id'];
                $class['stuName'] = $st['stuName'];
                $class['sno'] = $st['sno'];
                $class['identity'] = $st['identity'];
                $class['sex'] = $st['sex'];
                $class['bornDate'] = $st['bornDate'];
                $class['contactPhone'] = $st['contactPhone'];
                $class['email'] = $st['email'];
                $class['major'] = MajorInfo::find()->where(['id'=>(ClassInfo::find()->where(['id'=>$st['classId']])->select('majorId')->one()['majorId'])])->select('major')->one()['major'];
                $class['className'] = ClassInfo::find()->where(['id'=>$st['classId']])->select('className')->one()['className'];
                $class['grade'] = ClassInfo::find()->where(['id'=>$st['classId']])->select('grade')->one()['grade'];
                $aaa[$st['id']] = $class;           
                array_push($b,$class);
            }
            $a['student'] = $b;
            $applyst = explode(",", $value['studentId']);
            $aparr = StudentInfo::find()->where(['in','id',$applyst])->all();
            $c = [];
            foreach ($aparr as $key3 => $st) {
                if (array_key_exists($st['id'], $aaa)) {
                     array_push($c,$aaa[$st['id']]);
                     continue;
                }
                $class = [];
                $class['id'] = $st['id'];
                $class['stuName'] = $st['stuName'];
                $class['sno'] = $st['sno'];
                $class['identity'] = $st['identity'];
                $class['sex'] = $st['sex'];
                $class['bornDate'] = $st['bornDate'];
                $class['contactPhone'] = $st['contactPhone'];
                $class['email'] = $st['email'];
                $class['major'] = MajorInfo::find()->where(['id'=>(ClassInfo::find()->where(['id'=>$st['classId']])->select('majorId')->one()['majorId'])])->select('major')->one()['major'];
                $class['className'] = ClassInfo::find()->where(['id'=>$st['classId']])->select('className')->one()['className'];
                $class['grade'] = ClassInfo::find()->where(['id'=>$st['classId']])->select('grade')->one()['grade'];    
                $aaa[$st['id']] = $class;            
                array_push($c,$class);
            }
            $a['applyst'] = $c;
            array_push($list2,$a);
        }
        return $list2;
    }

    public function getList($type,$username)
    {
        if ($type == 'root') {
            $list = RecruitmentInfo::find()->andWhere(['not like','status',9])->andWhere(['or',['recruitStart' => null],['>','recruitStart',date("Y-m-d")]])->andWhere(['or',['recruitEnd' => null],['>','recruitStart',date("Y-m-d")]])->orderBy([
                'time' => SORT_ASC,
            ])->all();
        } else if ($type == 'company') {
            $comId = CompanyInfo::find()->where(['comAccount'=>$username])->select('id')->one()['id'];
            $list = RecruitmentInfo::find()->andWhere(['not like','status',9])->andWhere(['or',['recruitStart' => null],['>','recruitStart',date("Y-m-d")]])->andWhere(['or',['recruitEnd' => null],['>','recruitStart',date("Y-m-d")]])->andWhere(['comId'=>$comId])->orderBy([
                'time' => SORT_ASC,
            ])->all();
        } else if ($type == 'teacher') {
            $majorId = TeacherInfo::find()->where(['job_num'=>$username])->select('majorId')->one()['majorId'];
            $list = RecruitmentInfo::find()->andWhere(['not like','status',9])->andWhere(['or',['recruitStart' => null],['>','recruitStart',date("Y-m-d")]])->andWhere(['or',['recruitEnd' => null],['>','recruitStart',date("Y-m-d")]])->andWhere(['majorId'=>$majorId])->orderBy([
                'time' => SORT_ASC,
            ])->all(); return $list;
        }
		return $this->dataHandle($list);
    }

    public function getMajor()
    {
    	return MajorInfo::find()->where(['status'=>1])->select('id,major')->all();
    }
// /
    public function getGrade()
    {
        $grade = ClassInfo::find()->where(['status'=>1])->select('id,grade')->all();
        $a = [];
        foreach ($grade as $key => $value) {
            array_push($a,$value['grade']);
        }
        $b = array_unique($a);
        return $b;

    }

    public function modify($id,$subject,$content,$startDate,$endDate,$major,$grade,$num)
    {
    	return \Yii::$app->db->createCommand()->update('recruitment_info', 
                array(  
                    'subject'=>$subject, 
                    'content'=>$content,
                    'num'=>$num,
                    'time'=>date("Y-m-d"),
                    'startDate'=>$startDate,
                    'endDate'=>$endDate,
                    'status'=>0,
                    'majorId'=>MajorInfo::find()->where(['major'=>$major])->select('id')->one()['id'],
                    //
                    'grade' =>ClassInfo::find()->where(['grade'=>$grade])->select('id')->one()['id'],
                ), "id=:id",array(':id'=>$id))->execute();
    }

    public function delete($id)
    {
    	return \Yii::$app->db->createCommand()->update('recruitment_info',array('status' => 9),"id=:id",array(':id'=>$id))->execute();
    }

    public function modstatus($id,$status)
    {
    	return \Yii::$app->db->createCommand()->update('recruitment_info',array('status' => $status),"id=:id",array(':id'=>$id))->execute();
    }

    public function getCompany($power)
    {
        if ($power['userkind'] == '2') {
            return CompanyInfo::find()->where(['comAccount'=>$power["username"]])->one();
        } else {
            return CompanyInfo::find()->where(['status'=>1])->select('id,comName')->all();
        }
    }

    public function addRecruit($form,$major,$grade,$company)
    {
    	return \Yii::$app->db->createCommand()->insert('recruitment_info',
                array(  
                    'subject'=>$form['subject'], 
                    'content'=>$form['content'],
                    'num'=>$form['num'],
                    'time'=>date("Y-m-d"),
                    'startDate'=>$form['date'][0],
                    'endDate'=>$form['date'][1],
                    'status'=>0,
                    'majorId'=>$major,
                    ///
                    'grade' =>$grade,
                    // 'majorId'=>count($major)==1?$major[0]:implode(",", (array)$major),
                    'comId'=>$company
                ))->execute();
    }

    public function modifystatus($id,$status,$date)
    {
    	return \Yii::$app->db->createCommand()->update('recruitment_info', 
                array(  
                    'status'=>$status,
                    'studentEnddate'=>$date
                ), "id=:id",array(':id'=>$id))->execute();
    }

    public function getteacher()
    {
    	return TeacherInfo::find()->select('id,teacherName,job_num')->all();
    }

    //这些学生所有申请的招聘
    public function stRecruit($st)
    {
        $stu = [];
        $apply = [];      
        if ($st) {
            if (strpos($st,',')) {
                $stu = explode(',',$st);
            } else {
                array_push($stu,$st);
            }  

            $allst = RecruitmentInfo::find()->andWhere(['NOT', ['status' => 0]])->andWhere(['NOT', ['status' => 2]])->andWhere(['NOT', ['status' => 3]])->andWhere(['NOT', ['status' => 4]])->andWhere(['NOT', ['status' => 9]])->andWhere(['NOT', ['studentId' => null]])->select('id,subject,content,comId,studentId')->all();
            foreach ($stu as $key => $value) {
                $student = [];
                $student['student'] = StudentInfo::find()->where(['id'=>$value])->select('id,stuName,sno')->one();
                $recruit = [];
                foreach ($allst as $key2 => $value2) {
                    $a = [];
                    if (strpos($value2['studentId'],',')) {
                        $a = explode(',',$value2['studentId']);
                    } else {
                        array_push($a,$value2['studentId']);
                    }
                    foreach ($a as $key3 => $value3) {
                        if ($value == $value3) {
                            $b = [];
                            $b['subject'] = $value2['subject'];
                            $b['content'] = $value2['content'];
                            $b['comName'] = CompanyInfo::find()->where(['id'=>$value2['comId']])->select('comName')->one()['comName'];
                            array_push($recruit,$b);                            
                            break;
                        }
                    }                    
                }  
                $student['recruit'] = $recruit;  
                array_push($apply,$student);        
            }
            return $apply;
        } else {
            return $st;
        }  
    }

    public function gettstudent($id,$major)
    {
        $return = [];
        $st = RecruitmentInfo::find()->where(['id'=>$id])->select('studentId')->one()['studentId'];
        $st2 = RecruitmentInfo::find()->where(['id'=>$id])->select('arrangeStuId')->one()['arrangeStuId'];
        $arranAll = RecruitmentInfo::find()->andWhere(['status' => 5])->andWhere(['NOT', ['arrangeStuId' => null]])->andWhere(['NOT', ['id' => $id]])->select('arrangeStuId')->all();
        $a = [];
        if ($arranAll) {
            foreach ($arranAll as $key => $value) {
                if (strpos($value['arrangeStuId'],',')) {
                    $b = explode(',',$value['arrangeStuId']);
                    foreach ($b as $key2 => $value2) {
                        array_push($a,$value2);
                    }
                } else {
                    array_push($a,$value['arrangeStuId']);
                }
            }
            $a = array_unique($a);
            $st3 = count($a)==1?$a[0]:implode(",", $a);                   
        } else {
            $st3 = null;
        }
        $c = [];
        if ($st2) {
            if($a){
                foreach ($a as $key => $value) {
                    array_push($c,$value);
                }
            }
            if (strpos($st2,',')) {
                $d = explode(',',$st2);
                foreach ($d as $key => $value) {
                    array_push($c,$value);
                }
            } else {
                array_push($c,$st2);
            }
            $c = array_unique($c);
        } else {
            $c = $a;
        }  
        $majorId = RecruitmentInfo::find()->where(['id'=>$id])->select('majorId')->one()['majorId'];
        // $majorId = MajorInfo::find()->where(['major'=>$major])->select('id')->one()['id'];
        $classId = ClassInfo::find()->andWhere(['majorId'=>$majorId])->andWhere(['grade'=>date('Y')-2])
->select('id')->one()['id'];
        $allstu = StudentInfo::find()->andWhere(['status'=>1])->andWhere(['classId'=>$classId])->select('id')->all();
        $b = [];
        if ($allstu) {
            foreach ($allstu as $key => $value) {
                if (array_search($value['id'],$c)) {
                } else {
                    if (array_search($value['id'],$c) === 0) {
                    } else {
                        array_push($b,$value['id']);
                    }
                }            
            }
            $st4 = count($b)==1?$b[0]:implode(",", $b);
        } else {
            $st4 = null;
        }                           
        //申请此条招聘信息的学生
        $return['thisApply'] = $this->stRecruit($st);
        //该条招聘已安排的学生
        $return['thisArrange'] = $this->stRecruit($st2);
        //除了该条招聘外已安排的学生
        $return['arrange'] = $this->stRecruit($st3);
        //未必安排的学生
        // $return['unArrange'] = $id;
        $return['unArrange'] = $this->stRecruit($st4);

        return $return;
    }

    public function arrangeSt($student,$id)
    {
        if (count($student)!=0) {
            $arranAll = RecruitmentInfo::find()->andWhere(['NOT', ['status' => 0]])->andWhere(['NOT', ['status' => 2]])->andWhere(['NOT', ['status' => 3]])->andWhere(['NOT', ['status' => 4]])->andWhere(['NOT', ['status' => 9]])->andWhere(['NOT', ['arrangeStuId' => null]])->andWhere(['NOT', ['id' => $id]])->select('id,arrangeStuId')->all();
            if ($arranAll) {
                foreach ($arranAll as $key2 => $value2) {
                    $compare = [];
                    if (strpos($value2['arrangeStuId'],',')) {
                        $b = explode(',',$value2['arrangeStuId']);
                        foreach ($b as $key3 => $value3) {
                            array_push($compare,(int)$value3);
                        }
                    } else {
                        array_push($compare,(int)$value2['arrangeStuId']);
                    }     
                    foreach ($compare as $key4 => $value4) {
                        if (array_search($value4,$student)) {
                            array_splice($compare,$key4,1);
                        } else {
                            if (array_search($value4,$student) === 0) {
                                array_splice($compare,$key4,1);
                            }
                        } 
                    }          
                    if (count($compare)==0) {
                        \Yii::$app->db->createCommand()->update('recruitment_info', 
                            array(  
                                'arrangeStuId'=>null
                            ), "id=:id",array(':id'=>$value2['id']))->execute();
                    } else {
                        \Yii::$app->db->createCommand()->update('recruitment_info', 
                            array(  
                                'arrangeStuId'=>count($compare)==1?$compare[0]:implode(",", $compare)
                            ), "id=:id",array(':id'=>$value2['id']))->execute();
                    }
                }
            } else {
                # code...
            }          
                       
            $arranId = count($student)==1?$student[0]:implode(",", $student);
            return \Yii::$app->db->createCommand()->update('recruitment_info', 
                array(  
                    'status'=>5,
                    'arrangeStuId'=>$arranId
                ), "id=:id",array(':id'=>$id))->execute();
        }
    }

    public function addmap($rowId,$form,$lng,$lat,$address)
    {
        return \Yii::$app->db->createCommand()->update('recruitment_info', 
                array( 
                    'recruitStart'=>$form['date'][0],
                    'recruitEnd'=>$form['date'][1],
                    'clockMor'=>$form['startTime'],
                    'clockEve'=>$form['endTime'],
                    'lng'=>$lng,
                    'lat'=>$lat,
                    'address'=>$address,
                    'scope'=>$form['scope'],
                    'status'=>3
                ), "id=:id",array(':id'=>$rowId))->execute();
    }

}