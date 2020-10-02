<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "teacher_info".
 *
 * @property int $id 教师Id
 * @property string $job_num
 * @property int $majorId 院系Id
 * @property string $teacherName 教师姓名
 * @property string $identity 身份证号
 * @property string $sex
 * @property string $rank 职务
 * @property string $post 职称
 * @property string $contactPhone
 * @property string $email
 * @property int $authority
 * @property int $status
 *
 * @property RecruitmentInfo[] $recruitmentInfos
 * @property SysPower[] $sysPowers
 * @property MajorInfo $major
 * @property User[] $users
 */
class TeacherInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'teacher_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['job_num', 'majorId'], 'required'],
            [['majorId', 'authority', 'status'], 'integer'],
            [['job_num'], 'string', 'max' => 255],
            [['teacherName', 'identity', 'contactPhone', 'email'], 'string', 'max' => 50],
            [['sex'], 'string', 'max' => 5],
            [['rank', 'post'], 'string', 'max' => 20],
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
            'job_num' => 'Job Num',
            'majorId' => 'Major ID',
            'teacherName' => 'Teacher Name',
            'identity' => 'Identity',
            'sex' => 'Sex',
            'rank' => 'Rank',
            'post' => 'Post',
            'contactPhone' => 'Contact Phone',
            'email' => 'Email',
            'authority' => 'Authority',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecruitmentInfos()
    {
        return $this->hasMany(RecruitmentInfo::className(), ['teacherId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSysPowers()
    {
        return $this->hasMany(SysPower::className(), ['username' => 'job_num']);
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
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['username' => 'job_num']);
    }
}
