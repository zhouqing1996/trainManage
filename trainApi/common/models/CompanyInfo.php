<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "company_info".
 *
 * @property int $id
 * @property string $comAccount
 * @property string $comName
 * @property string $comArea 所属行业
 * @property int $empCount
 * @property string $comAddress
 * @property string $comPhone
 * @property string $comEmail
 * @property string $comPage
 * @property string $comCharger
 * @property int $status
 *
 * @property RecruitmentInfo[] $recruitmentInfos
 * @property SysPower[] $sysPowers
 * @property User[] $users
 */
class CompanyInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comAccount'], 'required'],
            [['empCount', 'status'], 'integer'],
            [['comAccount'], 'string', 'max' => 255],
            [['comName', 'comPhone', 'comEmail', 'comCharger'], 'string', 'max' => 50],
            [['comArea'], 'string', 'max' => 10],
            [['comAddress'], 'string', 'max' => 200],
            [['comPage'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'comAccount' => 'Com Account',
            'comName' => 'Com Name',
            'comArea' => 'Com Area',
            'empCount' => 'Emp Count',
            'comAddress' => 'Com Address',
            'comPhone' => 'Com Phone',
            'comEmail' => 'Com Email',
            'comPage' => 'Com Page',
            'comCharger' => 'Com Charger',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecruitmentInfos()
    {
        return $this->hasMany(RecruitmentInfo::className(), ['comId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSysPowers()
    {
        return $this->hasMany(SysPower::className(), ['username' => 'comAccount']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['username' => 'comAccount']);
    }
}
