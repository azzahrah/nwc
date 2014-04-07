<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "setting".
 *
 * @property integer $id
 * @property string $key
 * @property string $value
 */
class Setting extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'setting';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['key', 'value'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'key' => 'Key',
            'value' => 'Value',
        ];
    }

    public static function getAll() {
        $all = Setting::find()->all();
        $settings = [];
        foreach ($all as $a) {
            $settings[$a->key] = $a->value;
        }
        return $settings;
    }

    public static function get($key) {
        $setting = Setting::find()->where(['key' => $key])->one();
        
        if (isset($setting)) {
            if (strpos($key,'path') >= 0) {
                $setting->value = rtrim($setting->value, '/');
            }
            
            return $setting->value;
        } else {
            return "";
        }
    }

}
