<?php

namespace backend\models;

use yii\db\ActiveRecord;

/**
 * User model
 *
 * @property integer $id
 * @property string $color цвет яблока
 * @property string $size остаток яблока (<= 1)
 * @property string $birth_date время "рождения"
 * @property string $fall_date время падения
 */
class AppleAR extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{apple}}';
    }

    /**
     * Получение всех неудаленных яблок
     */
    public static function findExistsApples()
    {
        $apples = AppleAR::find()
            //->select(['color','size','birth_date','fall_date'])
            ->where('size>0')
            ->orderBy('id')
            ->indexBy('id')
            ->asArray()
            ->all();

        foreach ($apples as $k=>$a) {
            if ($a['fall_date']) {
                if (time() - $a['fall_date'] >= 60*60*5) $apples[$k]['state'] = \appleState::rotten->value;
            }
        }
        return $apples;
    }
}
