<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sys_function".
 *
 * @property string $funcId
 * @property string $name
 * @property int $type
 * @property string $icon
 * @property string $url
 * @property int $level
 * @property int $pfuncId
 * @property string $sort
 * @property int $status
 */
class SysFunction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sys_function';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['funcId'], 'required'],
            [['type', 'level', 'pfuncId', 'status'], 'integer'],
            [['funcId'], 'string', 'max' => 11],
            [['name', 'icon', 'url', 'sort'], 'string', 'max' => 255],
            [['funcId'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'funcId' => 'Func ID',
            'name' => 'Name',
            'type' => 'Type',
            'icon' => 'Icon',
            'url' => 'Url',
            'level' => 'Level',
            'pfuncId' => 'Pfunc ID',
            'sort' => 'Sort',
            'status' => 'Status',
        ];
    }
}
