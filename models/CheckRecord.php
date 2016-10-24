<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "check_record".
 *
 * @property integer $id
 * @property integer $tid
 * @property integer $check_in
 * @property integer $check_out
 * @property string $flag
 * @property string $note
 */
class CheckRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'check_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tid'], 'required'],
            [['check_in', 'check_out'], 'integer'],
            [['tid','flag', 'note'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tid' => 'Tid',
            'check_in' => 'Check In',
            'check_out' => 'Check Out',
            'date' => 'date',
            'flag' => 'Flag',
            'note' => 'Note',
        ];
    }
}
