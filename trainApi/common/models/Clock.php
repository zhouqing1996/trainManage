<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "clock".
 *
 * @property int $id
 * @property int $stuId
 * @property string $time
 * @property string $address
 * @property int $recruitId
 * @property int $status
 * @property string $lng 经度
 * @property string $lat 纬度
 *
 * @property RecruitmentInfo $recruit
 * @property StudentInfo $stu
 */
class Clock extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'clock';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['stuId', 'recruitId', 'status'], 'integer'],
            [['time'], 'safe'],
            [['address', 'lng', 'lat'], 'string'],
            [['recruitId'], 'exist', 'skipOnError' => true, 'targetClass' => RecruitmentInfo::className(), 'targetAttribute' => ['recruitId' => 'id']],
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
            'stuId' => 'Stu ID',
            'time' => 'Time',
            'address' => 'Address',
            'recruitId' => 'Recruit ID',
            'status' => 'Status',
            'lng' => 'Lng',
            'lat' => 'Lat',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecruit()
    {
        return $this->hasOne(RecruitmentInfo::className(), ['id' => 'recruitId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStu()
    {
        return $this->hasOne(StudentInfo::className(), ['id' => 'stuId']);
    }
}
