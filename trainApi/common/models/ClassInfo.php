<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "class_info".
 *
 * @property int $id
 * @property int $majorId
 * @property string $className
 * @property string $grade
 * @property int $status
 *
 * @property MajorInfo $major
 * @property StudentInfo[] $studentInfos
 */
class ClassInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'class_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['majorId', 'status'], 'integer'],
            [['className', 'grade'], 'string', 'max' => 50],
            [['majorId'], 'exist', 'skipOnError' => true, 'targetClass' => MajorInfo::className(), 'targetAttribute' => ['majorId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'majorId' => 'Major ID',
            'className' => 'Class Name',
            'grade' => 'Grade',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMajor()
    {
        return $this->hasOne(MajorInfo::className(), ['id' => 'majorId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudentInfos()
    {
        return $this->hasMany(StudentInfo::className(), ['classId' => 'id']);
    }
}
