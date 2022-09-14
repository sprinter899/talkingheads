<?php

namespace app\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\mongodb\ActiveRecord;

class BaseModel extends ActiveRecord
{

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    public function behaviors()
    {
        return [
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created',
                'updatedByAttribute' => 'updated',
            ],
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created',
                'updatedAtAttribute' => 'updated',
                'value' => date('Y-m-d H:i:s'),
            ],
        ];
    }

    /*
    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            $date = date('Y-m-d H:i:s');
            if ($this->isNewRecord) {
                $this->created = $date;
            }
            $this->updated = $date;
            return true;
        }
        return false;
    }
    */

}