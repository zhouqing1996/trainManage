<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "survey_questions".
 *
 * @property int $id
 * @property int $surveyId
 * @property string $title
 * @property string $type
 * @property int $isRequired
 * @property string $optionContent
 * @property string $direction
 * @property int $status
 *
 * @property SurveyInfo $survey
 */
class SurveyQuestions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'survey_questions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['surveyId', 'isRequired', 'status'], 'integer'],
            [['title', 'type'], 'string', 'max' => 50],
            [['optionContent'], 'string', 'max' => 1000],
            [['direction'], 'string', 'max' => 255],
            [['surveyId'], 'exist', 'skipOnError' => true, 'targetClass' => SurveyInfo::className(), 'targetAttribute' => ['surveyId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'surveyId' => 'Survey ID',
            'title' => 'Title',
            'type' => 'Type',
            'isRequired' => 'Is Required',
            'optionContent' => 'Option Content',
            'direction' => 'Direction',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSurvey()
    {
        return $this->hasOne(SurveyInfo::className(), ['id' => 'surveyId']);
    }
}
