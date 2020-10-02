<?php
namespace frontend\models\wechart\club;

use Yii;
use yii\base\Model;
use common\models\VwAttenderList;
use common\models\MeetAttenderList;
use common\models\MeetArrangeList;
/**
 * Login form
 */
class MeetList extends Model
{
    public $userId;
    public $kind;
    public $name;
    public $page;
    public $sortType;
    public $sortKind;
    public $meetId;

    private $_kind;
    private $_size=8;
    private $_sortType;
    private $_sortKind;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId'], 'required'],
            [['userId','page'], 'integer']
        ];
    }
    public function getMeetList()
    {
        if (!$this->validate()) {
            return false;
        }

        $userId=$this->userId;
        
        $_kind=$this->kind?$this->kind:1;
        $_sortType=$this->sortType?$this->sortType:'id';

        if(!$this->sortKind||$this->sortKind=='asc'){
            $_sortKind=SORT_ASC;
        }else{
            $_sortKind=SORT_DESC;
        }

        $query =VwAttenderList::find()->where(['status'=>$_kind,'userId'=>$userId])->orderBy([$_sortType => $_sortKind]);
        $countQuery = clone $query;
        $page = $this->page?$this->page:1;
        $count = $countQuery->count();
        $total = ceil($count/$this->_size);
        $models = $query->offset(($page-1)*$this->_size)
            ->limit($this->_size)
            ->all();

        return ['pages'=>$page,'count'=>$count,'total'=> $total ,'models'=>$models];
    } 

    public function getMeetReportList()
    {
        if (!$this->validate()) {
            return false;
        }
        $now=date("Y-m-d",time());
        $time=date('Y-m-d H:i:s',time());
        $result=[];
        $_data =VwAttenderList::find()->where(['status'=>1,'userId'=>$this->userId])

            ->all();
        foreach ($_data as $key => $value) {
            $_meet=MeetArrangeList::find()->where(['status'=>1,'meetId'=>$value->meetId])->all();
            foreach($_meet as $key => $value){
                array_push($result, $value);
            }
        }
            // ->andWhere(['<', 'startDate',$now ])
            // ->andWhere(['>', 'endDate',$now ])
        return $result;
    } 

}
