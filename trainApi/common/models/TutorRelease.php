<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tutor_release".
 *
 * @property int $id
 * @property string $time 发布时间
 * @property int $comId
 * @property int $majorId
 * @property int $grade
 * @property string $tutor
 * @property int $status
 */
class TutorRelease extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tutor_release';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['time'], 'safe'],
            [['comId', 'majorId', 'grade', 'status'], 'integer'],
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
            'grade' => 'Grade',
            'tutor' => 'Tutor',
            'status' => 'Status',
        ];
    }
}
