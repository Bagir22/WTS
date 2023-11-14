<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%article}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m231029_160022_createArticleTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%article}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'body' => $this->text(),
            'userId' => $this->integer()->notNull(),
        ]);

        // creates index for column `userId`
        $this->createIndex(
            '{{%idx-article-userId}}',
            '{{%article}}',
            'userId'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-article-userId}}',
            '{{%article}}',
            'userId',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-article-userId}}',
            '{{%article}}'
        );

        // drops index for column `userId`
        $this->dropIndex(
            '{{%idx-article-userId}}',
            '{{%article}}'
        );

        $this->dropTable('{{%article}}');
    }
}
