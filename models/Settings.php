<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "settings".
 *
 * @property int $id
 * @property string $title
 * @property string $label
 * @property string $value
 */
class Settings extends \yii\db\ActiveRecord
{
    public CONST WORK_DIR = 'work-dir';
    public CONST SEARCH_DEPTH = 'search-depth';

    public static $settingKeyList = [
        self::WORK_DIR => [
            'id' => self::WORK_DIR,
            'title' => 'Рабочий каталог'
        ],
        self::SEARCH_DEPTH => [
            'id' => self::SEARCH_DEPTH,
            'title' => 'Глубина поиска'
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'label', 'value'], 'required'],
            [['title', 'label', 'value'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Наименование',
            'label' => 'Ключ',
            'value' => 'Значение',
        ];
    }
}
