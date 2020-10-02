<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tutor_info".
 *
 * @property int $id 导师id
 * @property string $identity 身份证号
 * @property string $tutorName 导师姓名
 * @property string $companyAccount 所属公司companyAccount
 * @property string $craft 工种
 * @property string $sex 性别
 * @property string $phone 电话
 * @property string $profile 简介
 * @property int $status
 */
class TutorInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tutor_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['identity', 'tutorName', 'companyAccount'], 'required'],
            [['status'], 'integer'],
            [['identity', 'tutorName', 'companyAccount', 'craft', 'phone'], 'string', 'max' => 50],
            [['sex'], 'string', 'max' => 5],
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
            'identity' => 'Identity',
            'tutorName' => 'Tutor Name',
            'companyAccount' => 'Company Account',
            'craft' => 'Craft',
            'sex' => 'Sex',
            'phone' => 'Phone',
            'profile' => 'Profile',
            'status' => 'Status',
        ];
    }
}
