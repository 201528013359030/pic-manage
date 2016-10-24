<?php

namespace app\frame\models;

use Yii;

/**
 * This is the model class for table "lappinfo".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $url
 * @property string $icon
 * @property string $icon_group
 * @property string $icon_group_selected
 * @property string $api_key
 * @property integer $notice_way
 * @property integer $show_way
 * @property integer $android_ver
 * @property integer $ios_ver
 * @property integer $pc_ver
 * @property integer $version
 * @property integer $typeid
 * @property string $eid
 * @property integer $time
 */
class Lappinfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lappinfo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['notice_way', 'show_way', 'android_ver', 'ios_ver', 'pc_ver', 'version', 'typeid', 'time'], 'integer'],
            [['eid', 'time'], 'required'],
            [['name', 'description', 'api_key'], 'string', 'max' => 100],
            [['url', 'icon', 'icon_group', 'icon_group_selected'], 'string', 'max' => 500],
            [['eid'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'url' => 'Url',
            'icon' => 'Icon',
            'icon_group' => 'Icon Group',
            'icon_group_selected' => 'Icon Group Selected',
            'api_key' => 'Api Key',
            'notice_way' => 'Notice Way',
            'show_way' => 'Show Way',
            'android_ver' => 'Android Ver',
            'ios_ver' => 'Ios Ver',
            'pc_ver' => 'Pc Ver',
            'version' => 'Version',
            'typeid' => 'Typeid',
            'eid' => 'Eid',
            'time' => 'Time',
        ];
    }
}
