<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "client".
 *
 * @property int $id
 * @property string $title
 * @property string $title_dir
 * @property string $title_file
 * @property string $header_in_file
 * @property bool|null $is_active
 */
class Client extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'client';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'title_dir', 'title_file', 'header_in_file'], 'required'],
            [['is_active'], 'boolean'],
            [['title', 'title_dir', 'title_file', 'header_in_file'], 'string', 'max' => 255],
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
            'title_dir' => 'Title Dir',
            'title_file' => 'Title File',
            'header_in_file' => 'Header In File',
            'is_active' => 'Is Active',
        ];
    }
}
