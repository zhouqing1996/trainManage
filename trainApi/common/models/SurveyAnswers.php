<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "survey_answers".
 *
 * @property int $id
 * @property string $userName
 * @property int $surveyId
 * @property int $questionId
 * @property string $content
 * @property string $beginTime
 * @property string $endTime
 * @property string $ip
 * @property int $status
 */
class SurveyAnswers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'survey_answers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['surveyId', 'questionId', 'status'], 'integer'],
            [['userName', 'content', 'beginTime', 'endTime', 'ip'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userName' => 'User Name',
            'surveyId' => 'Survey ID',
            'questionId' => 'Question ID',
            'content' => 'Content',
            'beginTime' => 'Begin Time',
            'endTime' => 'End Time',
            'ip' => 'Ip',
            'status' => 'Status',
        ];
    }
}
