<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%history}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%data}}`
 */
class m250730_103718_create_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%history}}', [
            'id' => $this->primaryKey(),
            'data_id' => $this->integer(),
            'ip_address' => $this->string(),
            'created_at' => $this->datetime(),
        ]);

        // creates index for column `data_id`
        $this->createIndex(
            '{{%idx-history-data_id}}',
            '{{%history}}',
            'data_id'
        );

        // add foreign key for table `{{%data}}`
        $this->addForeignKey(
            '{{%fk-history-data_id}}',
            '{{%history}}',
            'data_id',
            '{{%data}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%data}}`
        $this->dropForeignKey(
            '{{%fk-history-data_id}}',
            '{{%history}}'
        );

        // drops index for column `data_id`
        $this->dropIndex(
            '{{%idx-history-data_id}}',
            '{{%history}}'
        );

        $this->dropTable('{{%history}}');
    }
}
