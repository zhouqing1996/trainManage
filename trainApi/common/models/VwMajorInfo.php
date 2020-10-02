<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vw_major_info".
 *
 * @property string $major
 * @property string $profile 简介
 * @property int $majorStatus
 * @property string $super 1-超级管理员，2-专业管理员，3-班主任，0-企业，5-学生
 * @property string $userkind 1-教师用户，2-企业用户
 * @property string $powerStatus
 * @property string $job_num
 * @property string $teacherName 教师姓名
 * @property int $teacherStatus
 * @property int $syspowerId
 * @property string $username
 * @property string $majorName
 * @property string $company
 * @property string $practice
 * @property string $requirement
 * @property string $tracking
 * @property string $statistics
 * @property string $system
 * @property int $majorId
 */
class VwMajorInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_major_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['majorStatus', 'teacherStatus', 'syspowerId', 'majorId'], 'integer'],
            [['super', 'userkind', 'majorName', 'company', 'practice', 'requirement', 'tracking', 'statistics', 'system'], 'string'],
            [['major'], 'string', 'max' => 100],
            [['profile', 'powerStatus', 'job_num', 'username'], 'string', 'max' => 255],
            [['teacherName'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'major' => 'Major',
            'profile' => 'Profile',
            'majorStatus' => 'Major Status',
            'super' => 'Super',
            'userkind' => 'Userkind',
            'powerStatus' => 'Power Status',
            'job_num' => 'Job Num',
            'teacherName' => 'Teacher Name',
            'teacherStatus' => 'Teacher Status',
            'syspowerId' => 'Syspower ID',
            'username' => 'Username',
            'majorName' => 'Major Name',
            'company' => 'Company',
            'practice' => 'Practice',
            'requirement' => 'Requirement',
            'tracking' => 'Tracking',
            'statistics' => 'Statistics',
            'system' => 'System',
            'majorId' => 'Major ID',
        ];
    }
}
