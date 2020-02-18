<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%flights}}`.
 */
class m200218_082336_create_flights_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%flights}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'is_active' => $this->boolean()->defaultValue(false),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%flights}}');
    }
}
