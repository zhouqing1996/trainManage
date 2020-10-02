<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "remind".
 *
 * @property int $id
 * @property string $repeat 重复 每周几提醒 逗号隔开
 * @property string $content
 * @property string $pushId 实习id，以逗号分隔 推送给某实习的所有学生
 * @property int $status
 */
class Remind extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'remind';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['repeat'], 'required'],
            [['content'], 'string'],
            [['status'], 'integer'],
            [['repeat', 'pushId'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'repeat' => 'Repeat',
            'content' => 'Content',
            'pushId' => 'Push ID',
            'status' => 'Status',
        ];
    }
}
