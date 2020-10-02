<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "answer_source".
 *
 * @property int $id
 * @property string $userName
 * @property int $surveyId
 * @property string $ip
 * @property string $subtime
 * @property string $usetime
 * @property string $city
 * @property string $source
 * @property int $status
 */
class AnswerSource extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'answer_source';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['surveyId', 'status'], 'integer'],
            [['userName', 'ip', 'subtime', 'usetime', 'city', 'source'], 'string', 'max' => 255],
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
            'ip' => 'Ip',
            'subtime' => 'Subtime',
            'usetime' => 'Usetime',
            'city' => 'City',
            'source' => 'Source',
            'status' => 'Status',
        ];
    }
}
