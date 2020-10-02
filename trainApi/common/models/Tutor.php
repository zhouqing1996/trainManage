<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tutor".
 *
 * @property int $id
 * @property string $time
 * @property int $comId
 * @property int $majorId
 * @property string $tutor
 */
class Tutor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tutor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['time'], 'safe'],
            [['comId', 'majorId'], 'integer'],
            [['tutor'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'time' => 'Time',
            'comId' => 'Com ID',
            'majorId' => 'Major ID',
            'tutor' => 'Tutor',
        ];
    }
}
