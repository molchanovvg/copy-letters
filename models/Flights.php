<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "flights".
 *
 * @property int $id
 * @property string $title
 * @property string $title_in_file
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
            [['title', 'title_in_file'], 'required'],
            [['is_active'], 'boolean'],
            [['title', 'title_in_file'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Краткое наименование',
            'title_in_file' => 'Наименование в файле',
            'is_active' => 'Отображать в панели',
        ];
    }
}
