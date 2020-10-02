<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "recruitment_info".
 *
 * @property int $id
 * @property int $comId 企业ID
 * @property string $startDate 开始时间
 * @property string $endDate 截止时间
 * @property string $studentEnddate
 * @property string $content 岗位内容
 * @property int $teacherId 指导教师
 * @property string $time 发布时间
 * @property int $major 指定发给某个院系
 * @property int $status 招聘信息状态：0-待审核 1-审核通过 2-审核未通过 3-企业审核通过 4-企业审核未通过 5-已分配 6-待分配  9-删除
 * @property string $subject 主题
 * @property string $studentId 申请该实习的所有学生id
 * @property string $majorId
 * @property int $num 实习人数
 * @property string $arrangeStuId 教师安排的学生
 * @property string $recruitStart 实习开始时间
 * @property string $recruitEnd 实习结束时间
 * @property string $clockMor
 * @property string $clockEve
 * @property string $lng
 * @property string $lat
 * @property string $address
 * @property int $scope
 *
 * @property Clock[] $clocks
 */
class RecruitmentInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'recruitment_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comId', 'teacherId', 'major', 'status', 'num', 'scope'], 'integer'],
            [['startDate', 'endDate', 'studentEnddate', 'time', 'recruitStart', 'recruitEnd'], 'safe'],
            [['content', 'subject', 'studentId', 'majorId', 'arrangeStuId', 'clockMor', 'clockEve', 'lng', 'lat', 'address'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'comId' => 'Com ID',
            'startDate' => 'Start Date',
            'endDate' => 'End Date',
            'studentEnddate' => 'Student Enddate',
            'content' => 'Content',
            'teacherId' => 'Teacher ID',
            'time' => 'Time',
            'major' => 'Major',
            'status' => 'Status',
            'subject' => 'Subject',
            'studentId' => 'Student ID',
            'majorId' => 'Major ID',
            'num' => 'Num',
            'arrangeStuId' => 'Arrange Stu ID',
            'recruitStart' => 'Recruit Start',
            'recruitEnd' => 'Recruit End',
            'clockMor' => 'Clock Mor',
            'clockEve' => 'Clock Eve',
            'lng' => 'Lng',
            'lat' => 'Lat',
            'address' => 'Address',
            'scope' => 'Scope',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClocks()
    {
        return $this->hasMany(Clock::className(), ['recruitId' => 'id']);
    }
}
