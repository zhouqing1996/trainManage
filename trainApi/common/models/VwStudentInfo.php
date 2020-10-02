<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vw_student_info".
 *
 * @property int $id 学生ID
 * @property string $stuName 学生姓名
 * @property string $sno 学号
 * @property string $identity 身份证号
 * @property string $sex 性别
 * @property string $bornDate
 * @property int $classId
 * @property string $contactPhone 联系电话
 * @property string $email 邮箱
 * @property int $authority 权限：5学生管理员，6普通学生
 * @property int $status
 * @property string $className
 * @property int $majorId
 * @property string $major
 */
class VwStudentInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_student_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'classId', 'authority', 'status', 'majorId'], 'integer'],
            [['stuName', 'sno', 'classId'], 'required'],
            [['bornDate'], 'safe'],
            [['stuName', 'sno', 'identity', 'contactPhone', 'email', 'className'], 'string', 'max' => 50],
            [['sex'], 'string', 'max' => 5],
            [['major'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stuName' => 'Stu Name',
            'sno' => 'Sno',
            'identity' => 'Identity',
            'sex' => 'Sex',
            'bornDate' => 'Born Date',
            'classId' => 'Class ID',
            'contactPhone' => 'Contact Phone',
            'email' => 'Email',
            'authority' => 'Authority',
            'status' => 'Status',
            'className' => 'Class Name',
            'majorId' => 'Major ID',
            'major' => 'Major',
        ];
    }
}
