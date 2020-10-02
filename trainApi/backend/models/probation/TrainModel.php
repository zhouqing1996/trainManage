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
use backend\models\probation\RecruitModel;
date_default_timezone_set('PRC');
/**
 * Login form
 */
class TrainModel extends Model{

	public function getList($type,$username)
    {
        if ($type == 'root') {
            $list = RecruitmentInfo::find()->andWhere(['status'=>3])->andWhere(['or',['<=','recruitStart',date("Y-m-d")],['<=','recruitEnd',date("Y-m-d")]])->orderBy([
                'time' => SORT_ASC,
            ])->all();
        } else if ($type == 'company') {
            $comId = CompanyInfo::find()->where(['comAccount'=>$username])->select('id')->one()['id'];
            $list = RecruitmentInfo::find()->andWhere(['status'=>3])->andWhere(['or',['<=','recruitStart',date("Y-m-d")],['<=','recruitEnd',date("Y-m-d")]])->andWhere(['comId'=>$comId])->orderBy([
                'time' => SORT_ASC,
            ])->all();
        } else if ($type == 'teacher') {
            $majorId = TeacherInfo::find()->where(['job_num'=>$username])->select('majorId')->one()['majorId'];
            $list = RecruitmentInfo::find()->andWhere(['status'=>3])->andWhere(['or',['<=','recruitStart',date("Y-m-d")],['<=','recruitEnd',date("Y-m-d")]])->andWhere(['majorId'=>$majorId])->orderBy([
                'time' => SORT_ASC,
            ])->all();
        }
        $model = new RecruitModel();
		return $model->dataHandle($list);
    }

    public function editmap($rowId,$form,$lng,$lat,$address)
    {
    	return \Yii::$app->db->createCommand()->update('recruitment_info', 
                array( 
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