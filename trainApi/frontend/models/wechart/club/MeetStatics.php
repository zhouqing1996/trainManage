<?php
namespace frontend\models\wechart\club;

use Yii;
use yii\base\Model;
use common\models\StaticsSign;
use common\models\StaticsInteration;
use common\models\StaticsMaterialDownload;
use common\models\MeetArrangeList;
/**
 * Login form
 */
class MeetStatics extends Model
{
    public $meetId;
    public $reportId;

    private $_size=999;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['meetId','reportId'], 'integer']
        ];
    }

    public function getStatics()
    {
        if (!$this->validate()) {
            return false;
        }

        $_sign = StaticsSign::find()->where(['meetId'=>$this->meetId,'arrangeId'=>$this->reportId])->count();

        $_interation = StaticsInteration::find()->where(['meetId'=>$this->meetId,'arrangeId'=>$this->reportId])->count();

        $_material = StaticsMaterialDownload::find()->where(['meetId'=>$this->meetId,'arrangeId'=>$this->reportId])->count();

        $_info=MeetArrangeList::find()->where(['meetId'=>$this->meetId,'id'=>$this->reportId])->one();

        return ['sign'=>$_sign,'interation'=>$_interation,'material'=> $_material,'subject'=>$_info->subject,'time'=>$_info->startTime];
    } 

}
