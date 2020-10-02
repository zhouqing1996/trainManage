<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "summary".
 *
 * @property int $id
 * @property string $subject
 * @property string $content
 * @property string $date
 * @property int $stuId
 * @property int $type 1-日总结 2-周总结 3-月总结
 * @property int $status 0-删除
 * @property string $teacherEvaluate
 * @property string $majorEvaluate
 *
 * @property StudentInfo $stu
 */
class Summary extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'summary';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subject', 'content', 'teacherEvaluate', 'majorEvaluate'], 'string'],
            [['date'], 'safe'],
            [['stuId', 'type', 'status'], 'integer'],
            [['stuId'], 'exist', 'skipOnError' => true, 'targetClass' => StudentInfo::className(), 'targetAttribute' => ['stuId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subject' => 'Subject',
            'content' => 'Content',
            'date' => 'Date',
            'stuId' => 'Stu ID',
            'type' => 'Type',
            'status' => 'Status',
            'teacherEvaluate' => 'Teacher Evaluate',
            'majorEvaluate' => 'Major Evaluate',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStu()
    {
        return $this->hasOne(StudentInfo::className(), ['id' => 'stuId']);
    }
}
