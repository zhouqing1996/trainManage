<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tutor_time".
 *
 * @property int $id
 * @property int $year
 * @property int $term
 * @property string $time 截止时间
 * @property int $status
 */
class TutorTime extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tutor_time';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['year', 'term', 'status'], 'integer'],
            [['time'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'year' => 'Year',
            'term' => 'Term',
            'time' => 'Time',
            'status' => 'Status',
        ];
    }
}
