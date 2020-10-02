<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sys_power".
 *
 * @property int $id
 * @property string $username
 * @property string $super 1-超级管理员，2-模块管理员，3-院系管理员
 * @property string $userkind 1-教师用户，2-企业用户
 * @property int $majorId
 * @property string $major
 * @property string $company
 * @property string $practice
 * @property string $requirement
 * @property string $tracking
 * @property string $apprenticeship
 * @property string $system
 * @property string $status
 *
 * @property TeacherInfo $username0
 * @property MajorInfo $major0
 */
class SysPower extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sys_power';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['super', 'userkind', 'major', 'company', 'practice', 'requirement', 'tracking', 'apprenticeship', 'system'], 'string'],
            [['majorId'], 'integer'],
            [['username', 'status'], 'string', 'max' => 255],
            [['username'], 'exist', 'skipOnError' => true, 'targetClass' => TeacherInfo::className(), 'targetAttribute' => ['username' => 'job_num']],
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
            'username' => 'Username',
            'super' => 'Super',
            'userkind' => 'Userkind',
            'majorId' => 'Major ID',
            'major' => 'Major',
            'company' => 'Company',
            'practice' => 'Practice',
            'requirement' => 'Requirement',
            'tracking' => 'Tracking',
            'apprenticeship' => 'Apprenticeship',
            'system' => 'System',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsername0()
    {
        return $this->hasOne(TeacherInfo::className(), ['job_num' => 'username']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMajor0()
    {
        return $this->hasOne(MajorInfo::className(), ['id' => 'majorId']);
    }
}
