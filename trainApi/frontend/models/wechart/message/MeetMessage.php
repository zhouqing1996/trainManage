<?php
namespace frontend\models\wechart\message;

use Yii;
use yii\base\Model;
use common\models\ModuleScene;
/**
 * Login form
 */
class MeetMessage extends Model
{
    public $meetId;
    public $receiver;
    public $page;

    private $_size=999;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['meetId','page'], 'integer'],
            [['receiver'], 'string', 'max' => 255]
        ];
    }

    public function getMessage()
    {
        if (!$this->validate()) {
            return false;
        }
        $query = ModuleScene::find()->where(['kind'=>3,'status'=>2,'receiver'=>$this->receiver]);
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
