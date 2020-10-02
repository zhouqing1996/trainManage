<?php
namespace backend\models\student;

use Yii;
use yii\base\Model;
use common\models\RecruitmentInfo;
use common\models\CompanyInfo;
use common\models\MajorInfo;
use common\models\TeacherInfo;
use common\models\StudentInfo;
use common\models\ClassInfo;
use common\models\Summary;
use common\models\Clock;
use common\models\SurveyInfo;
use common\models\Remind;
date_default_timezone_set('PRC');
/**
 * Login form
 */
class StudentModel extends Model{
    public function getrecruit($username)
    {
    	$nowdate = date("Y-m-d");
    	$stuId = StudentInfo::find()->where(['sno'=>$username])->select('id')->one()['id'];
    	$recruit = RecruitmentInfo::find()->andWhere(['status'=>1])->andWhere(['>=', 'studentEnddate', $nowdate])->all();
    	$classId = StudentInfo::find()->where(['sno'=>$username])->select('classId')->one()['classId'];
	    $majorId = ClassInfo::find()->where(['id'=>$classId])->select('majorId')->one()['majorId'];
    	$return = [];
        foreach ($recruit as $key => $value) {
            $d=[];
            $d['id'] = $value['id'];
            $d['subject'] = $value['subject'];
            $d['content'] = $value['content'];
            $d['startDate'] = $value['startDate'];
            $d['endDate'] = $value['studentEnddate'];
            $d['time'] = $value['time'];
            $d['company'] = CompanyInfo::find()->where(['id'=>$value['comId']])->select('comName')->one()['comName'];
            if ($value['studentId']) {
                $e=[];
                $f=[];
                $d['apply'] = 0;
                if (strpos($value['studentId'],',')) {
                    $e = explode(',',$value['studentId']);
                    foreach ($e as $key2 => $value2) {
                        array_push($f,$value2);
                    }
                    foreach ($f as $key3 => $value3) {                              
                        if ($value3 == $stuId) {
                            $d['apply'] = 1;                                
                        }
                    }
                } else {
                    if ($value['studentId'] == $stuId) {
                        $d['apply'] = 1;
                    }
                }             
            } else {
                $d['apply'] = 0;
            }           
            array_push($return,$d);
        }

     //    $a = [];
    	// if ($recruit) {
    	// 	foreach ($recruit as $key => $value) {
	    // 		if ($value['majorId']) {
	    // 			if ($value['majorId'] == 13) {
	    // 				array_push($a,$value);
	    // 			} else {
	    // 				//包含该学生专业和13的
	    // 				$b = [];
	    // 				$c = [];
	    // 				if (strpos($value['majorId'],',')) {
	    //                     $b = explode(',',$value['majorId']);
	    //                     foreach ($b as $key2 => $value2) {
	    //                         array_push($c,$value2);
	    //                     }
	    //                 } else {
	    //                     array_push($c,$value2);
	    //                 }
	    //                 foreach ($c as $key3 => $value3) {                 	         	
	    //                 	if ($value3 == 13) {
	    //                 		array_push($a,$value);	                    		
	    //                 	} else if ($value3 == $majorId) {
	    //                 		array_push($a,$value);
	    //                 	}
	    //                 }
	    // 			}
	    // 		} else {
	    // 			array_push($a,$value);
	    // 		}
	    // 	}
    	// }
    	// $return = [];
    	// foreach ($a as $key => $value) {
    	// 	$d=[];
    	// 	$d['id'] = $value['id'];
    	// 	$d['subject'] = $value['subject'];
    	// 	$d['content'] = $value['content'];
    	// 	$d['startDate'] = $value['startDate'];
    	// 	$d['endDate'] = $value['studentEnddate'];
    	// 	$d['time'] = $value['time'];
    	// 	$d['company'] = CompanyInfo::find()->where(['id'=>$value['comId']])->select('comName')->one()['comName'];
    	// 	if ($value['studentId']) {
    	// 		$e=[];
    	// 		$f=[];
    	// 		$d['apply'] = 0;
    	// 		if (strpos($value['studentId'],',')) {
	    //             $e = explode(',',$value['studentId']);
	    //             foreach ($e as $key2 => $value2) {
	    //                 array_push($f,$value2);
	    //             }
	    //             foreach ($f as $key3 => $value3) {                 	         	
	    //             	if ($value3 == $stuId) {
	    //             		$d['apply'] = 1;	                    		
	    //             	}
	    //             }
	    //         } else {
	    //             if ($value['studentId'] == $stuId) {
	    //             	$d['apply'] = 1;
	    //             }
	    //         }	          
    	// 	} else {
    	// 		$d['apply'] = 0;
    	// 	}    		
    	// 	array_push($return,$d);
    	// }
    	return $return;
    }

    public function getsurvey($username)
    {
        $stuId = StudentInfo::find()->where(['sno'=>$username])->select('id')->one()['id'];
        $survey = SurveyInfo::find()->andWhere(['NOT', ['status' => 0]])->andWhere(['type'=>20])->andWhere(['NOT', ['pushId' => null]])->select('id,title,content,pushId')->all();
        $return = [];
        foreach ($survey as $key => $value) {
            $p = strpos($value['pushId'],',')?explode(',',$value['pushId']):[$value['pushId']];
            foreach ($p as $key2 => $value2) {
                $st = RecruitmentInfo::find()->where(['id'=>$value2])->select('arrangeStuId')->one()['arrangeStuId'];
                $stu = strpos($st,',')?explode(',',$st):[$st];
                if (in_array($stuId, $stu)) {
                    array_push($return,$value);
                    break;
                }
            }
        }
        return $return;
    }

    public function getremind($username)
    {
        $stuId = StudentInfo::find()->where(['sno'=>$username])->select('id')->one()['id'];
        $nw = date("w")==0?7:date("w");
        $nowdate = date("Y-m-d");
        $remind = Remind::find()->where(['status' => 1])->all();
        $return = [];
        foreach ($remind as $key => $value) {
            $p = strpos($value['pushId'],',')?explode(',',$value['pushId']):[$value['pushId']];
            $computeweek = $this->computeWeek($value['date'],$nowdate);
            foreach ($p as $key2 => $value2) {
                $st = RecruitmentInfo::find()->where(['id'=>$value2])->select('arrangeStuId')->one()['arrangeStuId'];
                $stu = strpos($st,',')?explode(',',$st):[$st];
                if (in_array($stuId, $stu)) {
                    $nextweek = $this->getNextWeekOf($value['date']);
                    $w = strpos($value['repeat'],',')?explode(',',$value['repeat']):[$value['repeat']];
                    if ($computeweek == 0) {
                        foreach ($w as $key3 => $value3) {
                            if ($value3 <= $nw) {
                                $nowweek = $this->getNextWeekOf($this->getLastMonday());
                                $a = [];
                                $a['content'] = $value['content'];
                                $a['date'] = $nowweek[$value3-1];
                                array_push($return,$a);
                            }
                            if ($value3 >= date('N', strtotime($value['date']))) {
                                $nowweek = $this->getNextWeekOf(date('Y-m-d',strtotime('-1 week last monday')));
                                if ($nowweek[$value3-1] <= date('Y-m-d') && $nowweek[$value3-1] >= $value['date']) {
                                    $a = [];
                                    $a['content'] = $value['content'];
                                    $a['date'] = $nowweek[$value3-1];
                                    array_push($return,$a);
                                }
                            }
                        }  
                    } else {
                        for ($i=0; $i < $computeweek; $i++) { 
                            foreach ($w as $key3 => $value3) {
                                $a = [];
                                $a['content'] = $value['content'];
                                $a['date'] = $nextweek[$value3-1];
                                array_push($return,$a);
                            }
                            $nextweek = $this->getNextWeekOf($nextweek[6]);
                        } 
                    }  
                }
            }
        }
        return $return;
    }

    function getLastMonday()
    {
        if (date('l',time()) == 'Monday') 
            return date('Y-m-d',strtotime('last monday'));
        return date('Y-m-d',strtotime('-1 week last monday'));
    }

    function getNextWeekOf($date)
    {    
        $dates = array();    
        $time  = strtotime($date.' 12:00:00'); 
        $w     = date('w', $time);     
        if($w == 0){        
            $nextMonday = 1;    
        } else {        
            $nextMonday = 7 - $w + 1;    
        }    
        for($i = $nextMonday; $i<$nextMonday + 7; $i++){        
            $dates[] = date('Y-m-d', $time + 3600*24*$i);    
        }    
        return $dates;
    }

    function computeWeek($date1,$date2){
        $diff = strtotime($date2) - strtotime($date1);
        $week = floor($diff/(24*60*60*7));
        $day = $diff%(24*60*60*7)/(24*60*60);
        $res = $week;
        return $res;
    }

    public function getstinfo($username)
    {
    	$st = StudentInfo::find()->where(['sno'=>$username])->one();
    	$classId = StudentInfo::find()->where(['sno'=>$username])->select('classId')->one()['classId'];
	    $majorId = ClassInfo::find()->where(['id'=>$classId])->select('majorId')->one()['majorId'];
	    $stuId = StudentInfo::find()->where(['sno'=>$username])->select('id')->one()['id'];
    	$student=[];
		$student['id'] = $st['id'];
		$student['stuName'] = $st['stuName'];
		$student['sno'] = $st['sno'];
		$student['identity'] = $st['identity'];
		$student['sex'] = $st['sex'] == '1'?'男':'女';
		$student['bornDate'] = $st['bornDate'];
		$student['contactPhone'] = $st['contactPhone'];
		$student['email'] = $st['email'];
		$student['major'] = MajorInfo::find()->where(['id'=>$majorId])->select('major')->one()['major'];
		$student['class'] = ClassInfo::find()->where(['id'=>$classId])->select('className,grade')->one();
		$student['recruit']=[];
		$recruit = RecruitmentInfo::find()->andWhere(['status'=>3])->andWhere(['>', 'recruitEnd', date("Y-m-d")])->andWhere(['NOT', ['arrangeStuId' => null]])->all();
		if ($recruit) {			
			foreach ($recruit as $key => $value) {
    			$a=[];
    			$b=[];
    			if (strpos($value['arrangeStuId'],',')) {
	                $a = explode(',',$value['arrangeStuId']);
	                foreach ($a as $key2 => $value2) {
	                    array_push($b,$value2);
	                }
	                foreach ($b as $key3 => $value3) {                 	         	
	                	if ($value3 == $stuId) {
	                		$student['recruit']['id'] = $value['id'];             		
	                		$student['recruit']['company'] = CompanyInfo::find()->where(['id'=>$value['comId']])->select('comName')->one()['comName'];
	                		$student['recruit']['subject'] = $value['subject'];
	                		$student['recruit']['content'] = $value['content'];
	                		$student['recruit']['teacher'] = TeacherInfo::find()->where(['id'=>$value['teacherId']])->select('teacherName')->one()['teacherName'];
	                		$student['recruit']['recruitStart'] = $value['recruitStart'];
	                		$student['recruit']['recruitEnd'] = $value['recruitEnd'];
	                		break 2;
	                	}
	                }
	            } else {
	                if ($value['arrangeStuId'] == $stuId) {
	                	$student['recruit']['id'] = $value['id'];             		
                		$student['recruit']['company'] = CompanyInfo::find()->where(['id'=>$value['comId']])->select('comName')->one()['comName'];
                		$student['recruit']['subject'] = $value['subject'];
                		$student['recruit']['content'] = $value['content'];
                		$student['recruit']['teacher'] = TeacherInfo::find()->where(['id'=>$value['teacherId']])->select('teacherName')->one()['teacherName'];
                		$student['recruit']['recruitStart'] = $value['recruitStart'];
                		$student['recruit']['recruitEnd'] = $value['recruitEnd'];
                		break;
	                }
	            }	          
			}
		}

    	return $student;
    }

    public function modifyinfo($username,$contactPhone,$email)
    {
    	return \Yii::$app->db->createCommand()->update('student_info', 
                array(
                    'contactPhone'=>$contactPhone,
                    'email'=>$email
                ), "sno=:sno",array(':sno'=>$username))->execute();
    }

    public function apply($id,$username)
    {
    	$stuId = StudentInfo::find()->where(['sno'=>$username])->select('id')->one()['id'];
    	$applyId = RecruitmentInfo::find()->where(['id'=>$id])->select('studentId')->one()['studentId'];    	
    	if ($applyId) {
            $newApplyId = $applyId.','.$stuId;          
		} else {
			$newApplyId = $stuId;
		}
		return \Yii::$app->db->createCommand()->update('recruitment_info', 
                array(
                    'studentId'=>$newApplyId
                ), "id=:id",array(':id'=>$id))->execute();
    }

    public function cancelapply($id,$username)
    {
    	$stuId = StudentInfo::find()->where(['sno'=>$username])->select('id')->one()['id'];
    	$applyId = RecruitmentInfo::find()->where(['id'=>$id])->select('studentId')->one()['studentId'];
    	if (strpos($applyId,',')) {
    		$b=[];
            $a = explode(',',$applyId);
            foreach ($a as $key2 => $value2) {                 	         	
            	if ($value2 != $stuId) {
            		array_push($b,$value2);	                    		
            	}
            }
            $applyId = count($b)==1?$b[0]:implode(",", $b);
        } else {
            $applyId = null;
        }
        return \Yii::$app->db->createCommand()->update('recruitment_info', 
                array(
                    'studentId'=>$applyId
                ), "id=:id",array(':id'=>$id))->execute();
    }

    public function applylist($username)
    {
    	$stuId = StudentInfo::find()->where(['sno'=>$username])->select('id')->one()['id'];
    	$recruit = RecruitmentInfo::find()->andWhere(['NOT', ['status' => 0]])->andWhere(['NOT', ['status' => 9]])->andWhere(['NOT', ['status' => 2]])->andWhere(['NOT', ['studentId' => null]])->all();
    	$stRecruit=[];
    	if ($recruit) {
    		foreach ($recruit as $key => $value) {
    			$a=[];
    			$b=[];
    			if (strpos($value['studentId'],',')) {
	                $a = explode(',',$value['studentId']);
	                foreach ($a as $key2 => $value2) {
	                    array_push($b,$value2);
	                }
	                foreach ($b as $key3 => $value3) {                 	         	
	                	if ($value3 == $stuId) {
	                		$st=[];
	                		$st['id'] = $value['id'];             		
	                		$st['company'] = CompanyInfo::find()->where(['id'=>$value['comId']])->select('comName')->one()['comName'];
	                		$st['subject'] = $value['subject'];
	                		$st['content'] = $value['content'];
	                		$st['status'] = $value['status'];
	                		$st['comId'] = $value['comId'];
	                		$st['teacherId'] = $value['teacherId'];
	                		$st['arrangeStuId'] = $value['arrangeStuId'];
	                		$st['teacher'] = TeacherInfo::find()->where(['id'=>$value['teacherId']])->select('teacherName')->one()['teacherName'];
	                		array_push($stRecruit,$st);
	                	}
	                }
	            } else {
	                if ($value['studentId'] == $stuId) {
	                	$st=[];
	                	$st['id'] = $value['id'];             		
                		$st['company'] = CompanyInfo::find()->where(['id'=>$value['comId']])->select('comName')->one()['comName'];
                		$st['subject'] = $value['subject'];
                		$st['content'] = $value['content'];
                		$st['status'] = $value['status'];
                		$st['comId'] = $value['comId'];
                		$st['teacherId'] = $value['teacherId'];
                		$st['arrangeStuId'] = $value['arrangeStuId'];
                		$st['teacher'] = TeacherInfo::find()->where(['id'=>$value['teacherId']])->select('teacherName')->one()['teacherName'];
                		array_push($stRecruit,$st);
	                }
	            }
	        }
	        $return=[];
	        foreach ($stRecruit as $key => $value) {
	        	$new=[];
            	$new['id'] = $value['id'];             		
        		$new['company'] = CompanyInfo::find()->where(['id'=>$value['comId']])->select('comName')->one()['comName'];
        		$new['subject'] = $value['subject'];
        		$new['content'] = $value['content'];
        		$new['teacher'] = TeacherInfo::find()->where(['id'=>$value['teacherId']])->select('teacherName')->one()['teacherName'];
	        	if ($value['status'] == 3) {
            		if ($value['arrangeStuId']) {
            			$aa=[];
	            		$bb=[];
	            		if (strpos($value['arrangeStuId'],',')) {
			                $aa = explode(',',$value['arrangeStuId']);
			                foreach ($aa as $key2 => $value2) {
			                    array_push($bb,$value2);
			                }
			                $flag = 0;
			                foreach ($bb as $key3 => $value3) {                 	         	
			                	if ($value3 == $stuId) {
			                		$flag = 1;
			                	}
			                }
			                if ($flag == 0) {
			                	$new['apply'] = '申请失败';
			                } else {
			                	$new['apply'] = '申请成功';
			                }
			                
			            } else {
			                if ($value['arrangeStuId'] == $stuId) {
			                	$new['apply'] = '申请成功';
			                } else {
			                	$new['apply'] = '申请失败';
			                }
			            }
            		} else {
            			$new['apply'] = '申请失败';
            		}          		
    			} else if ($value['status'] == 1 || $value['status'] == 5) {
    				$new['apply'] = '正在申请中';
    			} else if ($value['status'] == 4) {
    				$new['apply'] = '申请失败';
    			}
    			array_push($return,$new);
	        }	          	
    	}
    	return $return;	
    }

    public function addsummary($username,$subject,$content,$date,$type,$recruitId)
    {
    	$stuId = StudentInfo::find()->where(['sno'=>$username])->select('id')->one()['id'];
    	$summary = Summary::find()->andWhere(['stuId'=>$stuId])->andWhere(['date'=>$date])->andWhere(['type'=>$type])->one();
    	if ($summary) {
    		$id = Summary::find()->andWhere(['stuId'=>$stuId])->andWhere(['date'=>$date])->andWhere(['type'=>$type])->select('id')->one()['id'];
    		return \Yii::$app->db->createCommand()->update('summary',
                array(  
                    'subject'=>$subject, 
                    'content'=>$content,
                ), "id=:id",array(':id'=>$id))->execute();
    	} else {
    		return \Yii::$app->db->createCommand()->insert('summary',
                array(  
                    'subject'=>$subject, 
                    'content'=>$content,
                    'recruitId'=>$recruitId,
                    'date'=>$date,
                    'status'=>1,
                    'type'=>$type,
                    'stuId'=>$stuId
                ))->execute();
    	}
    }

    public function getsummary($username,$type)
    {
    	$stuId = StudentInfo::find()->where(['sno'=>$username])->select('id')->one()['id'];
    	$summary = Summary::find()->andWhere(['stuId'=>$stuId])->andWhere(['type'=>$type])->all();
    	return $summary;
    }

    public function clock($username,$recruitId,$time,$lng,$lat,$address)
    {
    	$stuId = StudentInfo::find()->where(['sno'=>$username])->select('id')->one()['id'];
        $startTime = date("Y-m-d").' 00:00:00';
        $endTime = date("Y-m-d",strtotime("+1 day",strtotime(date("Y-m-d")))).' 00:00:00';
        $list = Clock::find()->andWhere(['stuId'=>$stuId])->andWhere(['recruitId'=>$recruitId])->andWhere(['status'=>1])->andWhere(['>=', 'time', $startTime])->andWhere(['<=', 'time', $endTime])->orderBy(['time' => SORT_ASC])->all();
        $r = RecruitmentInfo::find()->where(['id'=>$recruitId])->select('recruitStart,recruitEnd,clockMor,clockEve,lng,lat,address,scope')->one();
        $start = $r['recruitStart'];
        $end = $r['recruitEnd'];
        $mor = $r['clockMor'];
        $eve = $r['clockEve'];
        $lng2 = $r['lng'];
        $lat2 = $r['lat'];
        $scope = $r['scope'];
        $mf = 0;
        $ef = 0;
        if ($this->getdistance($lng2,$lat2,$lng,$lat)<=$scope) {
            if (substr(explode(' ',$time)[1],0,5)<=$mor) {
                if (count($list) > 0) {
                    return '上班已打卡！打卡时间为：'.$list[0]['time'].'。请勿重复打卡！';
                } else {
                    \Yii::$app->db->createCommand()->insert('clock',
                        array(  
                            'stuId'=>$stuId, 
                            'recruitId'=>$recruitId, 
                            'time'=>$time,
                            'lng'=>$lng,
                            'lat'=>$lat,
                            'address'=>$address,
                            'status'=>1
                        ))->execute();
                    return '打卡成功！';
                }
            } else if (substr(explode(' ',$time)[1],0,5)>=$eve) {
                if (count($list) == 1) {
                    if (substr(explode(' ',$list[0]['time'])[1],0,5)>=$eve) {
                        return '下班已打卡！打卡时间为：'.$list[0]['time'].'。请勿重复打卡！';
                    } else {
                        \Yii::$app->db->createCommand()->insert('clock',
                            array(  
                                'stuId'=>$stuId, 
                                'recruitId'=>$recruitId, 
                                'time'=>$time,
                                'lng'=>$lng,
                                'lat'=>$lat,
                                'address'=>$address,
                                'status'=>1
                            ))->execute();
                        return '打卡成功！';
                    }
                } else if (count($list) == 2) {
                    return '下班已打卡！打卡时间为：'.$list[count($list)-1]['time'].'。请勿重复打卡！';
                } else {
                    \Yii::$app->db->createCommand()->insert('clock',
                        array(  
                            'stuId'=>$stuId, 
                            'recruitId'=>$recruitId, 
                            'time'=>$time,
                            'lng'=>$lng,
                            'lat'=>$lat,
                            'address'=>$address,
                            'status'=>1
                        ))->execute();
                    return '打卡成功！';
                }
            } else {
                return '打卡失败！时间不在打卡范围内！上班打卡时间为：'.$mor.'，下班打卡时间为：'.$eve;
            }

        } else {
            return '打卡失败！请在规定的地点范围内打卡！';
        }
    }

    public function clocklist($username,$recruitId)
    {
    	$nowTime = date("Y-m-d");
    	$stuId = StudentInfo::find()->where(['sno'=>$username])->select('id')->one()['id'];
    	$time = RecruitmentInfo::find()->where(['id'=>$recruitId])->select('recruitStart,recruitEnd,clockMor,clockEve,lng,lat,address,scope')->one();
    	$start = $time['recruitStart'];
    	$end = $time['recruitEnd'];
    	$mor = $time['clockMor'];
        $eve = $time['clockEve'];
        $lng = $time['lng'];
        $lat = $time['lat'];
        $address = $time['address'];
    	$scope = $time['scope'];
    	$list = Clock::find()->andWhere(['stuId'=>$stuId])->andWhere(['recruitId'=>$recruitId])->andWhere(['status'=>1])->andWhere(['>=', 'time', $start])->andWhere(['<=', 'time', $end])->orderBy(['time' => SORT_ASC])->all();
        $group = [];  
        foreach($list as $key=>$value){
            $d = explode(' ',$value['time']);
            $group[$d[0]][] = $value;
        }
        $clock = [];
        $unclock = [];
        foreach ($group as $key => $value) {
            $mf = 0;
            $ef = 0;
            if ($this->getdistance($lng,$lat,$value[0]['lng'],$value[0]['lat'])<=$scope) {
                if (substr(explode(' ',$value[0]['time'])[1],0,5)<=$mor) {
                    $mf = 1;
                }                   
            }
            if ($this->getdistance($lng,$lat,$value[count($value)-1]['lng'],$value[count($value)-1]['lat'])<=$scope) {
                if (substr(explode(' ',$value[count($value)-1]['time'])[1],0,5)>=$eve) {
                    $ef = 1;
                }                    
            }
            if ($mf == 1 && $ef == 1) {
                array_push($clock,$key);
            } else {
                array_push($unclock,$key);
            }
            
        }
        $return['已打卡']['length'] = count($clock);
        $return['已打卡']['data'] = $clock;
        $return['未打卡']['length'] = (strtotime($nowTime)-strtotime($start))/86400-count($clock);
        $return['未打卡']['data'] = '实习开始时间'.$start.'到今天'.$nowTime.'减掉打卡的就是未打卡的啦';        
        return $return;
    }

    //经纬度距离换算
    function getdistance($lng1, $lat1, $lng2, $lat2) {
        $radLat1 = deg2rad($lat1); //deg2rad()函数将角度转换为弧度
        $radLat2 = deg2rad($lat2);
        $radLng1 = deg2rad($lng1);
        $radLng2 = deg2rad($lng2);
        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137 * 1000;
        return $s;
    } 

    public function dayclcok($username,$recruitId)
    {
        $startTime = date("Y-m-d").' 00:00:00';
        $endTime = date("Y-m-d",strtotime("+1 day",strtotime(date("Y-m-d")))).' 00:00:00';
        $stuId = StudentInfo::find()->where(['sno'=>$username])->select('id')->one()['id'];
        $list = Clock::find()->andWhere(['stuId'=>$stuId])->andWhere(['recruitId'=>$recruitId])->andWhere(['status'=>1])->andWhere(['>=', 'time', $startTime])->andWhere(['<=', 'time', $endTime])->orderBy(['time' => SORT_ASC])->all();
        $time = RecruitmentInfo::find()->where(['id'=>$recruitId])->select('clockMor,clockEve,lng,lat,address,scope')->one();
        $mor = $time['clockMor'];
        $eve = $time['clockEve'];
        $lng = $time['lng'];
        $lat = $time['lat'];
        $scope = $time['scope'];
        $mf = 0;
        $ef = 0;
        if ($this->getdistance($lng,$lat,$list[0]['lng'],$list[0]['lat'])<=$scope) {
            if (substr(explode(' ',$list[0]['time'])[1],0,5)<=$mor) {
                $mf = 1;
            }                   
        }
        if ($this->getdistance($lng,$lat,$list[count($list)-1]['lng'],$list[count($list)-1]['lat'])<=$scope) {
            if (substr(explode(' ',$list[count($list)-1]['time'])[1],0,5)>=$eve) {
                $ef = 1;
            }                    
        }
        $return = [];
        if ($mf == 1 && $ef == 1) {
            $return['clock'] = '上下班均已打卡';
        } else if ($mf == 1 && $ef == 0) {
            $return['clock'] = '上班已打卡';
        } else if ($mf == 0 && $ef == 1) {
            $return['clock'] = '下班已打卡';
        } else if ($mf == 0 && $ef == 0) {
            $return['clock'] = '未打卡';
        }
        $return['details'] = $list;
        return $return;
    }

}