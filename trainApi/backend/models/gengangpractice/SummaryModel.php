<?php
namespace backend\models\gengangpractice;

use Yii;
use yii\base\Model;
use common\models\RecruitmentInfo;
use common\models\CompanyInfo;
use common\models\MajorInfo;
use common\models\TeacherInfo;
use common\models\StudentInfo;
use common\models\ClassInfo;
date_default_timezone_set('PRC');

class SummaryModel extends Model{

	public function getcom()
	{
		return CompanyInfo::find()->where(['status'=>1])->all();
	}

	public function getrecuitst($id)
	{
		$recruit = RecruitmentInfo::find()->andWhere(['comId'=>$id])->andWhere(['status'=>3])->andWhere(['>', 'recruitEnd', date("Y-m-d")])->andWhere(['NOT', ['arrangeStuId' => null]])->all();
		$return = [];
		foreach ($recruit as $key => $value) {
			$aa=[];
			$aa['recruit']['id'] = $value['id'];             		
    		$aa['recruit']['company'] = CompanyInfo::find()->where(['id'=>$value['comId']])->select('comName')->one()['comName'];
    		$aa['recruit']['subject'] = $value['subject'];
    		$aa['recruit']['content'] = $value['content'];
    		$aa['recruit']['address'] = $value['address'];
    		$aa['recruit']['teacher'] = TeacherInfo::find()->where(['id'=>$value['teacherId']])->select('teacherName')->one()['teacherName'];
    		$aa['recruit']['recruitStart'] = $value['recruitStart'];
    		$aa['recruit']['recruitEnd'] = $value['recruitEnd'];
    		$aa['recruit']['major'] = MajorInfo::find()->where(['id'=>$value['majorId']])->select('major')->one()['major'];
			if (strpos($value['arrangeStuId'],',')) {
                $a = explode(',',$value['arrangeStuId']);
                $b=[];
                foreach ($a as $key2 => $value2) {                	
                	$c['id'] = $value2;
                	$c['stuName'] = StudentInfo::find()->where(['id'=>$value2])->select('stuName')->one()['stuName'];
                	array_push($b,$c);
                }
                $aa['student'] = $b;
            } else {
            	$aa['student']['id'] = $value['arrangeStuId'];
            	$aa['student']['stuName'] = StudentInfo::find()->where(['id'=>$value['arrangeStuId']])->select('stuName')->one()['stuName'];
            }
            array_push($return,$aa);
		}
		return $return;
	}

}

?>