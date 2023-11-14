<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%accessToken}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m231029_160352_createAccessTokenTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%accessToken}}', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer()->notNull()->unique(),
            'token' => $this->string()->notNull()->unique(),
        ]);

        // creates index for column `userId`
        $this->createIndex(
            '{{%idx-accessToken-userId}}',
            '{{%accessToken}}',
            'userId'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-accessToken-userId}}',
            '{{%accessToken}}',
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
            '{{%fk-accessToken-userId}}',
            '{{%accessToken}}'
        );

        // drops index for column `userId`
        $this->dropIndex(
            '{{%idx-accessToken-userId}}',
            '{{%accessToken}}'
        );

        $this->dropTable('{{%accessToken}}');
    }
}
