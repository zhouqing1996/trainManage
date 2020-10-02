<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vw_teacher_info".
 *
 * @property int $majorId 院系Id
 * @property string $major
 * @property string $job_num
 * @property string $teacherName 教师姓名
 * @property string $identity 身份证号
 * @property string $sex
 * @property string $rank 职务
 * @property string $post 职称
 * @property int $id 教师Id
 * @property string $contactPhone
 * @property string $email
 * @property int $authority
 * @property int $status
 */
class VwTeacherInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_teacher_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['majorId', 'job_num'], 'required'],
            [['majorId', 'id', 'authority', 'status'], 'integer'],
            [['major'], 'string', 'max' => 100],
            [['job_num'], 'string', 'max' => 255],
            [['teacherName', 'identity', 'contactPhone', 'email'], 'string', 'max' => 50],
            [['sex'], 'string', 'max' => 5],
            [['rank', 'post'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'majorId' => 'Major ID',
            'major' => 'Major',
            'job_num' => 'Job Num',
            'teacherName' => 'Teacher Name',
            'identity' => 'Identity',
            'sex' => 'Sex',
            'rank' => 'Rank',
            'post' => 'Post',
            'id' => 'ID',
            'contactPhone' => 'Contact Phone',
            'email' => 'Email',
            'authority' => 'Authority',
            'status' => 'Status',
        ];
    }
}
