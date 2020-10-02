<?php
namespace frontend\models\web;

use Yii;
use yii\base\Model;
use common\models\MeetList;
/**
 * Login form
 */
class MeetsList extends Model
{
    public $page;

    private $_size=99;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['page'], 'integer']
        ];
    }
    public function getMeetList()
    {
        if (!$this->validate()) {
            return false;
        }

        $query =MeetList::find()->where(['delFlag'=>1,'status'=>1])->orderBy(["id"=> SORT_DESC]);
        $countQuery = clone $query;
        $page = $this->page?$this->page:1;
        $count = $countQuery->count();
        $total = ceil($count/$this->_size);
        $models = $query->offset(($page-1)*$this->_size)
            ->limit($this->_size)
            ->all();

        return ['pages'=>$page,'count'=>$count,'total'=> $total ,'models'=>$models];
    } 

}
