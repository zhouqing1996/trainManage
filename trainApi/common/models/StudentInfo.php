<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "student_info".
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
 *
 * @property ClassInfo $class
 * @property SysPower[] $sysPowers
 * @property User[] $users
 */
class StudentInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'student_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['stuName', 'sno', 'classId'], 'required'],
            [['bornDate'], 'safe'],
            [['classId', 'authority', 'status'], 'integer'],
            [['stuName', 'sno', 'identity', 'contactPhone', 'email'], 'string', 'max' => 50],
            [['sex'], 'string', 'max' => 5],
            [['classId'], 'exist', 'skipOnError' => true, 'targetClass' => ClassInfo::className(), 'targetAttribute' => ['classId' => 'id']],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClass()
    {
        return $this->hasOne(ClassInfo::className(), ['id' => 'classId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSysPowers()
    {
        return $this->hasMany(SysPower::className(), ['username' => 'sno']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['username' => 'sno']);
    }
}
