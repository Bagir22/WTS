<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%comments}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%Article}}`
 */
class m231122_073726_createCommentsTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%comments}}', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer()->notNull(),
            'articleId' => $this->integer()->notNull(),
            'body' => $this->text()->notNull(),
        ]);

        // creates index for column `userId`
        $this->createIndex(
            '{{%idx-comments-userId}}',
            '{{%comments}}',
            'userId'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-comments-userId}}',
            '{{%comments}}',
            'userId',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `articleId`
        $this->createIndex(
            '{{%idx-comments-articleId}}',
            '{{%comments}}',
            'articleId'
        );

        // add foreign key for table `{{%Article}}`
        $this->addForeignKey(
            '{{%fk-comments-articleId}}',
            '{{%comments}}',
            'articleId',
            '{{%Article}}',
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
            '{{%fk-comments-userId}}',
            '{{%comments}}'
        );

        // drops index for column `userId`
        $this->dropIndex(
            '{{%idx-comments-userId}}',
            '{{%comments}}'
        );

        // drops foreign key for table `{{%Article}}`
        $this->dropForeignKey(
            '{{%fk-comments-articleId}}',
            '{{%comments}}'
        );

        // drops index for column `articleId`
        $this->dropIndex(
            '{{%idx-comments-articleId}}',
            '{{%comments}}'
        );

        $this->dropTable('{{%comments}}');
    }
}
