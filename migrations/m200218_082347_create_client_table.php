<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%client}}`.
 */
class m200218_082347_create_client_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%client}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'title_dir' => $this->string()->notNull(),
            'title_file' => $this->string()->notNull(),
            'header_in_file' => $this->string()->notNull(),
            'is_active' => $this->boolean()->defaultValue(false),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%client}}');
    }
}
