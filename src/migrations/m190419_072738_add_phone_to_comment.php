<?php

use floor12\comments\models\Comment;
use yii\db\Migration;

/**
 * Class m190419_072738_add_email_to_comment
 */
class m190419_072738_add_phone_to_comment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            Comment::tableName(),
            'author_email',
            $this
                ->string(14)
                ->null()
                ->comment('Phone number')
        );

        $this->createIndex('idx-comment-author_phone', Comment::tableName(), 'author_phone');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Comment::tableName(), 'author_phone');
    }

}
