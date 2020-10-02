<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "major_info".
 *
 * @property int $id
 * @property string $major
 * @property string $profile 简介
 * @property int $status
 *
 * @property ClassInfo[] $classInfos
 * @property RecruitmentInfo[] $recruitmentInfos
 * @property TeacherInfo[] $teacherInfos
 */
class MajorInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'major_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['major'], 'string', 'max' => 100],
            [['profile'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'major' => 'Major',
            'profile' => 'Profile',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClassInfos()
    {
        return $this->hasMany(ClassInfo::className(), ['majorId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecruitmentInfos()
    {
        return $this->hasMany(RecruitmentInfo::className(), ['majorId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherInfos()
    {
        return $this->hasMany(TeacherInfo::className(), ['majorId' => 'id']);
    }
}
