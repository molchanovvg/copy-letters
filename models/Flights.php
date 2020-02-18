<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "flights".
 *
 * @property int $id
 * @property string $title
 * @property bool|null $is_active
 */
class Flights extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'flights';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['is_active'], 'boolean'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'is_active' => 'Is Active',
        ];
    }
}
