<?php
namespace frontend\models\wechart\club;

use Yii;
use yii\base\Model;
use common\models\ModuleScene;
/**
 * Login form
 */
class MeetArrange extends Model
{
    public $id;
    public $meetId;
    public $author;
    public $title;
    public $content;
    public $type;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['meetId','id','type'], 'integer'],
            [['title', 'author'], 'string', 'max' => 255]
        ];
    }

}
