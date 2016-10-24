<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pai_user".
 *
 * @property string $user_id
 * @property string $user_name
 * @property string $user_sex
 * @property string $admin
 */
class PaiUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pai_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'user_name'], 'string', 'max' => 200],
            [['user_sex', 'admin'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'user_name' => '用户名称',
            'user_sex' => '用户性别',
            'admin' => '是否为管理员',
        ];
    }
}
