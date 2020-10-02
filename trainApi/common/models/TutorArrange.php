<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tutor_arrange".
 *
 * @property int $id
 * @property int $releaseId
 * @property int $tutorIdentity
 * @property string $student
 * @property int $status
 */
class TutorArrange extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tutor_arrange';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['releaseId', 'tutorIdentity', 'status'], 'integer'],
            [['student'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'releaseId' => 'Release ID',
            'tutorIdentity' => 'Tutor Identity',
            'student' => 'Student',
            'status' => 'Status',
        ];
    }
}
