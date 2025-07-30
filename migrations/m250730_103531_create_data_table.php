<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%data}}`.
 */
class m250730_103531_create_data_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%data}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'url' => $this->string(),
            'code' => $this->string(6),
            'qr_file' => $this->string(),
            'count' => $this->integer()->defaultValue(0),
            'created_at' => $this->datetime(),
            'updated_at' => $this->datetime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%data}}');
    }
}
