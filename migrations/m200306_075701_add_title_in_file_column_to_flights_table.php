<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%flights}}`.
 */
class m200306_075701_add_title_in_file_column_to_flights_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('flights', 'title_in_file', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
