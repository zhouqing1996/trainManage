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
use common\models\Clock;
use common\models\VwStudentInfo;
date_default_timezone_set('PRC');

class ClockModel extends Model{

    public function getclass($recruitId,$power,$user)
    {
        if ($recruitId) {
            $recuirt = RecruitmentInfo::find()->where(['id'=>$recruitId])->one();
            $stu = [];
            if (strpos($recuirt['arrangeStuId'],',')) {
                $b = explode(',',$recuirt['arrangeStuId']);
                foreach ($b as $key => $value) {
                    array_push($stu,StudentInfo::find()->where(['id'=>$value])->one());
                }
            } else {
                array_push($stu,StudentInfo::find()->where(['id'=>$recuirt['arrangeStuId']])->one());
            }
            $student = [];
            foreach ($stu as $key => $value) {
                $a['value'] = $value['sno'];
                $a['label'] = $value['stuName'];
                array_push($student,$a);
            }
            $return['major'] = MajorInfo::find()->where(['id'=>$recuirt['majorId']])->one();
            $return['student'] = $student;
            return $return;
        } else {
            if ($power['userkind'] == '1') {
                if ($power['super'] == '1') {
                    $list = ClassInfo::find()->andWhere(['status'=>1])->orderBy(['majorId' => SORT_DESC])->orderBy(['grade' => SORT_DESC])->all();
                } else {
                    $majorId = TeacherInfo::find()->andWhere(['job_num'=>$user])->andWhere(['status'=>1])->select('majorId')->one()['majorId'];
                    $list = ClassInfo::find()->andWhere(['status'=>1])->andWhere(['majorId'=>$majorId])->orderBy(['grade' => SORT_DESC])->all();
                }           
            }
            $group = [];
            foreach($list as $key=>$value){
                $d = explode(' ',$value['majorId']);
                $group[$d[0]][] = $value;
            }
            $class=[];
            foreach ($group as $key => $value) {
                $g=[];
                foreach ($value as $key2 => $value2) {
                    $g[$value2['grade']][] = $value2['className'];
                }
                $class[MajorInfo::find()->where(['id'=>$key])->select('major')->one()['major']] = $g;
            }
            $return = [];
            foreach ($class as $key => $value) {
                $children=[];
                foreach ($value as $key2 => $value2) {
                    $children2=[];
                    foreach ($value2 as $key3 => $value3) {
                        $c=[];
                        $c['value'] = $value3;
                        $c['label'] = $value3;
                        array_push($children2,$c);
                    }
                    $b=[];
                    $b['value'] = $key2;
                    $b['label'] = $key2;
                    $b['children'] = $children2;
                    array_push($children,$b);
                }
                $a=[];
                $a['value'] = $key;
                $a['label'] = $key;
                $a['children'] = $children;
                array_push($return,$a);
            }
            $d=[];
            $d['value'] = '不限';
            $d['label'] = '不限';
            array_push($return,$d);
            return $return;
        }
    }

    public function clockdata($data,$class)
    {
        if ($data) {
            $group = [];  
            foreach($data as $key=>$value){
                $d = explode(' ',$value['stuId']);
                $group[$d[0]][] = $value;
            }
            $newgroup = [];
            foreach ($group as $key => $value) {
                $st = VwStudentInfo::find()->where(['id'=>$key])->select('className,major,stuName')->one();
                $newgroup[$st['stuName']] = $value;
            }
            $return = [];
            foreach ($newgroup as $key => $value) {
                $time = RecruitmentInfo::find()->where(['id'=>$value[0]['recruitId']])->select('recruitStart,recruitEnd,clockMor,clockEve,lng,lat,address,scope')->one();
                $mor = $time['clockMor'];
                $eve = $time['clockEve'];
                $lng = $time['lng'];
                $lat = $time['lat'];
                $address = $time['address'];
                $scope = $time['scope'];
                $group2 = [];  
                foreach($value as $key2=>$value2){
                    $dd = explode(' ',$value2['time']);
                    $group2[$dd[0]][] = $value2;
                }
                $clock = [];
                $unclock = [];
                foreach ($group2 as $key3 => $value3) {
                    $mf = 0;
                    $ef = 0;
                    if ($this->getdistance($lng,$lat,$value3[0]['lng'],$value3[0]['lat'])<=$scope) {
                        if (substr(explode(' ',$value3[0]['time'])[1],0,5)>=$eve) {
                            $mf = 1;
                        }                   
                    }
                    if ($this->getdistance($lng,$lat,$value3[count($value3)-1]['lng'],$value3[count($value3)-1]['lat'])<=$scope) {
                        if (substr(explode(' ',$value3[count($value3)-1]['time'])[1],0,5)<=$mor) {
                            $ef = 1;
                        }                    
                    }
                    if ($mf == 1 && $ef == 1) {
                        array_push($clock,$key3);
                    } else {
                        array_push($unclock,$key3);
                    }   
                }
                $abc=[];
                $abc[$key]['已打卡'] = count($clock);
                $abc[$key]['未打卡'] = count($unclock);
                array_push($return,$abc);
            }
            $aaa = [];
            $bbb = [];
            foreach ($return as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    array_push($aaa,$key2);
                    array_push($bbb,$value2['已打卡']);
                }
            }
            array_multisort($bbb,SORT_DESC,$aaa);
        } else {
            $aaa = [];
            $bbb = [];
        }
        foreach ($bbb as $key => $value) {
             if ($value == 0) {
                 array_splice($aaa,$key,1);
                 array_splice($bbb,$key,1);
             }
         } 
        return [$aaa,$bbb];
    }

    public function clocklist($type,$date,$sno,$class,$user)
    {
        $stuId = StudentInfo::find()->where(['sno'=>$sno])->select('id')->one()['id'];
        if ($type == 'root') {
            $data = Clock::find()
                ->andWhere(['status'=>1])->orderBy(['time' => SORT_DESC])
                ->andWhere(empty($stuId)?['is','stuId',null] or ['not like','stuId','']:['stuId'=>$stuId])
                ->andFilterWhere(['between','time',empty($date)?'':$date[0].' 00:00:00',empty($date)?'':date("Y-m-d",strtotime("+1 day",strtotime($date[1]))).' 00:00:00'])
                ->all();
            $data1 = [];
            if ($class) {
                if ($class[0] != '不限') {
                    if (count($class) == 1) {
                        $majorId = MajorInfo::find()->andWhere(['major'=>$class[0]])->andWhere(['status'=>1])->select('id')->one()['id'];
                        $c = ClassInfo::find()->where(['majorId'=>$majorId])->select('id')->all();
                        $classId = [];
                        foreach ($c as $key => $value) {
                            array_push($classId,$value['id']);
                        }
                        $allStu = StudentInfo::find()->where(['status'=>1])->all();
                        $stuId = [];
                        foreach ($allStu as $key => $value) {
                            if (in_array($value['classId'],$classId)) {
                                array_push($stuId,$value['id']);
                            }
                        }
                        foreach ($data as $key => $value) {
                            if (in_array($value['stuId'],$stuId)) {
                                array_push($data1,$value);
                            }
                        }
                    } elseif (count($class) == 2) {
                        $majorId = MajorInfo::find()->andWhere(['major'=>$class[0]])->andWhere(['status'=>1])->select('id')->one()['id'];
                        $c = ClassInfo::find()->andWhere(['majorId'=>$majorId])->andWhere(['grade'=>$class[1]])->select('id')->all();
                        $classId = [];
                        foreach ($c as $key => $value) {
                            array_push($classId,$value['id']);
                        }
                        $allStu = StudentInfo::find()->where(['status'=>1])->all();
                        $stuId = [];
                        foreach ($allStu as $key => $value) {
                            if (in_array($value['classId'],$classId)) {
                                array_push($stuId,$value['id']);
                            }
                        }
                        foreach ($data as $key => $value) {
                            if (in_array($value['stuId'],$stuId)) {
                                array_push($data1,$value);
                            }
                        }
                    } elseif (count($class) == 3) {
                        $majorId = MajorInfo::find()->andWhere(['major'=>$class[0]])->andWhere(['status'=>1])->select('id')->one()['id'];
                        $c = ClassInfo::find()->andWhere(['majorId'=>$majorId])->andWhere(['grade'=>$class[1]])->andWhere(['className'=>$class[2]])->select('id')->all();
                        $classId = [];
                        foreach ($c as $key => $value) {
                            array_push($classId,$value['id']);
                        }
                        $allStu = StudentInfo::find()->where(['status'=>1])->all();
                        $stuId = [];
                        foreach ($allStu as $key => $value) {
                            if (in_array($value['classId'],$classId)) {
                                array_push($stuId,$value['id']);
                            }
                        }
                        foreach ($data as $key => $value) {
                            if (in_array($value['stuId'],$stuId)) {
                                array_push($data1,$value);
                            }
                        }
                    }
                } else {
                    $data1 = $data;
                }
            } else {
                $data1 = $data;
            }
        } else if ($type == 'teacher') {
            $data = Clock::find()
                ->andWhere(['status'=>1])->orderBy(['time' => SORT_DESC])
                ->andWhere(empty($stuId)?['is','stuId',null] or ['not like','stuId','']:['stuId'=>$stuId])
                ->andFilterWhere(['between','time',empty($date)?'':$date[0].' 00:00:00',empty($date)?'':date("Y-m-d",strtotime("+1 day",strtotime($date[1]))).' 00:00:00'])
                ->all();
            $majorId = TeacherInfo::find()->andWhere(['job_num'=>$user])->andWhere(['status'=>1])->select('majorId')->one()['majorId'];
            $data1 = [];
            if ($class) {
                if ($class[0] != '不限') {
                    if (count($class) == 1) {
                        $c = ClassInfo::find()->where(['majorId'=>$majorId])->select('id')->all();
                        $classId = [];
                        foreach ($c as $key => $value) {
                            array_push($classId,$value['id']);
                        }
                        $allStu = StudentInfo::find()->where(['status'=>1])->all();
                        $stuId = [];
                        foreach ($allStu as $key => $value) {
                            if (in_array($value['classId'],$classId)) {
                                array_push($stuId,$value['id']);
                            }
                        }
                        foreach ($data as $key => $value) {
                            if (in_array($value['stuId'],$stuId)) {
                                array_push($data1,$value);
                            }
                        }
                    } elseif (count($class) == 2) {
                        $c = ClassInfo::find()->andWhere(['majorId'=>$majorId])->andWhere(['grade'=>$class[1]])->select('id')->all();
                        $classId = [];
                        foreach ($c as $key => $value) {
                            array_push($classId,$value['id']);
                        }
                        $allStu = StudentInfo::find()->where(['status'=>1])->all();
                        $stuId = [];
                        foreach ($allStu as $key => $value) {
                            if (in_array($value['classId'],$classId)) {
                                array_push($stuId,$value['id']);
                            }
                        }
                        foreach ($data as $key => $value) {
                            if (in_array($value['stuId'],$stuId)) {
                                array_push($data1,$value);
                            }
                        }
                    } elseif (count($class) == 3) {
                        $c = ClassInfo::find()->andWhere(['majorId'=>$majorId])->andWhere(['grade'=>$class[1]])->andWhere(['className'=>$class[2]])->select('id')->all();
                        $classId = [];
                        foreach ($c as $key => $value) {
                            array_push($classId,$value['id']);
                        }
                        $allStu = StudentInfo::find()->where(['status'=>1])->all();
                        $stuId = [];
                        foreach ($allStu as $key => $value) {
                            if (in_array($value['classId'],$classId)) {
                                array_push($stuId,$value['id']);
                            }
                        }
                        foreach ($data as $key => $value) {
                            if (in_array($value['stuId'],$stuId)) {
                                array_push($data1,$value);
                            }
                        }
                    }
                } else {
                    $c = ClassInfo::find()->where(['majorId'=>$majorId])->select('id')->all();
                    $classId = [];
                    foreach ($c as $key => $value) {
                        array_push($classId,$value['id']);
                    }
                    $allStu = StudentInfo::find()->where(['status'=>1])->all();
                    $stuId = [];
                    foreach ($allStu as $key => $value) {
                        if (in_array($value['classId'],$classId)) {
                            array_push($stuId,$value['id']);
                        }
                    }
                    foreach ($data as $key => $value) {
                        if (in_array($value['stuId'],$stuId)) {
                            array_push($data1,$value);
                        }
                    }
                }
            } else {
               $c = ClassInfo::find()->where(['majorId'=>$majorId])->select('id')->all();
                $classId = [];
                foreach ($c as $key => $value) {
                    array_push($classId,$value['id']);
                }
                $allStu = StudentInfo::find()->where(['status'=>1])->all();
                $stuId = [];
                foreach ($allStu as $key => $value) {
                    if (in_array($value['classId'],$classId)) {
                        array_push($stuId,$value['id']);
                    }
                }
                foreach ($data as $key => $value) {
                    if (in_array($value['stuId'],$stuId)) {
                        array_push($data1,$value);
                    }
                } 
            }    
        }
        return $this->clockdata($data1,$class); 
    }

    public function clocklist2($rowId,$date,$sno)
    {
        $stuId = StudentInfo::find()->where(['sno'=>$sno])->select('id')->one()['id'];
        $data = Clock::find()
                ->andWhere(['status'=>1])->orderBy(['time' => SORT_DESC])
                ->andWhere(['recruitId'=>$rowId])
                ->andWhere(empty($stuId)?['is','stuId',null] or ['not like','stuId','']:['stuId'=>$stuId])
                ->andFilterWhere(['between','time',empty($date)?'':$date[0].' 00:00:00',empty($date)?'':date("Y-m-d",strtotime("+1 day",strtotime($date[1]))).' 00:00:00'])
                ->all();
        if ($data) {
            $group = [];  
            foreach($data as $key=>$value){
                $group[$value['stuId']][] = $value;
            }
            $newgroup = [];
            foreach ($group as $key => $value) {
                $st = VwStudentInfo::find()->where(['id'=>$key])->select('className,major,stuName')->one();
                $newgroup[$st['stuName']] = $value;
            }
            $return = [];
            foreach ($newgroup as $key => $value) {
                $time = RecruitmentInfo::find()->where(['id'=>$value[0]['recruitId']])->select('recruitStart,recruitEnd,clockMor,clockEve,lng,lat,address,scope')->one();
                $mor = $time['clockMor'];
                $eve = $time['clockEve'];
                $lng = $time['lng'];
                $lat = $time['lat'];
                $address = $time['address'];
                $scope = $time['scope'];
                $group2 = [];  
                foreach($value as $key2=>$value2){
                    $dd = explode(' ',$value2['time']);
                    $group2[$dd[0]][] = $value2;
                }
                $clock = [];
                $unclock = [];
                foreach ($group2 as $key3 => $value3) {
                    $mf = 0;
                    $ef = 0;
                    if ($this->getdistance($lng,$lat,$value3[0]['lng'],$value3[0]['lat'])<=$scope) {
                        if (substr(explode(' ',$value3[0]['time'])[1],0,5)>=$eve) {
                            $mf = 1;
                        }                   
                    }
                    if ($this->getdistance($lng,$lat,$value3[count($value3)-1]['lng'],$value3[count($value3)-1]['lat'])<=$scope) {
                        if (substr(explode(' ',$value3[count($value3)-1]['time'])[1],0,5)<=$mor) {
                            $ef = 1;
                        }                    
                    }
                    if ($mf == 1 && $ef == 1) {
                        array_push($clock,$key3);
                    } else {
                        array_push($unclock,$key3);
                    }   
                }
                $abc=[];
                $abc[$key]['已打卡'] = count($clock);
                $abc[$key]['未打卡'] = count($unclock);
                array_push($return,$abc);
            }
            $aaa = [];
            $bbb = [];
            foreach ($return as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    array_push($aaa,$key2);
                    array_push($bbb,$value2['已打卡']);
                }
            }
            array_multisort($bbb,SORT_DESC,$aaa);
        } else {
            $aaa = [];
            $bbb = [];
        }
        return [$aaa,$bbb];
    }

    function getdistance($lng1, $lat1, $lng2, $lat2) {
        $radLat1 = deg2rad($lat1); 
        $radLat2 = deg2rad($lat2);
        $radLng1 = deg2rad($lng1);
        $radLng2 = deg2rad($lng2);
        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137 * 1000;
        return $s;
    }

}

?>