<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "survey_info".
 *
 * @property int $id
 * @property string $username
 * @property string $title
 * @property int $type 问卷类型：1-学校调查企业，2-学校调查学生，3-学校调查教师，4-企业调查学校、教师、学生,5-毕业生追踪，6-其他，7-评价量表模板;0-校企互通问卷；10毕业生追踪；20量表问卷 
 * @property string $direction
 * @property string $addTime
 * @property string $endTime
 * @property string $thanksMsg
 * @property int $status 0-删除，1-保存未发布，2-已发布，3-已结束
 * @property string $pushId
 * @property string $content
 */
class SurveyInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'survey_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'status'], 'integer'],
            [['pushId', 'content'], 'string'],
            [['username', 'direction', 'addTime', 'endTime', 'thanksMsg'], 'string', 'max' => 255],
            [['title'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'title' => 'Title',
            'type' => 'Type',
            'direction' => 'Direction',
            'addTime' => 'Add Time',
            'endTime' => 'End Time',
            'thanksMsg' => 'Thanks Msg',
            'status' => 'Status',
            'pushId' => 'Push ID',
            'content' => 'Content',
        ];
    }
}
