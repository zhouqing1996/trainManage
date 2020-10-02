<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vw_class_info".
 *
 * @property int $id
 * @property int $majorId
 * @property string $className
 * @property string $grade
 * @property int $status
 * @property string $major
 */
class VwClassInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_class_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'majorId', 'status'], 'integer'],
            [['className', 'grade'], 'string', 'max' => 50],
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
            'majorId' => 'Major ID',
            'className' => 'Class Name',
            'grade' => 'Grade',
            'status' => 'Status',
            'major' => 'Major',
        ];
    }
}
