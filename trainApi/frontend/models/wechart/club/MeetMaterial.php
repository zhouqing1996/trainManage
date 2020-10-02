<?php
namespace frontend\models\wechart\club;

use Yii;
use yii\base\Model;
use common\models\ModuleScene;
use common\models\StaticsMaterialDownload;
/**
 * Login form
 */
class MeetMaterial extends Model
{
    public $meetId;
    public $page;
    public $userId;
    public $sceneId;
    public $reportId;

    private $_size=999;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['meetId','reportId'], 'required'],
            [['meetId','page','sceneId','reportId'], 'integer'],
            [['userId'], 'string']
        ];
    }

    public function getMaterial()
    {
        if (!$this->validate()) {
            return false;
        }
        $query = ModuleScene::find()->where(['meetId'=>$this->meetId,'reportId'=>$this->reportId,'kind'=>4,'status'=>2]);
        $countQuery = clone $query;
        $page = $this->page?$this->page:1;
        $count = $countQuery->count();
        $total = ceil($count/$this->_size);
        $models = $query->offset(($page-1)*$this->_size)
            ->limit($this->_size)
            ->all();

        return ['pages'=>$page,'count'=>$count,'total'=> $total ,'models'=>$models];
    }

    public function loadMaterial()
    {
        if (!$this->validate())
            return false;
        
        $_data=new StaticsMaterialDownload();
        $_data->meetId=$this->meetId;
        $_data->userId=$this->userId;
        $_data->arrangeId=$this->reportId;
        $_data->sceneId=$this->sceneId;
        $_data->time=date('y-m-d',time());
        if($_data->save())
            return $_data;
        else
            return false;
    }
}
