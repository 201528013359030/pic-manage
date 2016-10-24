<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "attendancer".
 *
 * @property integer $id
 * @property string $eid
 * @property string $uid
 * @property string $name
 * @property string $mobile
 * @property integer $time
 * @property integer $level
 */
class Attendancer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attendancer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['eid', 'uid', 'name', 'mobile', 'time'], 'required'],
            [['time','sort'], 'integer'],
            [['eid', 'uid', 'name','department', 'mobile'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'eid' => 'Eid',
            'uid' => 'Uid',
            'name' => '姓名',
            'mobile' => '手机号码',
            'department' => '所在部门',
            'sort' => '显示顺序',
            'time' => 'Time',
            'level' => 'Level',
        ];
    }
}
