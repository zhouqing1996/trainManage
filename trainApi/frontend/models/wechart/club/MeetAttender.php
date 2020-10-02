<?php
namespace frontend\models\wechart\club;

use Yii;
use yii\base\Model;
use common\models\VwAttenderList;
use common\models\VwStaticsSign;
use common\models\StaticsSign;
/**
 * Login form
 */
class MeetAttender extends Model
{
    public $meetId;
    public $page;
    public $userId;
    public $reportId;

    private $_size=999;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['meetId', 'page','reportId','userId'], 'integer']
        ];
    }

    public function getAttenderList()
    {
        if (!$this->validate()) {
            return false;
        }
        $query = VwAttenderList::find()->where(['meetId'=>$this->meetId,'status'=>1]);
        $countQuery = clone $query;
        $page = $this->page?$this->page:1;
        $count = $countQuery->count();
        $total = ceil($count/$this->_size);
        $models = $query->offset(($page-1)*$this->_size)
            ->limit($this->_size)
            ->all();

        return ['pages'=>$page,'count'=>$count,'total'=> $total ,'models'=>$models];
    } 

    public function getSignList()
    {
        if (!$this->validate()) {
            return false;
        }

        $_data = VwStaticsSign::find()->where(['meetId'=>$this->meetId,'arrangeId'=>$this->reportId])->all();

        return ['models'=>$_data];
    }

    public function setSign()
    {
        if (!$this->validate()) {
            return false;
        }

        $_data = new StaticsSign();
        $_data->meetId=$this->meetId;
        $_data->arrangeId=$this->reportId;
        $_data->userId=$this->userId;
        $_data->time=date('Y-m-d',time());
        $_data->save();

        return true;
    }  

}
