<?php
namespace frontend\models\wechart\club;

use Yii;
use yii\base\Model;
use common\models\ModuleScene;
/**
 * Login form
 */
class MeetNotices extends Model
{
    public $meetId;
    public $page;

    private $_size=999;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['meetId'], 'required'],
            [['meetId','page'], 'integer']
        ];
    }

    public function getNotices()
    {
        if (!$this->validate()) {
            return false;
        }
        $query = ModuleScene::find()->where(['meetId'=>$this->meetId,'kind'=>1,'status'=>2]);
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
